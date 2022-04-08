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

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Subscription extends Model
{
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Subscription';

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'unitId'     => 'integer',
        'customerId' => 'integer',
        'length'     => 'integer',
        'monitored'  => 'boolean',
        'invoiced'   => 'boolean',
    ];

    protected $fillable = [
        'unitId',
        'customerId',
        'length',
        'subStart',
        'subEnd',
        'monitored',
    ];

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'unitId';

    public function subscribeUntilFirstOfNextMonth($unitId, $customerId)
    {
        $subscription = new Subscription();
        $subscription->unitId = $unitId;
        $subscription->customerId = $customerId;
        $subscription->length = 1;
        $subscription->subStart = Carbon::now('UTC')->format('Y-m-d H:i:s');
        $subscription->subEnd = Carbon::now('UTC')->addMonth()->startOfMonth()->format('Y-m-d H:i:s');
        return $subscription->saveOrFail();
    }
}
