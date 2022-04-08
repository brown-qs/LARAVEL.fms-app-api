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

use App\Models\Driver;
use App\Models\VehicleJourney;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * DriverController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class DriverController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $drivers = Driver::where('customerId', $this->request->get('user')->customerId)
                         ->with('latestJourney.vehicle')
                         ->orderBy('lastActive', 'DESC');

        if ($this->request->has('search')) {
            $drivers = $drivers->where(function ($q) {
                $q->where('firstName', 'like', '%' . $this->request->get('search') . '%')
                  ->orWhere('lastName', 'like', '%' . $this->request->get('search') . '%')
                  ->orWhere('mobilePhone', 'like', '%' . $this->request->get('search') . '%')
                  ->orWhere('email', 'like', '%' . $this->request->get('search') . '%');
            });
        }

        $drivers = $drivers->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($drivers, ['groups', 'latest_journey.vehicle'], 'drivers')
                    ->respond();
    }

    /**
     * @param int $driverId
     *
     * @return JsonResponse
     */
    public function showAction(int $driverId): JsonResponse
    {
        $driver = Driver::where('driverId', $driverId)
                        ->where('customerId', $this->request->get('user')->customerId)
                        ->with('latestJourney')
                        ->first();

        if (!$driver) {
            return $this->respondWithNotFound('Driver not found');
        }

        return $this->transformItem($driver, 'latest_journey')
                    ->respond();
    }

    public function paginateJourneysAction(int $driverId): JsonResponse
    {
        $vehicleJourneys = VehicleJourney::where('customerId', $this->request->get('user')->customerId)
                                         ->where('driverId', $driverId)
                                         ->with('vehicle', 'driver');

        if ($this->request->has('from') && $this->request->has('to')) {
            try {
                $from = Carbon::createFromTimestamp($this->request->get('from'))->toDateTimeString();
                $to   = Carbon::createFromTimestamp($this->request->get('to'))->toDateTimeString();
            } catch (\Exception $ex) {
                return $this->respondWithInvalidRequest("From and To must be valid unix timestamps");
            }

            $vehicleJourneys = $vehicleJourneys->whereBetween('startTime', [$from, $to])
                                               ->whereBetween('endTime', [$from, $to]);
        }

        $vehicleJourneys = $vehicleJourneys->orderBy('endTime', 'DESC')
                                           ->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($vehicleJourneys, ['vehicle', 'driver'], 'vehicle_journeys')
                    ->respond();
    }

    /**
     * @param int $driverId
     * @param int $start
     * @param int $end
     *
     * @return JsonResponse
     */
    public function showJourneyAction(int $driverId, int $start, int $end): JsonResponse
    {
        $start = Carbon::createFromTimestamp($start);
        $end   = Carbon::createFromTimestamp($end);

        $vehicleJourney = VehicleJourney::where('driverId', $driverId)
                                        ->where('customerId', $this->request->get('user')->customerId)
                                        ->where('startTime', $start)
                                        ->where('endTime', $end)
                                        ->with(['positions' => function ($query) use ($start, $end) {
                                            $query->whereBetween('timestamp', [
                                                $start->toDateTimeString(),
                                                $end->toDateTimeString(),
                                            ]);
                                        }])
                                        ->first();

        if (!$vehicleJourney) {
            return $this->respondWithNotFound('Driver Journey not found');
        }

        return $this->transformItem($vehicleJourney, ['positions'])
                    ->respond();
    }
}
