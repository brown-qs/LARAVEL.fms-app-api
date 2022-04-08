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

use App\Models\GeofenceData;
use League\Fractal\Resource\ResourceAbstract;

/**
 * UnitTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class GeofenceDataTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}z
     */
    protected $availableIncludes = ['geofence'];

    /**
     * @param GeofenceData $geofenceData
     *
     * @return array
     */
    public function transform(GeofenceData $geofenceData): array
    {
        return [
            'points' => $geofenceData->points,
            'radius' => $geofenceData->radius,
        ];
    }

    /**
     * @param GeofenceData $geofenceData
     *
     * @return ResourceAbstract
     */
    public function includeGeofence(GeofenceData $geofenceData): ResourceAbstract
    {
        return $this->returnItem($geofenceData->geofence, GeofenceDataTransformer::class);
    }
}
