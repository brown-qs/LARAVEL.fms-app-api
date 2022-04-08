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

/**
 * Class Customer
 *
 * @package App\Models
 * @author  Tariq Tamuji <tariq@hare.digital>
 */
class AlertEvent extends Model
{
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'AlertEvent';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'alertEventId';

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'markRead' => 'boolean',
    ];

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'timestamp',
    ];

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
     * @return BelongsTo
     */
    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class, 'alertId', 'alertId');
    }

    public function getPositionAttribute(): VehiclePosition
    {
        return VehiclePosition::where("vehicleId", $this->vehicleId)
                              ->where("timestamp", $this->positionId)
                              ->first();
    }
}
