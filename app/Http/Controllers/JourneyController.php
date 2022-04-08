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

use App\Models\VehicleGroup;
use App\Models\VehicleJourney;
use App\Models\VehicleJourneyCalendar;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;

/**
 * DriverController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class JourneyController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function paginateAction(): JsonResponse
    {
        $authUser = $this->request->get('user');

        $query = VehicleJourney::where('customerId', $authUser->customerId)
                               ->with('vehicle.unit.subscription', 'driver');

        if ($authUser->customer->usesSeatModule) {
            $query->withSeatFares();
        }

        $vehicleIds = [];
        if ($authUser->permittedGroups()->exists()) {
            $vehicleIds = $authUser->permittedGroups()
                                   ->with('vehicles')
                                   ->get()
                                   ->flatMap(function ($group) {
                                       return $group->vehicles;
                                   })
                                   ->pluck('vehicleId')
                                   ->toArray();
        }

        if ($this->request->has('search')) {
            $vehicleGroup = VehicleGroup::with('vehicles')
                                        ->where('groupName', $this->request->get('search'))
                                        ->first();

            // TODO: uncomment after new app code deployed
            // if (is_null($vehicleGroup)) {
            //     return $this->respondWithInvalidRequest("Unrecognised search term");
            // }

            // TODO: remove outer if after new app code deployed
            if (!is_null($vehicleGroup)) {
                if (count($vehicleIds)) {
                    $vehicleIds = array_intersect($vehicleIds, $vehicleGroup->vehicles->pluck('vehicleId')->toArray());
                } else {
                    $vehicleIds = $vehicleGroup->vehicles->pluck('vehicleId')->toArray();
                }
            }
        }

        if (count($vehicleIds)) {
            $query->whereIn('VehicleJourney.vehicleId', $vehicleIds);
        }

        if ($this->request->has('from') && $this->request->has('to')) {
            try {
                $from = Carbon::createFromTimestamp($this->request->get('from'))->toDateTimeString();
                $to   = Carbon::createFromTimestamp($this->request->get('to'))->toDateTimeString();
            } catch (\Exception $ex) {
                return $this->respondWithInvalidRequest("From and To must be valid unix timestamps");
            }

            $query->whereBetween('VehicleJourney.startTime', [$from, $to]);
        }

        // sort collection is faster than mysql ¯\_(ツ)_/¯
        $vehicleJourneysPaginated = $query->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));
        $vehicleJourneys          = $vehicleJourneysPaginated->sortBy('endTime');
        $vehicleJourneys          = new LengthAwarePaginator(
            $vehicleJourneys,
            $vehicleJourneysPaginated->total(),
            $vehicleJourneysPaginated->perPage(),
            $vehicleJourneysPaginated->currentPage()
        );

        return $this->transformCollection($vehicleJourneys, ['vehicle', 'driver'], 'vehicle_journeys')
                    ->respond();
    }

    /**
     * @return JsonResponse
     */
    public function calendarAction(): JsonResponse
    {
        $authUser  = $this->request->get('user');
        $monthYear = Carbon::now();
        $monthYear->month($this->request->get('month'));
        $monthYear->year($this->request->get('year'));

        $from = $monthYear->copy();
        $from->startOfMonth();

        $to = $monthYear->copy();
        $to->endOfMonth();
        $query = VehicleJourneyCalendar::where('customerId', $authUser->customerId)
                                       ->whereBetween("startTime", [
                                           $from->toDateTimeString(),
                                           $to->toDateTimeString(),
                                       ]);

        if ($authUser->permittedGroups()->exists()) {
            $vehicleIds = $authUser->permittedGroups()
                                   ->with('vehicles')
                                   ->get()
                                   ->flatMap(function ($group) {
                                       return $group->vehicles;
                                   })
                                   ->pluck('vehicleId')
                                   ->toArray();
            $query->whereIn('vehicleId', $vehicleIds);
        }

        return $this->transformCollection($query->get(), null, 'vehicle_journey_calendar')
                    ->respond();
    }
}
