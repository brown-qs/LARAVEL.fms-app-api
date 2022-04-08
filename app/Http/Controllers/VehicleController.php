<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
 * (c) Hare Digital
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c) Hare Digital
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Http\Controllers;

use App\Models\AssignedUnits;
use App\Models\AssignedUnitsDealership;
use App\Models\BatteryType;
use App\Models\CatType;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\IncidentEvent;
use App\Models\PendingCommand;
use App\Models\PhoneID;
use App\Models\SoftTag;
use App\Models\Subscription;
use App\Models\Unit;
use App\Models\UnitInstallStatus;
use App\Models\Vehicle;
use App\Models\VehicleJourney;
use App\Models\VehicleNote;
use App\Models\VehiclePosition;
use App\Models\VtsTag;
use App\Transformers\DriverTransformer;
use App\Transformers\VehicleJourneyTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * VehicleGroupController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleController extends AbstractApiController
{

    const INCIDENT_TYPES = ['LMS', 'OSS', 'SSF', 'TLF'];

    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $authUser = $this->request->get('user');
        $query = Vehicle::where('Vehicle.customerId', $authUser->customerId)
                        ->with('groups', 'unit.subscription', 'pendingCommands', 'kenyaMeta');

        if ($authUser->permittedGroups()->exists()) {
            $groupIds = $authUser->permittedGroups->pluck('groupId')->toArray();
            $query->whereHas('groups', function ($query) use ($groupIds) {
                return $query->whereIn('VehicleGroup.groupId', $groupIds);
            });
        }

        if ($this->request->has('search') && $this->request->get('search')) {
            $query->where(function ($q) {
                $q->where('alias', 'like', '%' . $this->request->get('search') . '%')
                  ->orWhere('registration', 'like', '%' . $this->request->get('search') . '%');
            });
        }

        $limit = $this->request->get('limit');
        if (is_null($limit)) {
            $vehicles = $query->paginate(Config::get('app.paginateDefault'));
        } else {
            $limit = intval($limit);
            if ($limit === 0) {
                $vehicles = $query->get();
            } else {
                $vehicles = $query->paginate($this->request->get('limit'));
            }
        }

        if ($vehicles) {
            foreach ($vehicles as &$vehicle) {
                if ($vehicle->unitId) {
                    $catType = CatType::getCatType($vehicle, $vehicle->customer->brand);
                    $vehicle->catType = $catType;
                }
            }
        }


        return $this->transformCollection($vehicles, [
            'groups',
            'latest_position',
            'unit.subscription',
            'pending_commands',
            'kenya_meta',
            'cat_type'
        ], 'vehicles')->respond();
    }

    /**
     * @return JsonResponse
     */
    public function pollAction(): JsonResponse
    {
        $timestamp = $this->request->get('last_poll');
        if (is_null($timestamp)) {
            return $this->respondWithInvalidRequest('last_poll is required');
        }

        $vehicles = Vehicle::withoutGlobalScope('latestPosition')
                           ->afterTimeStampPositions(
                               $this->request->get('user')->customerId,
                               $this->request->get('last_poll'))
                           ->with('groups', 'unit')
                           ->get();

        return $this->transformCollection($vehicles,
                                          ['groups', 'latest_position', 'unit.subscription', 'pending_commands'], 'vehicles')
                    ->respond();
    }

    public function getKey(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
            ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
            ->with('unit')
            ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        if (!$vehicle->unitId) {
            return $this->respondWithInvalidRequest('A vehicle must have a unit before creating a key');
        }

        $key = SoftTag::where('unitId', $vehicle->unitId)->first();


        $pendingCommands = PendingCommand::where('vehicleId', $vehicleId)
            ->where('status', 'pending')
            ->where('commandValue', 'like', 'SETPHNKEY%')
            ->first();

        $this->appendBody('pending', (bool)($pendingCommands));

        $this->appendBody('key', $key);
        return $this->respond();
    }

    /**
     * Convert $endian hex string to specified $format
     *
     * @param string $endian Endian HEX string
     * @param string $format Endian format: 'N' - little endian, 'V' - big endian
     *
     * @return string
     */
    private function formatEndian($endian, $format = 'N') {
        $endian = intval($endian, 16);      // convert string to hex
        $endian = pack('L', $endian);       // pack hex to binary sting (unsinged long, machine byte order)
        $endian = unpack($format, $endian); // convert binary sting to specified endian format

        return sprintf("%'.08x", $endian[1]); // return endian as a hex string (with padding zero)
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function generateKey(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
            ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
            ->with('unit')
            ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        if (!$vehicle->unitId) {
            return $this->respondWithInvalidRequest('A vehicle must have a unit before creating a key');
        }

        $key = SoftTag::where('unitId', $vehicle->unitId)->first();

        if (!$key) {
            $unitCheck = new Unit();
            $ready = $unitCheck->checkSoftFobReadiness($vehicle->unitId);

            if ($ready !== true) {
                return $this->respondWithError(422, $ready);
            }

            $key = new SoftTag();
            $key->unitId = $vehicle->unitId;
            $key->tagKey = strtoupper($this->formatEndian(dechex(random_int(1, 4294967295)), 'N'));
            $key->created = Carbon::now('UTC')->format('Y-m-d H:i:s');
            try {
                $key->saveOrFail();
                $pendingCommand = new PendingCommand();

                //Default of dechec is big endian, so convert it
                $pendingCommand->storePendingCommand(
                    $vehicleId,
                    PendingCommand::RAW,
                    'SETPHNKEY ' . $key->tagKey
                );
            } catch (\Exception $ex) {
                return $this->respondWithError(500, "Failed to create soft key");
            }
        }


        if ($this->request->has('phoneId')) {
            $foundPhone = PhoneID::where('phoneId', $this->request->get('phoneId'))->first();
            if (!$foundPhone) {
                $phoneId = new PhoneID();
                $phoneId->unitId = $vehicle->unitId;
                $phoneId->userId = $this->request->get('user')->id;
                $phoneId->phoneId = str_pad(strtoupper(dechex($this->request->get('phoneId'))), 8, "0", STR_PAD_LEFT);
                $phoneId->save();
            }
        }

        $this->appendBody('key', $key);
        return $this->respond();
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function removeKey(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
            ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
            ->with('unit')
            ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        if (!$vehicle->unitId) {
            return $this->respondWithInvalidRequest('A vehicle must have a unit before creating a key');
        }

        /**
         * @var SoftTag $key
         */
        $key = SoftTag::where('unitId', $vehicle->unitId)->first();

        if ($key) {
            try {
                $key->delete();
                $pendingCommand = new PendingCommand();
                //Default of dechec is big endian, so convert it
                $this->formatEndian(dechex($key->tagKey), 'N');
                $pendingCommand->storePendingCommand(
                    $vehicleId,
                    PendingCommand::RAW,
                    'SETPHNKEY 00000000'
                );
            } catch (\Exception $ex) {
                return $this->respondWithError(500, "Failed to create soft key");
            }
        }

        $this->appendBody('key', $key);
        return $this->respond();
    }

    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function showAction(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
                          ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
                          ->with('groups', 'latestJourney', 'unit', 'kenyaMeta')
                          ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        } else {
            if ($vehicle->unitId) {
                $catType = CatType::getCatType($vehicle, $vehicle->customer->brand);
                $vehicle->catType = $catType;
            }
        }

        return $this->transformItem($vehicle,
                                    ['groups', 'latest_journey', 'latest_position', 'unit.subscription', 'pending_commands', 'kenya_meta', 'cat_type'])
                    ->respond();
    }


    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function updateAction(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
                          ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
                          ->with('groups', 'latestJourney', 'unit')
                          ->first();

        // Admin doesn't require customer access
        if ($this->isAdmin()) {
            $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
                ->with('groups', 'latestJourney', 'unit')
                ->first();
        }

        /**
         * @var Vehicle $vehicle
         */

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found.');
        }

        if ($this->request->has('odometer')) {
            $vehicle->odometer = $this->request->get('odometer');
        }

        if ($this->request->has('alias')) {
            $vehicle->alias = $this->request->get('alias');
        }

        $userId = $this->request->get('user')->id;
        $ip = $this->request->ip();

        if ($this->request->has('privacy_mode_enabled')) {
            $newMode = $this->request->get('privacy_mode_enabled');

            $note = new VehicleNote();
            $note->modeChangeCheck(
                'privacyModeEnabled',
                $vehicle->privacyModeEnabled,
                $newMode,
                $userId,
                $vehicle->vehicleId,
                $vehicle->customerId,
                $this->isAdmin(),
            );


            $vehicle->privacyModeEnabled = $newMode;
        }

        if ($this->request->has('zero_speed_mode_enabled')) {
            $newMode = $this->request->get('zero_speed_mode_enabled');

            $note = new VehicleNote();
            $note->modeChangeCheck(
                'zeroSpeedModeEnabled',
                $vehicle->zeroSpeedModeEnabled,
                $newMode,
                $userId,
                $vehicle->vehicleId,
                $vehicle->customerId,
                $this->isAdmin(),
            );

            $vehicle->zeroSpeedModeEnabled = $newMode;
        }

        if ($this->request->has('battery_type')) {
            $batteryType = $this->request->get('battery_type');
            $type = '';
            $value = '';
            switch (strtoupper($batteryType)) {
                case BatteryType::BATTERY_TYPE_12VLA_S;
                    $value = BatteryType::BATTERY_TYPE_12VLA;
                    $type = BatteryType::BATTERY_TYPE_12VLA_S;
                    break;
                case BatteryType::BATTERY_TYPE_12VLI_S;
                    $value = BatteryType::BATTERY_TYPE_12VLI;
                    $type = BatteryType::BATTERY_TYPE_12VLI_S;
                    break;
                case BatteryType::BATTERY_TYPE_6VLA_S;
                    $value = BatteryType::BATTERY_TYPE_6VLA;
                    $type = BatteryType::BATTERY_TYPE_6VLA_S;
                    break;
                default:
                    return $this->respondWithInvalidRequest("Battery Type not found, must be 12VLA, 12VLI, 6VLA");
            }

            $vehicle->batteryType = $type;

            $pendingCommand = new PendingCommand();
            $pendingCommand->storePendingCommand(
                $vehicleId,
                PendingCommand::FMSCONFIG,
                BatteryType::BATTERY_TYPE_COMMAND . $value,
                $this->request->get('user')->id
            );
        }


        $vehicle->save();

        return $this->transformItem($vehicle,
                                    ['groups', 'latest_journey', 'latest_position', 'unit.subscription', 'pending_commands'])
                    ->respond();
    }

    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function paginateJourneysAction(int $vehicleId): JsonResponse
    {
        $vehicleJourneys = VehicleJourney::where('VehicleJourney.vehicleId', $vehicleId)
                                         ->where('customerId', $this->request->get('user')->customerId);

        if ($this->request->get('user')->customer->usesSeatModule) {
            $vehicleJourneys = $vehicleJourneys->withSeatFares();
        }

        if ($this->request->has('from') && $this->request->has('to')) {
            try {
                $from = Carbon::createFromTimestamp($this->request->get('from'))->toDateTimeString();
                $to = Carbon::createFromTimestamp($this->request->get('to'))->toDateTimeString();
            } catch (\Exception $ex) {
                return $this->respondWithInvalidRequest("From and To must be valid unix timestamps");
            }

            $vehicleJourneys = $vehicleJourneys->whereBetween('VehicleJourney.startTime', [$from, $to])
                                               ->whereBetween('VehicleJourney.endTime', [$from, $to]);
        }

        $vehicleJourneys = $vehicleJourneys->orderBy('VehicleJourney.endTime', 'DESC')
                                           ->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($vehicleJourneys, null, 'vehicle_journeys')
                    ->respond();
    }

    /**
     * @param int $vehicleId
     * @param int $start
     * @param int $end
     *
     * @return JsonResponse
     */
    public function showJourneyAction(int $vehicleId, int $start, int $end): JsonResponse
    {
        $start = Carbon::createFromTimestamp($start);
        $end = Carbon::createFromTimestamp($end);

        $vehicleJourney = VehicleJourney::where('vehicleId', $vehicleId)
                                        ->where('customerId', $this->request->get('user')->customerId)
                                        ->where('startTime', $start)
                                        ->where('endTime', $end)
                                        ->first();

        if (is_null($vehicleJourney)) {
            return $this->respondWithNotFound('Vehicle Journey not found');
        }

        $vehicleJourney->positions = VehiclePosition::where("vehicleId", $vehicleJourney->vehicleId)
                                                    ->whereBetween("timestamp", [$start, $end])->get();

        return $this->setTransformer(new VehicleJourneyTransformer(true, true))
                    ->transformItem($vehicleJourney, ['positions', 'vehicle'], null, true)
                    ->respond();
    }

    /**
     * @param int $vehicleId
     * @param int $time
     *
     * @return JsonResponse
     */
    public function showPositionAction(int $vehicleId, int $time): JsonResponse
    {
        $time = Carbon::createFromTimestamp($time);
        $vehiclePosition = VehiclePosition::where('vehicleId', $vehicleId)
                                          ->where('timestamp', $time)
                                          ->first();

        if (!$vehiclePosition) {
            return $this->respondWithNotFound('Vehicle Position not found');
        }

        return $this->transformItem($vehiclePosition)
                    ->respond();
    }

    /**
     * @param int $vehicleId
     * @param int $startTime
     * @param int $endTime
     *
     * @return JsonResponse
     */
    public function showPositionsAction(int $vehicleId, int $startTime, int $endTime): JsonResponse
    {
        $start = Carbon::createFromTimestamp($startTime);
        $end = Carbon::createFromTimestamp($endTime);
        $startFromEnd = Carbon::createFromTimestamp($endTime)->subHours(24);

        if ($start < $startFromEnd || $start > $end || $end < $start) {
            return $this->respondWithNotFound('Start must be <= than End, End must be >= to Start, Start and End must be within 24 hours');
        }

        $vehiclePositions = VehiclePosition::where('vehicleId', $vehicleId)
                                           ->whereBetween('timestamp', [
                                               $start->toDateTimeString(),
                                               $end->toDateTimeString(),
                                           ])
                                           ->get();

        if (!$vehiclePositions) {
            return $this->respondWithNotFound('Vehicle Position not found');
        }

        return $this->transformCollection($vehiclePositions,
                                          [], 'vehicle_id')
                    ->respond();
    }


    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function updateGsenseAction(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)
                          ->where('Vehicle.customerId', $this->request->get('user')->customerId)
                          ->with('groups', 'latestJourney', 'unit')
                          ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        $vehicle->fnl = $this->request->get('g_sense');
        $vehicle->fnlNum1 = $this->request->get('g_sense_number');
        $vehicle->save();

        return $this->transformItem($vehicle, ['groups', 'latest_journey', 'latest_position', 'unit.subscription'])
                    ->respond();
    }


    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function enableNoAlertsAction(int $vehicleId): JsonResponse
    {

        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)
            ->where('Vehicle.customerId', $this->request->get('user')->customerId)
            ->with('groups', 'latestJourney', 'unit')
            ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        $originalVehicle = clone $vehicle;
        if ($this->request->has('start') && $this->request->has('end')) {
            try {
                $dateBegin = Carbon::createFromTimestamp(
                    $this->request->get('start'), 'UTC'
                );

                $dateEnd = Carbon::createFromTimestamp(
                    $this->request->get('end'), 'UTC'
                );

                if ($dateEnd->lessThan($dateBegin)) {
                    //Trying to set the end to be before the start
                    return $this->respondWithInvalidRequest('Mode start must be before mode end');
                }

                $vehicle->noAlertStart = $dateBegin->toDateTimeString();
                $vehicle->noAlertEnd = $dateEnd->toDateTimeString();

            } catch (\Exception $exception) {
                return $this->respondWithInvalidRequest($exception->getMessage());
            }
        } else {
            return $this->respondWithInvalidRequest('Start and end date must be specified');
        }



        $vehicleModel = new Vehicle();
        try {
            $vehicleModel->sendEWMModeChanges($originalVehicle, $vehicle);
        }catch (\Exception $ex) {
            return $this->respondWithError(500, 'Failed to queue command for EWM. Please contact support');
        }
        $vehicle->save();

        $note = new VehicleNote();
        $note->vehicleId = $vehicleId;
        $note->userId = $this->request->get('user')->userId;
        $note->noteType = VehicleNote::TYPE_CUSTOMER;
        $note->note = 'No Alert mode enabled from ' . $vehicle->noAlertStart . ' until ' . $vehicle->noAlertEnd . ' [Generated by Fleet App]';
        $note->visibility = VehicleNote::VISIBILITY_PRIVATE;
        $note->timestamp = Carbon::now();
        $note->deleted = 0;
        $note->read = 0;
        $note->save();

        return $this->transformItem($vehicle,
            ['groups', 'latest_journey', 'latest_position', 'unit.subscription', 'pending_commands'])
            ->respond();

    }

    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function disableNoAlertsAction(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)
            ->where('Vehicle.customerId', $this->request->get('user')->customerId)
            ->with('groups', 'latestJourney', 'unit')
            ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        $originalVehicle = clone $vehicle;

        $vehicle->noAlertStart = null;
        $vehicle->noAlertEnd = null;

        $vehicleModel = new Vehicle();
        try {
            $vehicleModel->sendEWMModeChanges($originalVehicle, $vehicle);
        }catch (\Exception $ex) {
            return $this->respondWithError(500, 'Failed to queue command for EWM. Please contact support');
        }
        $vehicle->save();

        $note = new VehicleNote();
        $note->vehicleId = $vehicleId;
        $note->userId = $this->request->get('user')->userId;
        $note->noteType = VehicleNote::TYPE_CUSTOMER;
        $note->note = 'No Alert mode disabled [Generated by Fleet App]';
        $note->visibility = VehicleNote::VISIBILITY_PRIVATE;
        $note->timestamp = Carbon::now();
        $note->deleted = 0;
        $note->read = 0;
        $note->save();


        return $this->transformItem($vehicle,
            ['groups', 'latest_journey', 'latest_position', 'unit.subscription', 'pending_commands'])
            ->respond();
    }

    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function updateModeAction(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)
                          ->where('Vehicle.customerId', $this->request->get('user')->customerId)
                          ->with('groups', 'latestJourney', 'unit')
                          ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        $modes = [
            'garage'    => 'Garage mode',
            'transport' => 'Transport mode',
        ];


        if ($this->request->has('disable')) {
            foreach ($modes as $mode => $modeDescription) {
                $vehicle->{$mode . 'ModeBegin'} = null;
                $vehicle->{$mode . 'ModeEnd'} = null;
            }
        } else {
            foreach ($modes as $mode => $modeDescription) {
                if ($this->request->has($mode . '_mode_begin') && $this->request->has($mode . '_mode_end')) {
                    try {
                        $dateBegin = Carbon::createFromTimestamp(
                            $this->request->get($mode . '_mode_begin')
                        );

                        $dateEnd = Carbon::createFromTimestamp(
                            $this->request->get($mode . '_mode_end')
                        );

                        if ($dateEnd->lessThan($dateBegin)) {
                            //Trying to set the end to be before the start
                            return $this->respondWithInvalidRequest('Mode start must be before mode end');
                        }


                        $vehicle->{$mode . 'ModeBegin'} = $dateBegin->toDateTimeString();

                        $vehicle->{$mode . 'ModeEnd'} = $dateEnd->toDateTimeString();

                    } catch (\Exception $exception) {
                        return $this->respondWithInvalidRequest($exception->getMessage());
                    }
                }

                if ($this->request->has('clear_' . $mode . '_mode')) {
                    $vehicle->{$mode . 'ModeBegin'} = null;
                    $vehicle->{$mode . 'ModeEnd'} = null;
                }
            }
        }

        switch (true) {
            case $this->request->has('disable'):
                $noteMessage = 'All modes have been disabled';
                break;

            case $this->request->has('garage_mode_begin') && $this->request->has('transport_mode_begin'):
                $noteMessage = 'Garage and Transport Modes activated from' . $vehicle->garageModeBegin . ' until ' . $vehicle->garageModeEnd;
                break;

            case $this->request->has('garage_mode_begin'):
                $noteMessage = 'Garage Mode activated from' . $vehicle->garageModeBegin . ' until ' . $vehicle->garageModeEnd;
                break;

            case $this->request->has('transport_mode_begin'):
                $noteMessage = 'Transport Mode activated from ' . $vehicle->transportModeBegin . ' until ' . $vehicle->transportModeEnd;
                break;

            case $this->request->has('clear_transport_mode'):
                $noteMessage = 'Transport Mode cleared at ' . Carbon::now()->toDateTimeString();
                break;

            case $this->request->has('clear_garage_mode'):
                $noteMessage = 'Garage Mode cleared at ' . Carbon::now()->toDateTimeString();
                break;
        }

        $vehicle->save();

        $note = new VehicleNote();
        $note->vehicleId = $vehicleId;
        $note->userId = $this->request->get('user')->userId;
        $note->noteType = VehicleNote::TYPE_CUSTOMER;
        $note->note = $noteMessage . " [Generated by Fleet App]";
        $note->visibility = VehicleNote::VISIBILITY_PRIVATE;
        $note->timestamp = Carbon::now();
        $note->deleted = 0;
        $note->read = 0;
        $note->save();

        return $this->transformItem($vehicle,
                                    ['groups', 'latest_journey', 'latest_position', 'unit.subscription', 'pending_commands'])
                    ->respond();
    }

    public function lastSeen($unitId) {
        $unit = Unit::where("Unit.unitId", $unitId)->first();
        $tagsList = VtsTag::select(['VTSTag.vtsId as vtsTagId'])->where("UnitToVTSTag.unitId", $unitId)
            ->join('UnitToVTSTag', 'UnitToVTSTag.vtsId', '=', 'VTSTag.vtsId')
            ->get();
        if (!$unit) {
            return $this->respondWithNotFound();
        }

        $this->appendBody('lastCheckedIn' , $unit->lastCheckedIn);
        foreach ($tagsList as $index => $tag ) {
            $this->appendBody('tag_' . $index , $tag->vtsTagId);
        }

        return $this->respond();
    }

    public function installStatus($unitId) {
        $unit = Unit::where("Unit.unitId", $unitId)->first();
        if (!$unit) {
            return $this->respondWithNotFound();
        }

        if (
        $this->request->has("unitId") &&
        $this->request->has("voltage") &&
        $this->request->has("ignition") &&
        $this->request->has("gpsFix") &&
        $this->request->has("gpsSource") &&
        $this->request->has("modemState") &&
        $this->request->has("satellites") &&
        $this->request->has("hDop") &&
        $this->request->has("fob1") &&
        $this->request->has("fob2") &&
        $this->request->has("complete")
        ) {
            $id = $this->request->get("unitId");
            $voltage = $this->request->get("voltage");
            $ignition = $this->request->get("ignition");
            $gpsFix = $this->request->get("gpsFix");
            $gpsSource = $this->request->get("gpsSource");
            $modemState = $this->request->get("modemState");
            $satellites = $this->request->get("satellites");
            $hDop = $this->request->get("hDop");
            $fob1 = $this->request->get("fob1");
            $fob2 = $this->request->get("fob2");
            $complete = $this->request->get("complete");


            UnitInstallStatus::where("unitId", $id)->where('complete', $complete)->delete();

            //Insert new record
            $installStatus = new UnitInstallStatus();
            $installStatus->unitId = $id;
            $installStatus->complete = $complete;
            $installStatus->voltage = $voltage;
            $installStatus->ignition = $ignition;
            $installStatus->gpsFix = $gpsFix;
            $installStatus->gpsSource = $gpsSource;
            $installStatus->modemState = $modemState;
            $installStatus->satellites = $satellites;
            $installStatus->hDop = $hDop;
            $installStatus->fob1 = $fob1;
            $installStatus->fob2 = $fob2;
            $installStatus->timestamp = Carbon::now('UTC');

            if ($installStatus->fob1 === "MISSING" || $installStatus->fob2 === "MISSING") {
                $installStatus->complete = false;
            }


            $installStatus->save();


            return $this->respond();

        } else {
            return $this->respondWithInvalidRequest();
        }
    }

    /**
     * @return string
     */
    public function generateMarkerIconAction(): string
    {
        $bearing = floatval($this->request->get('bearing'));
        $bearing = (int)$bearing;
        $arrow = new \Imagick(base_path(sprintf(
                                            'resources/assets/img/speed-arrow-resized.png',
                                            $bearing
                                        )));
        $bearing = $bearing % 360;
        $arrow->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $arrow->setImageMatte(true);
        $arrow->distortImage(\Imagick::DISTORTION_SCALEROTATETRANSLATE, [$bearing], false);
        $arrow->setImageFormat('png');

        $draw = new \ImagickDraw();
        $draw->setFont(base_path('resources/assets/fonts/UbuntuMono-Bold.ttf'));
        $speed = floatval($this->request->get('speed'));
        $draw->setFontSize(($speed >= 100) ? 10 : 12);
        $draw->setGravity(\Imagick::GRAVITY_CENTER);
        $draw->setFillColor('#111');

        $arrow->annotateImage($draw, 0.5, 0.25, 0, (string)$speed);

        $arrow->setImageFormat('png');
        header("Content-Type: image/png");

        return $arrow->getImageBlob();
    }

    /**
     * @param $id
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function setDriverAction($id)
    {
        $authUser = $this->request->get('user');

        $vehicle = Vehicle::where('Vehicle.customerId', $authUser->customerId)
                          ->findOrFail($id);

        $driver = Driver::findOrFail($this->request->get('driver_id'));

        $vehicle->defaultDriver = $driver->driverId;
        $vehicle->save();

        return $this->setTransformer(new DriverTransformer)
                    ->transformItem($driver)
                    ->respond();
    }

    public function getVehiclesForCustomerAction($customerId, $brand)
    {
        if ($brand !== '') {
            $customer = Customer::where('customerId', $customerId)->where('brand', $brand)->first();

        } else {
            $customer = Customer::where('customerId', $customerId)->first();
        }
        if ($customer === null) {
            return $this->respondWithNotFound('Customer not on brand');
        }

        $vehicles = Vehicle::where('Vehicle.customerId', $customerId)->get();

        if (!$vehicles) {
            return $this->respondWithNotFound();
        }

        return $this->transformCollection($vehicles, null, 'vehicles')->respond();
    }

    public function getVehicleForCustomerAction($customerId, $vehicleId, $brand)
    {
        if ($brand !== '') {
            $customer = Customer::where('customerId', $customerId)->where('brand', $brand)->first();

        } else {
            $customer = Customer::where('customerId', $customerId)->first();
        }
        if ($customer === null) {
            return $this->respond();
        }

        $vehicle = Vehicle::where('Vehicle.customerId', $customerId)->where('Vehicle.vehicleId', $vehicleId)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound();
        }
        return $this->transformItem($vehicle, null, 'vehicle')->respond();
    }

    public function createVehicleAction()
    {
        if ($this->request->has('customerId') &&
            $this->request->has('unitId') &&
            $this->request->has('registration') &&
            $this->request->has('make') &&
            $this->request->has('model') &&
            $this->request->has('vin') &&
            $this->request->has('type') &&
            $this->request->has('color')) {

            $unitId = $this->request->get('unitId');
            $brand = $this->getBrand();

            //check the unit is valid and belongs to correct brand if brand set
            $unitId = getInternalUnitID($unitId);

            $unit = Unit::where("unitId", $unitId)->where("brand", $brand)->first();

            if ($unit === null) {
                return $this->respondWithForbidden("Unit doesn't exist or isn't owned by " . $brand);
            }
            //check if already assigned
            if ($unit) {
                $vehicle = Vehicle::where('unitId', $unitId)->first();
                if ($vehicle !== null) {
                    return $this->respondWithForbidden("Unit already assigned to vehicle: " . $vehicle->vehicleId);
                }
            }

            $vehicle = new Vehicle();
            $vehicle->unitId = $unitId;
            $vehicle->customerId = $this->request->get('customerId');
            $vehicle->registration = $this->request->get('registration');
            $vehicle->make = $this->request->get('make');
            $vehicle->model = $this->request->get('model');
            $vehicle->vin = $this->request->get('vin');
            $vehicle->colour = $this->request->get('color');


            $type = strtoupper($this->request->get('type'));
            $driverRec = "2,";
            switch ($type) {
                case 'S7':
                case 'S5';
                    $driverRec = $driverRec . "0000";
                    break;
                case 'S5+';
                    $driverRec = $driverRec . "0020";
                    break;
                default:
                    return $this->respondWithForbidden("type must be S7, S5 or S5+");
            }

            $vehicle->save();

            $pendingCommand = new PendingCommand();

            $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_1, '0000', $this->request->get('user')->id);
            $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC, $driverRec, $this->request->get('user')->id);

            return $this->transformItem($vehicle)->respond();
        } else {
            return $this->respondWithInvalidRequest("Must have customerId, unitId, registration, make, model, color, type and vin.
             Optional fields are description, dealershipId, fitterId and alias");
        }
    }

    public function getCoupons($vehicleId)
    {
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)
            ->where('Vehicle.customerId', $this->request->get('user')->customerId)
            ->with('groups', 'latestJourney', 'unit')
            ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        $coupon = new Coupon();
        $couponFound = $coupon->checkForDiscountCode($vehicle->unitId);

        $this->appendBody('coupon', $couponFound);
        return $this->respond();

    }

    public function updateVehicleAction($vehicleId)
    {
        $brand = $this->getBrand();
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound();
        }
        $customer = Customer::where('customerId', $vehicle->customerId)->where('brand', $brand)->first();
        if ($customer === null) {
            return $this->respondWithForbidden("vehicle not found");
        }

        if ($this->request->has('customerId')) {
            $vehicle->customerId = $this->request->get('customerId');
        }
        if ($this->request->has('registration')) {
            $vehicle->registration = $this->request->get('registration');
        }
        if ($this->request->has('make')) {
            $vehicle->make = $this->request->get('make');
        }
        if ($this->request->has('model')) {
            $vehicle->model = $this->request->get('model');
        }
        if ($this->request->has('vin')) {
            $vehicle->vin = $this->request->get('vin');
        }
        if ($this->request->has('color')) {
            $vehicle->colour = $this->request->get('color');
        }

        if ($this->request->has('type')) {
            $vehicle->type = $this->request->get('type');
        }
        $vehicle->save();
        return $this->transformItem($vehicle)->respond();
    }

    public function deleteVehicleAction($vehicleId)
    {
        $brand = $this->getBrand();
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound();
        }
        $customer = Customer::where('customerId', $vehicle->customerId)->where('brand', $brand)->first();
        if ($customer === null) {
            return $this->respondWithForbidden("vehicle not found");
        }
        //send restock unit commands!
        $vehicle->delete();
    }

    public function restockUnitAction($unitId)
    {
        $brand = $this->getBrand();
        $vehicle = Vehicle::where('Vehicle.unitId', $unitId)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound();
        }
        $customer = Customer::where('customerId', $vehicle->customerId)->where('brand', $brand)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound('Unit not found on vehicle');
        }

        AssignedUnitsDealership::where('unitId', $unitId)->delete();

        AssignedUnits::where('unitId', $unitId)->delete();

        Vehicle::where('unitId', $unitId)->update(['unitId' => 0]);

        Unit::where('unitId', $unitId)->update(['stock' => 1]);

        return $this->respond();
    }

    //5 years
    public function activateVehicleAction($vehicleId)
    {
        $brand = $this->getBrand();
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound();
        }
        $customer = Customer::where('customerId', $vehicle->customerId)->where('brand', $brand)->first();
        if (!$customer) {
            return $this->respondWithNotFound();
        }

        $monitored = null;
        if ($this->request->has('monitored')) {
            $monitored = $this->request->get('monitored');
        }

        //Update as new parameter
        $subscription = Subscription::where('Subscription.unitId', $vehicle->unitId)->first();

        $start = Carbon::now();


        if ($this->request->has('length') && is_numeric($this->request->get('length'))) {
            $subLength = (int)$this->request->get('length');
        } else {
            $subLength = 60;
        }

        $end = $start->copy()->addMonths($subLength)->format('Y-m-d');
        $start = $start->format('Y-m-d');

        if ($subscription) {
            $subscription->update([
              'subStart' => $start,
              'subEnd'   => $end,
              'length'   => $subLength,
                'monitored' => ($monitored) ?? (($brand === 'rewire') ? 1 : 0),
              ]);
        } else {
            //No subscription so create it
            $subscription = new Subscription();
            $subscription->unitId = $vehicle->unitId;
            $subscription->customerId = $customer->customerId;
            $subscription->length = $subLength;
            $subscription->subStart = $start;
            $subscription->subEnd = $end;
            $subscription->monitored = ($monitored) ?? (($brand === 'rewire') ? 1 : 0);
            $subscription->save();
        }

        return $this->transformItem($subscription)->respond();
    }

    public function deactivateVehicleAction($vehicleId)
    {
        $brand = $this->getBrand();
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound();
        }
        $customer = Customer::where('customerId', $vehicle->customerId)->where('brand', $brand)->first();
        if (!$customer) {
            return $this->respondWithNotFound();
        }

        //Update as new parameter
        $subscription = Subscription::where('Subscription.unitId', $vehicle->unitId)->first();

        if (!$subscription) {
            return $this->respondWithNotFound('Subscription not found');
        }

        $subscription->delete();
        return $this->respond();
    }

    public function isActiveVehicleAction($vehicleId)
    {
        $brand = $this->getBrand();
        $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)->first();
        if (!$vehicle) {
            return $this->respondWithNotFound();
        }
        $customer = Customer::where('customerId', $vehicle->customerId)->where('brand', $brand)->first();
        if (!$customer) {
            return $this->respondWithNotFound();
        }

        //Update as new parameter
        $subscription = Subscription::where('Subscription.unitId', $vehicle->unitId)->first();

        $active = false;
        if ($subscription) {
            $active = true;
        }

        $this->appendBody('active', $active);
        return $this->respond();
    }

    public function incidentsAction($vehicleId)
    {
        if ($this->request->has('from') && $this->request->has('to')) {
            $from = $this->request->get('from');
            $to = $this->request->get('to');
        } else {
            return $this->respondWithInvalidRequest("Must have from and to timestamps");
        }

        $incidents = IncidentEvent::where("vehicleId", $vehicleId)->whereIn('alertType', VehicleController::INCIDENT_TYPES)->whereBetween('timestamp', [$from, $to])->get();

        if (count($incidents) < 1) {
            return $this->respond();
        }
        return $this->transformCollection($incidents)->respond();
    }
}
