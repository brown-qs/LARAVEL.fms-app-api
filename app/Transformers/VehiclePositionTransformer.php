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

use App\Models\VehiclePosition;
use League\Fractal\Resource\ResourceAbstract;

/**
 * VehicleTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehiclePositionTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['customer', 'driver', 'vehicle', 'positions'];

    /**
     * @param VehiclePosition $vehiclePosition
     *
     * @return array
     */
    public function transform(VehiclePosition $vehiclePosition): array
    {
        $driverId = $vehiclePosition->driverId === 0 ? null : $vehiclePosition->driverId;

        $this->withData([
            'vehicle_id'      => $vehiclePosition->vehicleId,
            'customer_id'     => $vehiclePosition->customerId,
            'timestamp'       => carbon_timestamp($vehiclePosition->timestamp),
            'driver_id'       => $driverId,
            'health_check_id' => $vehiclePosition->healthCheckId,
            'state'           => $vehiclePosition->state,
            'gps_type'        => $vehiclePosition->gpsType,
            'gps_satellites'  => $vehiclePosition->gpsSatellites,
            'lat'             => $vehiclePosition->lat,
            'lng'             => $vehiclePosition->lng,
            'accuracy'        => $vehiclePosition->accuracy,
            'speed'           => $vehiclePosition->speed,
            'ignition'        => $vehiclePosition->ignition,
            'engine'          => $vehiclePosition->engine,
            'cell_data'       => $vehiclePosition->cellData,
            'hdop'            => $vehiclePosition->hdop,
            'bearing'         => $vehiclePosition->bearing,
            'address'         => $vehiclePosition->address,
            'aux_0_value'     => $vehiclePosition->aux0Value,
            'aux_1_value'     => $vehiclePosition->aux1Value,
            'aux_2_value'     => $vehiclePosition->aux2Value,
            'aux_3_value'     => $vehiclePosition->aux3Value,
            'seats_occupied'  => $vehiclePosition->seatsOccupied,
        ]);

        $this->withLinks([
            'vehicle' => customerRoute('vehicles.show', ['vehicleId' => $vehiclePosition->vehicleId]),
            'driver'  =>
                is_null($driverId) ?
                    null : customerRoute('drivers.show', ['driverId' => $vehiclePosition->driverId]),
        ]);

        return $this->build();
    }

    /**
     * @param VehiclePosition $vehiclePosition
     *
     * @return ResourceAbstract
     */
    public function includeDriver(VehiclePosition $vehiclePosition): ResourceAbstract
    {
        return $this->returnItem($vehiclePosition->driver, DriverTransformer::class);
    }

    /**
     * @param VehiclePosition $vehiclePosition
     *
     * @return ResourceAbstract
     */
    public function includeVehicle(VehiclePosition $vehiclePosition): ResourceAbstract
    {
        return $this->returnItem($vehiclePosition->vehicle, VehicleTransformer::class);
    }

    /**
     * @param VehiclePosition $vehiclePosition
     *
     * @return ResourceAbstract
     */
    public function includeCustomer(VehiclePosition $vehiclePosition): ResourceAbstract
    {
        return $this->returnItem($vehiclePosition->customer, CustomerTransformer::class);
    }
}
