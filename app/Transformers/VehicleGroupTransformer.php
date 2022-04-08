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

use App\Models\VehicleGroup;
use League\Fractal\Resource\ResourceAbstract;

/**
 * VehicleGroupTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleGroupTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['vehicles', 'customer'];

    /**
     * @param VehicleGroup $vehicleGroup
     *
     * @return array
     */
    public function transform(VehicleGroup $vehicleGroup): array
    {
        $this->withData([
            'id'                => $vehicleGroup->groupId,
            'customer_id'       => $vehicleGroup->customerId,
            'group_name'        => $vehicleGroup->groupName,
            'group_description' => $vehicleGroup->groupDescription,
            'vehicle_count'     => $vehicleGroup->countVehicles(),
        ]);

        $this->withLinks([
            'self' => route('vehicle-groups.show', ['vehicleGroupId' => $vehicleGroup->groupId]),
        ]);

        return $this->build();
    }

    /**
     * @param VehicleGroup $vehicleGroup
     *
     * @return ResourceAbstract
     */
    public function includeVehicles(VehicleGroup $vehicleGroup): ResourceAbstract
    {
        return $this->returnCollection($vehicleGroup->vehicles, VehicleTransformer::class);
    }

    /**
     * @param VehicleGroup $vehicleGroup
     *
     * @return ResourceAbstract
     */
    public function includeCustomer(VehicleGroup $vehicleGroup): ResourceAbstract
    {
        return $this->returnItem($vehicleGroup->customer, CustomerTransformer::class);
    }
}
