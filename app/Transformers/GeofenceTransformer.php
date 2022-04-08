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

use App\Models\Geofence;
use League\Fractal\Resource\ResourceAbstract;

/**
 * UnitTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class GeofenceTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['geofence_data'];

    /**
     * @param Geofence $geofence
     *
     * @return array
     */
    public function transform(Geofence $geofence): array
    {
        return [
            'id'          => $geofence->geofenceId,
            'name'        => $geofence->name,
            'type'        => $geofence->type,
            'lat'         => $geofence->lat,
            'lng'         => $geofence->lng,
            'radius'      => $geofence->radius,
            'description' => $geofence->description,
            'colour'      => $geofence->colour,
        ];
    }

    /**
     * @param Geofence $geofence
     *
     * @return ResourceAbstract
     */
    public function includeGeofenceData(Geofence $geofence): ResourceAbstract
    {
        return $this->returnItem($geofence->data, GeofenceDataTransformer::class);
    }
}
