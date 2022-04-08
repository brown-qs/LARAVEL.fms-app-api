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

use App\Models\Customer;
use League\Fractal\Resource\ResourceAbstract;

/**
 * VehicleGroupTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class CustomerTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['users', 'vehicle_groups', 'vehicles', 'journeys'];

    /**
     * @param Customer $customer
     *
     * @return array
     */
    public function transform(Customer $customer): array
    {
        return [
            'id'                => $customer->customerId,
            'dealership_id'     => $customer->dealershipId,
            'company'           => $customer->company,
            'address'           => $customer->address,
            'address2'          => $customer->address2,
            'address3'          => $customer->address3,
            'county'            => $customer->county,
            'postcode'          => $customer->postcode,
            'country'           => $customer->country,
            'timezone'          => $customer->timezone,
            'primary_phone'     => $customer->primaryPhone,
            'fax'               => $customer->fax,
            'email'             => $customer->email,
            'description'       => $customer->description,
            'texts'             => $customer->texts,
            'new_user_notify'   => $customer->newUserNotify,
            'new_driver_notify' => $customer->newDriverNotify,
            'show_map_speed'    => $customer->showMapSpeed,
            'gsense'            => $customer->gsense,
            'invoiced_monthly'  => $customer->invoicedMonthly,
        ];
    }

    /**
     * @param Customer $customer
     *
     * @return ResourceAbstract
     */
    public function includeUsers(Customer $customer): ResourceAbstract
    {
        return $this->returnCollection($customer->users, UserTransformer::class);
    }

    /**
     * @param Customer $customer
     *
     * @return ResourceAbstract
     */
    public function includeVehicleGroups(Customer $customer): ResourceAbstract
    {
        return $this->returnCollection($customer->vehicleGroups, VehicleGroupTransformer::class);
    }

    /**
     * @param Customer $customer
     *
     * @return ResourceAbstract
     */
    public function includeVehicles(Customer $customer): ResourceAbstract
    {
        return $this->returnCollection($customer->vehicles, VehicleTransformer::class);
    }

    /**
     * @param Customer $customer
     *
     * @return ResourceAbstract
     */
    public function includeJourneys(Customer $customer): ResourceAbstract
    {
        return $this->returnCollection($customer->journeys, VehicleJourneyTransformer::class);
    }
}
