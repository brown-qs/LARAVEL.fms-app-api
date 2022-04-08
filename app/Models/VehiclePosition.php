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

/**
 * Class VehiclePosition
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehiclePosition extends Model
{
    /*
     * The state constants
     */
    const STATE_UNSET = 'UNSET';
    const STATE_SET   = 'SET';
    const STATE_ALT   = 'ALT';
    const STATE_ALM   = 'ALM';
    const STATE_UNSUB = 'UNSUB';
    const STATE_INST  = 'INST';
    const STATE_SLP   = 'SLP';

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
    protected $primaryKey = null;

    /**
     * {@inheritDoc}
     */
    protected $table = 'VehiclePosition';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'timestamp',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'lat'           => 'float',
        'lng'           => 'float',
        'accuracy'      => 'float',
        'speed'         => 'float',
        'aux0Value'     => 'float',
        'aux1Value'     => 'float',
        'aux2Value'     => 'float',
        'aux3Value'     => 'float',
        'hdop'          => 'float',
        'vehicleId'     => 'integer',
        'driverId'      => 'integer',
        'ignition'      => 'boolean',
        'seatsOccupied' => 'integer',
    ];

    protected $fillable = [
        'vehicleId',
        'customerId',
        'timestamp',
        'driverId',
        'healthCheckId',
        'state',
        'gpsType',
        'gpsSatellites',
        'lat',
        'lng',
        'accuracy',
        'speed',
        'ignition',
        'engine',
        'cellData',
        'hdop',
        'bearing',
        'address',
        'aux0Value',
        'aux1Value',
        'aux2Value',
        'aux3Value',
        'seatsOccupied',
    ];

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
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnlyLatest(Builder $query): Builder
    {
        return $query->orderBy('timestamp', 'DESC')->limit(1);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeJourneyPositions(Builder $query): Builder
    {
        return $query->orderBy('timestamp', 'ASC');
    }
}
