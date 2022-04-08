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
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VehicleNote
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleNote extends Model
{
    public const VISIBILITY_PUBLIC  = 'Public';
    public const VISIBILITY_PRIVATE = 'Private';

    public const TYPE_CUSTOMER = 'Customer';
    public const TYPE_ADMIN    = 'Admin';

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'VehicleNotes';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'noteId';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'timestamp',
        'deletedTimestamp',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'deleted' => 'boolean',
        'read'    => 'boolean',
    ];

    public static $allowedModeActions = [
        'zeroSpeedModeEnabled' => 'ZERO SPEED',
        'privacyModeEnabled' => 'PRIVACY MODE'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customerId', 'customerId');
    }

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicleId', 'vehicleId');
    }

    public function modeChangeCheck($modeAction, $existingValue, $newValue, $userId, $vehicleId, $customerId, $isAdmin)
    {
        if (array_key_exists($modeAction, self::$allowedModeActions)) {

            //If going from empty to on then log
            //If its not empty but its different then log
            if (
                ($existingValue === null && $newValue) ||
                ($existingValue !== null && $existingValue !== (bool)$newValue)
            ) {
                $statusValue = ($newValue) ? 'ENABLED' : 'DISABLED';
                $statusAction = (self::$allowedModeActions[$modeAction]) . ' ' . $statusValue;

                $note = new VehicleNote();
                $note->vehicleId = $vehicleId;
                $note->userId = $userId;
                $note->noteType = ($isAdmin) ? VehicleNote::TYPE_ADMIN : VehicleNote::TYPE_CUSTOMER;
                $note->note = $statusAction;
                $note->visibility = VehicleNote::VISIBILITY_PRIVATE;
                $note->timestamp = Carbon::now();
                $note->deleted = 0;
                $note->read = 0;
                $note->save();
            }
        }
        return true;
    }

}
