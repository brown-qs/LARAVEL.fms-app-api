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

use App\Models\AlertEvent;
use League\Fractal\Resource\ResourceAbstract;

/**
 * Class AlertEventTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class AlertEventTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['position', 'alert'];

    /**
     * @param AlertEvent $alertEvent
     *
     * @return array
     */
    public function transform(AlertEvent $alertEvent): array
    {

        $this->withData([
            'alert_event_id' => $alertEvent->alertEventId,
            'alert_id'       => $alertEvent->alertId,
            'customer_id'    => $alertEvent->customerId,
            'vehicle_id'     => $alertEvent->vehicleId,
            'timestamp'      => carbon_timestamp($alertEvent->timestamp),
            'mark_read'      => $alertEvent->markRead,
            'idle'           => $alertEvent->idle,
            'engine_hours'   => $alertEvent->engineHours,
            'aux_id'         => $alertEvent->auxId,
            'road_speed'     => $alertEvent->roadspeed,
            'driver_speed'   => $alertEvent->driverspeed,
        ]);

        $this->withLinks([
            'self'  => customerRoute('alert-events.show', ['alertEventId' => $alertEvent->alertEventId]),
            'alert' => customerRoute('alerts.show', ['alertId' => $alertEvent->alertId]),
        ]);

        return $this->build();

    }

    /**
     * @param AlertEvent $alertEvent
     *
     * @return ResourceAbstract
     *
     */
    public function includePosition(AlertEvent $alertEvent): ResourceAbstract
    {
        return $this->returnItem($alertEvent->position, VehiclePositionTransformer::class);
    }

    /**
     * @param AlertEvent $alertEvent
     *
     * @return ResourceAbstract
     *
     */
    public function includeAlert(AlertEvent $alertEvent): ResourceAbstract
    {
        return $this->returnItem($alertEvent->alert, AlertTransformer::class);
    }
}
