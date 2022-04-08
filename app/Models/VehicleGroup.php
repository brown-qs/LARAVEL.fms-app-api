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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Customer
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleGroup extends Model
{
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'VehicleGroup';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'groupId';

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->BelongsTo(Customer::class, 'customerId', 'customerId');
    }

    /**
     * @return BelongsToMany
     */
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class,
            'AssignedVehicleGroup',
            'groupId',
            'vehicleId'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function permittedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class,
            'AssignedPreferences',
            'preferenceId',
            'customerId'
        );
    }

    public function countVehicles()
    {
        return DB::table('AssignedVehicleGroup')
            ->where('groupId', $this->groupId)
            ->count();
    }
}
