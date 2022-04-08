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

use App\Models\Driver;
use League\Fractal\Resource\ResourceAbstract;

/**
 * DriverTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class DriverTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['customer', 'journeys', 'latest_journey'];

    /**
     * @param Driver $driver
     *
     * @return array
     */
    public function transform(Driver $driver): array
    {
        $this->withData([
            'id'             => $driver->driverId,
            'customer_id'    => $driver->customerId,
            'first_name'     => $driver->firstName,
            'last_name'      => $driver->lastName,
            'full_name'      => "$driver->firstName $driver->lastName",
            'email'          => $driver->email,
            'active'         => $driver->active,
            'timezone'       => $driver->timezone,
            'mobile_phone'   => $driver->mobilePhone,
            'last_login'     => carbon_timestamp($driver->lastLogin),
            'last_active'    => carbon_timestamp($driver->lastActive),
            'volume_units'   => $driver->volumeUnits,
            'distance_units' => $driver->distanceUnits,
        ]);

        $this->withLinks([
            'self'     => customerRoute('drivers.show', ['driverId' => $driver->driverId]),
            'journeys' => customerRoute('drivers.show-journeys', ['driverId' => $driver->driverId]),
        ]);

        return $this->build();
    }

    /**
     * @param Driver $driver
     *
     * @return ResourceAbstract
     */
    public function includeCustomer(Driver $driver): ResourceAbstract
    {
        return $this->returnItem($driver->customer, CustomerTransformer::class);
    }

    /**
     * @param Driver $driver
     *
     * @return ResourceAbstract
     */
    public function includeJourneys(Driver $driver): ResourceAbstract
    {
        return $this->returnCollection($driver->journeys, VehicleJourneyTransformer::class);
    }

    /**
     * @param Driver $driver
     *
     * @return ResourceAbstract
     */
    public function includeLatestJourney(Driver $driver): ResourceAbstract
    {
        return $this->returnItem($driver->latestJourney, VehicleJourneyTransformer::class);
    }
}
