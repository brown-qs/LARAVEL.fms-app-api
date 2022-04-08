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

use App\Models\Alert;

/**
 * Class AddressTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class AlertTransformer extends DefaultTransformer
{

    /**
     * @param Alert $alert
     *
     * @return array
     */
    public function transform(Alert $alert): array
    {
        $this->withData([
            'alert_id'         => $alert->alertId,
            'customer_id'      => $alert->customerId,
            'vehicle_id'       => $alert->vehicleId,
            'group_id'         => $alert->groupId,
            'name'             => $alert->name,
            'description'      => $alert->description,
            'type'             => ($alert->type === 'Contextual Speed' || $alert->type === 'Fixed Speed')
                ? 'Speed' : $alert->type,   // TODO: remove once mobile team have implement fixed speed.
            'level'            => $alert->level,
            'email'            => $alert->email,
            'txt'              => $alert->txt,
            'aux_id'           => $alert->auxId,
            'speed_limit'      => $alert->speedLimit,
            'days'             => $alert->days,
            'idle_limit'       => $alert->idleLimit,
            'engine_limit'     => $alert->engineLimit,
            'geofence_id'      => $alert->geofenceId,
            'sunday_start'     => $alert->sundayStart,
            'sunday_end'       => $alert->sundayEnd,
            'monday_start'     => $alert->mondayStart,
            'monday_end'       => $alert->mondayEnd,
            'tuesday_start'    => $alert->tuesdayStart,
            'tuesday_end'      => $alert->tuesdayEnd,
            'wednesday_start'  => $alert->wednesdayStart,
            'wednesday_end'    => $alert->wednesdayEnd,
            'thursday_start'   => $alert->thursdayStart,
            'thursday_end'     => $alert->thursdayEnd,
            'friday_start'     => $alert->fridayStart,
            'friday_end'       => $alert->fridayEnd,
            'saturday_start'   => $alert->saturdayStart,
            'saturday_end'     => $alert->saturdayEnd,
            'timezone'         => $alert->timezone,
            'speedLimitMargin' => $alert->speedLimitMargin,
        ]);

        $this->withLinks([
            'self' => customerRoute('alerts.show', ['alertId' => $alert->alertId]),
        ]);

        return $this->build();

    }

}
