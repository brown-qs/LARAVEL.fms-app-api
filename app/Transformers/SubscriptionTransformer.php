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

use App\Models\Subscription;

/**
 * UnitTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class SubscriptionTransformer extends DefaultTransformer
{
    /**
     * @param Subscription $subscription
     *
     * @return array
     */
    public function transform(Subscription $subscription): array
    {
        return [
            'unit_id'            => $subscription->unitId,
            'customer_id'        => $subscription->customerId,
            'length'             => $subscription->length,
            'sub_start'          => $subscription->subStart,
            'sub_end'            => $subscription->subEnd,
            'monitored'          => $subscription->monitored,
            'paypalSubscribedId' => $subscription->paypalSubscribedId,
            'status'             => $subscription->status,
            'invoiced'           => $subscription->invoiced,
        ];
    }
}
