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

use App\Models\User;
use League\Fractal\Resource\ResourceAbstract;

/**
 * UserTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class UserTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['customer', 'permissions', 'permittedGroups'];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user): array
    {
        $this->withData([
            'id'                => $user->userId,
            'customer_id'       => $user->customerId,
            'dealership_id'     => $user->dealershipId,
            'type'              => $user->type,
            'first_name'        => $user->firstName,
            'last_name'         => $user->lastName,
            'full_name'         => "$user->firstName $user->lastName",
            'email'             => $user->email,
            'active'            => $user->active,
            'timezone'          => $user->timezone,
            'mobile_phone'      => $user->mobilePhone,
            'last_login'        => carbon_timestamp($user->lastLogin),
            'last_active'       => carbon_timestamp($user->lastActive),
            'distance_units'    => $user->distanceUnits,
            'volume_units'      => $user->volumeUnits,
            'security_question' => $user->securityQuestion,
            'cookie_policy' => $user->cookieAccepted,
            'privacy_policy' => $user->privacyPolicyAccepted,
            'terms_policy' => $user->termsAccepted,
            'brand_admin_for' => $user->brandAdminFor,
        ]);

        $this->withLinks([
            'self' => route('users.show', ['userId' => $user->userId]),
        ]);

        return $this->build();
    }

    /**
     * @param User $user
     *
     * @return ResourceAbstract
     */
    public function includePermissions(User $user): ResourceAbstract
    {
        return $this->returnCollection($user->permissions, PermissionsTransformer::class);
    }

    /**
     * @param User $user
     *
     * @return ResourceAbstract
     */
    public function includeCustomer(User $user): ResourceAbstract
    {
        return $this->returnItem($user->customer, CustomerTransformer::class);
    }

    /**
     * @param User $user
     *
     * @return ResourceAbstract
     */
    public function includePermittedGroups(User $user): ResourceAbstract
    {
        return $this->returnCollection($user->permittedGroups, VehicleGroupTransformer::class);
    }
}
