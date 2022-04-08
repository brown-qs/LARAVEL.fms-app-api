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


use App\Models\HealthCheck;

/**
 * Class HealthCheckTransformer
 *
 * @package App\Transformers
 * @author  Luke Vincent <luke@hare.digital>
 */
class HealthCheckTransformer extends DefaultTransformer
{
    /**
     * @param HealthCheck $healthCheck
     *
     * @return array
     */
    public function transform(HealthCheck $healthCheck)
    {
        $this->withData([
            'id'                      => $healthCheck->healthCheckReportId,
            'vehicle_id'              => $healthCheck->vehicleId,
            'unit_id'                 => $healthCheck->unitId,
            'gps_antenna_voltage'     => $healthCheck->gpsAntennaVoltage,
            'gps_antenna_current'     => $healthCheck->gpsAntennaCurrent,
            'last_gps_timestamp'      => $healthCheck->lastGpsFix->timestamp,
            'vehicle_battery_voltage' => $healthCheck->batteryVoltage,
            'backup_battery_voltage'  => $healthCheck->backupVoltage,
            'vehicle_system_voltage'  => $healthCheck->vehicleSystemVoltage,
            'unit_state'              => $healthCheck->unitState,
            'is_ignition_on'          => $healthCheck->isIgnitionOn,
            'received_at'             => $healthCheck->receivedAt->timestamp,
        ]);

        return $this->build();
    }
}
