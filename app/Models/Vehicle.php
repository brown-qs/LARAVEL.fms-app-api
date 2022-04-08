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

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * Class Customer
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Vehicle extends Model
{
    public const POWER_MODE_INTERNAL = "INTERNAL";
    public const POWER_MODE_EXTERNAL = "EXTERNAL";
    public const POWER_MODE_IGNITION = "IGNITION";
    public const POWER_MODE_VONLY    = "VONLY";

    public const TYPE_CAR   = "Car";
    public const TYPE_BIKE  = "Bike";
    public const TYPE_VAN   = "Van";
    public const TYPE_HGV   = "HGV";
    public const TYPE_OTHER = "Other";

    public const STATE_UNSET = "UNSET";
    public const STATE_SET   = "SET";
    public const STATE_ALT   = "ALT";
    public const STATE_ALM   = "ALM";
    public const STATE_UNSUB = "UNSUB";
    public const STATE_INST  = "INST";
    public const STATE_SLP   = "SLP";

    public const STATE_UNSET_ID = 1;
    public const STATE_SET_ID   = 2;
    public const STATE_ALT_ID   = 3;
    public const STATE_ALM_ID   = 4;
    public const STATE_UNSUB_ID = 5;
    public const STATE_INST_ID  = 6;
    public const STATE_SLP_ID   = 7;

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Vehicle';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'vehicleId';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'timestamp',
        'installed',
        'geoFenceExceptionUntil',
        'geoFenceIgnUntil',
        'lastOdo',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'customerId'           => 'integer',
        'unitId'               => 'integer',
        'dealershipId'         => 'integer',
        'fitterId'             => 'integer',
        'privacyModeEnabled'   => 'boolean',
        'zeroSpeedModeEnabled' => 'boolean',
    ];

    /**
     * @var bool
     */
    private $vehiclePositionCreated = false;

    /**
     * @var null|VehiclePosition
     */
    private $vehiclePositionCache = null;

    protected static function boot()
    {
        static::addGlobalScope('latestPosition', function (Builder $builder) {
            $builder->addSelect(DB::raw('Vehicle.*'))
                    ->addSelect(DB::raw('VehiclePosition.timestamp as lastEventDateTime, VehiclePosition.*'))
                    ->addSelect(DB::raw('Vehicle.vehicleId as vehicleId'))
                    ->addSelect(DB::raw('Vehicle.customerId as customerId'))
                    ->addSelect(DB::raw('Vehicle.timestamp as timestamp'))
                    ->addSelect(DB::raw("VehiclePositionAux.auxDataFloat AS seatsOccupied"))
                    ->leftJoin("VehiclePosition", function ($join) {
                        $join->on("VehiclePosition.timestamp", "=", "Vehicle.positionTimestamp")
                             ->on("VehiclePosition.vehicleId", "=", "Vehicle.vehicleId");
                    })->leftJoin("VehiclePositionAux", function ($join) {
                    $join->on("VehiclePositionAux.vehicleId", "=", "Vehicle.vehicleId")
                         ->on("VehiclePositionAux.timestamp", "=", "VehiclePosition.timestamp");
                });
        });

        parent::boot();
    }

    public function isEwmOn($status)
    {
        $CUSTSMS_ALT_EMW = 16;

        if (is_null($status)) {
            return false;
        }

        return (bool)(((int)hexdec(substr($status, 0, 4)) & $CUSTSMS_ALT_EMW) == $CUSTSMS_ALT_EMW);
    }

    /**
     * @param Builder $query
     * @param int     $customerId
     * @param int     $timestamp
     *
     * @return Builder
     */
    public function scopeAfterTimestampPositions($query, $customerId, $timestamp)
    {
        return $query->addSelect(DB::raw('Vehicle.*'))
                     ->addSelect(DB::raw('Vehicle.vehicleId as vehicleId'))
                     ->addSelect(DB::raw('Vehicle.customerId as customerId'))
                     ->addSelect(DB::raw('Vehicle.timestamp as timestamp'))
                     ->addSelect(DB::raw('VehiclePosition.timestamp as lastEventDateTime, VehiclePosition.*'))
                     ->addSelect(DB::raw("VehiclePositionAux.auxDataFloat AS seatsOccupied"))
                     ->leftJoin("VehiclePosition", function ($join) {
                         $join->on("VehiclePosition.timestamp", "=", "Vehicle.positionTimestamp")
                              ->on("VehiclePosition.vehicleId", "=", "Vehicle.vehicleId");
                     })->leftJoin("VehiclePositionAux", function ($join) {
                $join->on("VehiclePositionAux.vehicleId", "=", "Vehicle.vehicleId")
                     ->on("VehiclePositionAux.timestamp", "=", "VehiclePosition.timestamp");
            })->where('Vehicle.customerId', $customerId);
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }

    /**
     * @return HasOne
     */
    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'unitId', 'unitId');
    }

    /**
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(VehicleGroup::class,
            'AssignedVehicleGroup',
            'vehicleId',
            'groupId'
        );
    }

    /**
     * @return HasMany
     */
    public function journeys(): HasMany
    {
        return $this->hasMany(VehicleJourney::class, 'vehicleId', 'vehicleId');
    }

    /**
     * @return HasMany
     */
    public function pendingCommands(): HasMany
    {
        return $this->hasMany(PendingCommand::class, 'vehicleId', 'vehicleId')->pending();
    }

    /**
     * @return HasOne
     */
    public function latestJourney(): HasOne
    {
        return $this->hasOne(VehicleJourney::class, 'vehicleId', 'vehicleId')->Onlylatest();
    }

    /**
     * @return HasOne
     */
    public function kenyaMeta(): HasOne
    {
        return $this->hasOne(KenyaMeta::class, 'unitId', 'unitId');
    }

    /**
     * @return VehiclePosition|null
     */
    public function getLatestPositionAttribute()
    {
        if (!$this->vehiclePositionCreated) {
            if (is_null($this->lat) || is_null($this->lng)) {
                $this->vehiclePositionCache = null;
            } else {
                $this->vehiclePositionCache = new VehiclePosition([
                    'vehicleId'     => $this->vehicleId,
                    'customerId'    => $this->customerId,
                    'timestamp'     => $this->lastEventDateTime,
                    'driverId'      => $this->driverId,
                    'healthCheckId' => $this->healthCheckId,
                    'state'         => $this->state,
                    'gpsType'       => $this->gpsType,
                    'gpsSatellites' => $this->gpsSatellites,
                    'lat'           => $this->lat,
                    'lng'           => $this->lng,
                    'accuracy'      => $this->accuracy,
                    'speed'         => $this->speed,
                    'ignition'      => $this->ignition,
                    'engine'        => $this->engine,
                    'cellData'      => $this->cellData,
                    'hdop'          => $this->hdop,
                    'bearing'       => $this->bearing,
                    'address'       => $this->address,
                    'aux0Value'     => $this->aux0Value,
                    'aux1Value'     => $this->aux1Value,
                    'aux2Value'     => $this->aux2Value,
                    'aux3Value'     => $this->aux3Value,
                    'seatsOccupied' => $this->seatsOccupied,
                ]);
            }


            $this->vehiclePositionCreated = true;
        }

        return $this->vehiclePositionCache;
    }

    /**
     * If vehicle is capable of g-sense, return 0 or 1 depending on it being disabled or enabled, otherwise return null.
     *
     * @return int|null
     */
    public function canGSense()
    {
        if (is_null($this->unit) || !$this->unit->canGSense()) {
            return null;
        }

        return is_null($this->fnl) ? 0 : $this->fnl;
    }


    /**
     * @param Vehicle $originalVehicle
     * @param Vehicle $vehicle
     * @return bool
     * @throws Exception
     */
    public function sendEWMModeChanges($originalVehicle, $vehicle): bool
    {
        //If EWM's are on, or they are going to be on based on a pending command
        $currentDateTime = Carbon::now()->toDateTimeString();

        $ewmPendingOn = PendingCommand::where('vehicleId', $vehicle->vehicleId)
            ->where('command', 33)
            ->where('created', '<', $currentDateTime)
            ->where('commandValue', '1010')
            ->where('status', 'pending')
            ->first();

        if ($this->isEwmOn($vehicle->smsAlertStatus) || $ewmPendingOn) {

            PendingCommand::where('vehicleId', $vehicle->vehicleId)
                ->where('command', 33)
                ->where('created', '>', $currentDateTime)
                ->where('status', 'pending')
                ->delete();

            $toAdd = [];

            $now = Carbon::now('UTC');

            if ($vehicle->noAlertStart && $vehicle->noAlertEnd) {

                $newStart = new Carbon($vehicle->noAlertStart, 'UTC');
                $newEnd = new Carbon($vehicle->noAlertEnd, 'UTC');

                if ($originalVehicle->noAlertStart && $originalVehicle->noAlertEnd) {

                    $originalStart = new Carbon($originalVehicle->noAlertStart, 'UTC');
                    $originalEnd = new Carbon($originalVehicle->noAlertEnd, 'UTC');

                    if ($now->between($originalStart, $originalEnd) && !$now->between($newStart, $newEnd)) {
                        //It was enabled but now it is not
                        $toAdd[] = ['date' => $now->toDateTimeString(), 'type' => 'end'];
                        PendingCommand::where('vehicleId', $vehicle->vehicleId)
                            ->where('command', 33)
                            ->where('status', 'pending')
                            ->delete();
                    }
                } elseif ($now->between($newStart, $newEnd)) {
                    //No previous and the range has already started
                    //There is bad update prevention elsewhere so we can trust it at this point
                    $toAdd[] = ['date' => $now->toDateTimeString(), 'type' => 'start'];
                }

                //New start doesn't have a command yet
                if (!$now->greaterThan($newStart)) {
                    $toAdd[] = ['date' => $newStart->toDateTimeString(), 'type' => 'start'];
                }

                $toAdd[] = ['date' => $newEnd->toDateTimeString(), 'type' => 'end'];
            } elseif ($originalVehicle->noAlertStart && $originalVehicle->noAlertEnd) {
                //No longer set but was before
                $originalStart = new Carbon($originalVehicle->noAlertStart, 'UTC');
                $originalEnd = new Carbon($originalVehicle->noAlertEnd, 'UTC');

                if ($now->between($originalStart, $originalEnd)) {
                    //It was enabled but now it is not
                    $toAdd[] = ['date' => $now->toDateTimeString(), 'type' => 'end'];

                    PendingCommand::where('vehicleId', $vehicle->vehicleId)
                        ->where('command', 33)
                        ->where('status', 'pending')
                        ->delete();
                }
            }

            $baseCommand = [
                'vehicleId' => $vehicle->vehicleId,
                'command' => 33,
            ];

            $commandsToAdd = [];

            if ($toAdd) {
                foreach ($toAdd as $date) {
                    $command = $baseCommand;
                    $command['created'] = $date['date'];
                    $command['commandValue'] = ($date['type'] === 'start') ? '1000' : '1010';
                    $commandsToAdd[] = $command;
                }
            }

            return ($commandsToAdd) ? DB::table('PendingCommand')->insert($commandsToAdd) : true;
        }

        return true;
    }

    /**
     * @return Boolean
     * Checks wether the unit has a module fitted with an immobiliser
     */
    public function immobiliser()
    {   
        return $this->driverOptions == 20 || $this->driverOptions == 30;
    }

}
