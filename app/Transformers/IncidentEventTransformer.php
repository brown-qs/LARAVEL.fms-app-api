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

use App\Models\IncidentEvent;
use App\Models\KenyaMeta;
use App\Models\VehiclePosition;
use League\Fractal\Resource\ResourceAbstract;

/**
 * KenyaMetaTransformer
 *
 * @package App\Transformers
 * @author
 */
class IncidentEventTransformer extends DefaultTransformer
{

    /**
     *
     * @return array
     */
    public function transform(IncidentEvent $incidentEvent): array
    {
        $this->withData([
            'timestamp'      => $incidentEvent->timestamp,
            'alert_type'      => $incidentEvent->alertType,
            'lat' => $incidentEvent->lat,
            'lng' => $incidentEvent->lng,
            'speed' => $incidentEvent->speed,
            'ignition' => $incidentEvent->ignition,
            'engine' => $incidentEvent->engine,
        ]);
        return $this->build();
    }
}
