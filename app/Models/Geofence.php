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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Driver
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Geofence extends Model
{
    public const TYPE_FIXED   = "fixed";
    public const TYPE_PLOT    = "plot";
    public const TYPE_POLYGON = "polygon";

    public const TYPE_FIXED_ID   = 1;
    public const TYPE_PLOT_ID    = 2;
    public const TYPE_POLYGON_ID = 3;


    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Geofence';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'geofenceId';

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }

    /**
     * @return HasOne
     */
    public function data(): HasOne
    {
        return $this->hasOne(GeofenceData::class, 'geofenceId', 'geofenceId');
    }
}
