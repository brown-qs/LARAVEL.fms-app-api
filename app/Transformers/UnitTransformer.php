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

use App\Models\Unit;
use League\Fractal\Resource\ResourceAbstract;

/**
 * UnitTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class UnitTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['vehicle', 'subscription'];

    /**
     * @param Unit $unit
     *
     * @return array
     */
    public function transform(Unit $unit): array
    {
        return [
            'id'          => $unit->unitId,
            'hardware_id' => $unit->hardwareId,
            'app_id'      => $unit->appId,
            'type'        => $unit->type,
            'core_id'     => $unit->coreId,
            'sim_card_no' => $unit->simCardNo,
        ];
    }

    /**
     * @param Unit $unit
     *
     * @return ResourceAbstract
     */
    public function includeVehicle(Unit $unit): ResourceAbstract
    {
        return $this->returnItem($unit->vehicle, VehicleTransformer::class);
    }

    /**
     * @param Unit $unit
     *
     * @return ResourceAbstract
     */
    public function includeSubscription(Unit $unit): ResourceAbstract
    {
        return $this->returnItem($unit->subscription, SubscriptionTransformer::class);
    }
}
