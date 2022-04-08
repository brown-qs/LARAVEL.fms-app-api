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

namespace App\Transformers;

use App\Models\VehicleJourney;
use League\Fractal\Resource\ResourceAbstract;

/**
 * VehicleTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleJourneyTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['customer', 'driver', 'vehicle', 'positions'];

    /**
     * @var bool
     */
    protected $fromVehicle;

    /**
     * @var bool
     */
    protected $slimPositions;

    /**
     * VehicleJourneyTransformer constructor.
     *
     * @param bool $fromVehicle
     * @param bool $slimPositions
     */
    public function __construct($fromVehicle = true, $slimPositions = false)
    {
        $this->fromVehicle   = $fromVehicle;
        $this->slimPositions = $slimPositions;
    }

    /**
     * @param VehicleJourney $vehicleJourney
     *
     * @return array
     */
    public function transform(VehicleJourney $vehicleJourney): array
    {
        $driverId = $vehicleJourney->driverId === 0 ? null : $vehicleJourney->driverId;
        $data     = [
            'vehicle_id'        => $vehicleJourney->vehicleId,
            'customer_id'       => $vehicleJourney->customerId,
            'driver_id'         => $driverId,
            'start_time'        => carbon_timestamp($vehicleJourney->startTime),
            'end_time'          => carbon_timestamp($vehicleJourney->endTime),
            'start_lat'         => $vehicleJourney->startLat,
            'end_lat'           => $vehicleJourney->endLat,
            'start_lng'         => $vehicleJourney->startLon,
            'end_lng'           => $vehicleJourney->endLon,
            'start_address'     => $vehicleJourney->startAddress,
            'end_address'       => $vehicleJourney->endAddress,
            'average_speed'     => $vehicleJourney->averageSpeed,
            'top_speed'         => $vehicleJourney->topSpeed,
            'total_idle_time'   => $vehicleJourney->totalIdleTime,
            'longest_idle_time' => $vehicleJourney->longestIdleTime,
            'distance'          => $vehicleJourney->distance,
            'aux_count'         => $vehicleJourney->auxCount,
            'fare_data'         => $vehicleJourney->fareData,
        ];

        $this->withData($data);

        $showJourneyRoute = [
            'startTime' => carbon_timestamp($vehicleJourney->startTime),
            'endTime'   => carbon_timestamp($vehicleJourney->endTime),
        ];

        if ($this->fromVehicle) {
            $showJourneyRoute['vehicleId'] = $vehicleJourney->vehicleId;
        } else {
            $showJourneyRoute['driverId'] = $this->driverId;
        }

        $this->withLinks([
            'self'    => customerRoute(
                $this->fromVehicle ? 'vehicles.show-journey' : 'drivers.show-journey',
                $showJourneyRoute
            ),
            'vehicle' => customerRoute('vehicles.show', ['vehicleId' => $vehicleJourney->vehicleId]),
            'driver'  =>
                is_null($driverId) ?
                    null : customerRoute('drivers.show', ['driverId' => $vehicleJourney->driverId]),
        ]);

        return $this->build();
    }

    /**
     * @param VehicleJourney $vehicleJourney
     *
     * @return ResourceAbstract
     */
    public function includeDriver(VehicleJourney $vehicleJourney): ResourceAbstract
    {
        return $this->returnItem($vehicleJourney->driver, DriverTransformer::class);
    }

    /**
     * @param VehicleJourney $vehicleJourney
     *
     * @return ResourceAbstract
     */
    public function includeVehicle(VehicleJourney $vehicleJourney): ResourceAbstract
    {
        return $this->returnItem($vehicleJourney->vehicle, VehicleTransformer::class);
    }

    /**
     * @param VehicleJourney $vehicleJourney
     *
     * @return ResourceAbstract
     */
    public function includeCustomer(VehicleJourney $vehicleJourney): ResourceAbstract
    {
        return $this->returnItem($vehicleJourney->customer, CustomerTransformer::class);
    }

    /**
     * @param VehicleJourney $vehicleJourney
     *
     * @return ResourceAbstract
     */
    public function includePositions(VehicleJourney $vehicleJourney): ResourceAbstract
    {
        return $this->returnCollection($vehicleJourney->positions, VehiclePositionTransformer::class);
    }
}
