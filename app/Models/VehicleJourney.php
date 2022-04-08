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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * Class VehicleJourney
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleJourney extends Model
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
    protected $table = 'VehicleJourney';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = null;

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'startTime',
        'endTime',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'startLat'     => 'float',
        'startLon'     => 'float',
        'endLat'       => 'float',
        'endLon'       => 'float',
        'averageSpeed' => 'float',
        'topSpeed'     => 'float',
        'distance'     => 'float',
        'vehicleId'    => 'integer',
        'customerId'   => 'integer',
        'driverId'     => 'integer',
        'fareData'     => 'integer',
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->attributesToArray(), $this->relationsToArray());
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driverId', 'driverId');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicleId', 'vehicleId');
    }

    /**
     * @return HasMany
     */
    public function positions(): HasMany
    {
        return $this->hasMany(VehiclePosition::class, 'vehicleId', 'vehicleId');
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnlyLatest(Builder $query): Builder
    {
        return $query->orderBy('VehicleJourney.endTime', 'DESC')->limit(1);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWithSeatFares(Builder $query): Builder
    {
        return $query->addSelect(DB::raw("VehicleJourney.*"))
                     ->addSelect(DB::raw("VehicleJourneyAux.auxData AS fareData"))
                     ->leftJoin("VehicleJourneyAux", function ($join) {
                         $join->on("VehicleJourneyAux.vehicleId", "=", "VehicleJourney.vehicleId")
                              ->on("VehicleJourneyAux.startTime", "=", "VehicleJourney.startTime");
                     });
    }
}
