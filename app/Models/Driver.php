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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Driver
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Driver extends Model
{
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Driver';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'driverId';

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
        'salt',
    ];

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'lastLogin',
        'lastActive',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }

    /**
     * @return HasMany
     */
    public function journeys(): HasMany
    {
        return $this->hasMany(VehicleJourney::class, 'driverId', 'driverId');
    }

    /**
     * @return HasOne
     */
    public function latestJourney(): HasOne
    {
        return $this->hasOne(VehicleJourney::class, 'driverId', 'driverId')->Onlylatest();
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return sprintf("%s %s", $this->firstName, $this->lastName);
    }
}
