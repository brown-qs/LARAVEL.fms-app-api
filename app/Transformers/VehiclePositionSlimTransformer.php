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

/**
 * VehicleTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehiclePositionSlimTransformer extends DefaultTransformer
{
    /**
     * @param VehiclePosition $vehiclePosition
     *
     * @return array
     */
    public function transform(VehiclePosition $vehiclePosition): array
    {
        $this->withData([
            'timestamp'      => carbon_timestamp($vehiclePosition->timestamp),
            'state'          => $vehiclePosition->state,
            'lat'            => $vehiclePosition->lat,
            'lng'            => $vehiclePosition->lng,
            'speed'          => $vehiclePosition->speed,
            'ignition'       => $vehiclePosition->ignition,
            'engine'         => $vehiclePosition->engine,
            'bearing'        => $vehiclePosition->bearing,
            'gps_satellites' => $vehiclePosition->gpsSatellites,
            'hdop'           => $vehiclePosition->hdop,
            'aux_0_value'    => $vehiclePosition->aux0Value,
            'aux_1_value'    => $vehiclePosition->aux1Value,
            'aux_2_value'    => $vehiclePosition->aux2Value,
            'aux_3_value'    => $vehiclePosition->aux3Value,
            'seats_occupied' => $vehiclePosition->seatsOccupied,
        ]);

        $this->withLinks([
            'self' => customerRoute('vehicles.show-position', [
                'vehicleId' => $vehiclePosition->vehicleId,
                'time'      => carbon_timestamp($vehiclePosition->timestamp),
            ]),
        ]);

        return $this->build();
    }
}
