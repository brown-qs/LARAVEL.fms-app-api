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

use App\Support\Facades\Internationalisation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Customer
 *
 * @package App\Models
 * @author  Tariq Tamuji <tariq@hare.digital>
 */
class Alert extends Model
{

    public const ALERT_TYPE_SPEED_CONTEXTUAL      = 'Contextual Speed';
    public const ALERT_TYPE_SPEED_FIXED           = 'Fixed Speed';
    public const ALERT_TYPE_SPEED                 = 'Speed';
    public const ALERT_TYPE_IGNITION              = 'Ignition';
    public const ALERT_TYPE_IDLE                  = 'Idle';
    public const ALERT_TYPE_GF_ENTRY              = 'GF Entry';
    public const ALERT_TYPE_GF_EXIT               = 'GF Exit';
    public const ALERT_TYPE_GF_SPEED              = 'GF Speed';
    public const ALERT_TYPE_GF_PLOT               = 'GF Plot';
    public const ALERT_TYPE_DRIVER_ID             = 'Driver ID';
    public const ALERT_TYPE_ANALOG                = 'Analog';
    public const ALERT_TYPE_AUX_HIGH              = 'Aux High';
    public const ALERT_TYPE_AUX_LOW               = 'Aux Low';
    public const ALERT_TYPE_GFV                   = 'GFV';
    public const ALERT_TYPE_IOV                   = 'IOV';
    public const ALERT_TYPE_MFR                   = 'MFR';
    public const ALERT_TYPE_RST                   = 'RST';
    public const ALERT_TYPE_VPI                   = 'VPI';
    public const ALERT_TYPE_VBL                   = 'VBL';
    public const ALERT_TYPE_MWG                   = 'MWG';
    public const ALERT_TYPE_IPI                   = 'IPI';
    public const ALERT_TYPE_GJA                   = 'GJA';
    public const ALERT_TYPE_ENGINE                = 'Engine';
    public const ALERT_TYPE_NOT_IN_USE            = 'Not_In_Use';

    public const VALID_ALERT_TYPES = [
        self::ALERT_TYPE_SPEED_CONTEXTUAL,
        self::ALERT_TYPE_SPEED_FIXED,
        self::ALERT_TYPE_SPEED,
        self::ALERT_TYPE_IGNITION,
        self::ALERT_TYPE_IDLE,
        self::ALERT_TYPE_GF_ENTRY,
        self::ALERT_TYPE_GF_EXIT,
        self::ALERT_TYPE_GF_SPEED,
        self::ALERT_TYPE_GF_PLOT,
        self::ALERT_TYPE_DRIVER_ID,
        self::ALERT_TYPE_ANALOG,
        self::ALERT_TYPE_AUX_HIGH,
        self::ALERT_TYPE_AUX_LOW,
        self::ALERT_TYPE_GFV,
        self::ALERT_TYPE_IOV,
        self::ALERT_TYPE_MFR,
        self::ALERT_TYPE_RST,
        self::ALERT_TYPE_VPI,
        self::ALERT_TYPE_VBL,
        self::ALERT_TYPE_MWG,
        self::ALERT_TYPE_IPI,
        self::ALERT_TYPE_GJA,
        self::ALERT_TYPE_ENGINE,
        self::ALERT_TYPE_NOT_IN_USE,
    ];

    public const ALERT_LEVEL_NOTICE  = 'Notice';
    public const ALERT_LEVEL_WARNING = 'Warning';
    public const ALERT_LEVEL_ALARM   = 'Alarm';

    public const VALID_ALERT_LEVELS = [
        self::ALERT_LEVEL_NOTICE,
        self::ALERT_LEVEL_WARNING,
        self::ALERT_LEVEL_ALARM,
    ];

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Alert';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'alertId';

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'customerId'       => 'integer',
        'vehicleId'        => 'integer',
        'driverId'         => 'integer',
        'days'             => 'integer',
        'idleLimit'        => 'integer',
        'geofenceId'       => 'integer',
        'engineLimit'      => 'integer',
        'speedLimitMargin' => 'float',
        'auxId'            => 'integer',
        'groupId'          => 'integer',
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
    public function vehicleGroup(): BelongsTo
    {
        return $this->belongsTo(VehicleGroup::class, 'groupId', 'groupId');
    }

    /**
     * @param $speedLimit
     *
     * @return mixed
     */
    public function getSpeedLimitAttribute($speedLimit)
    {
        return Internationalisation::convertMilesToKilometers($speedLimit);
    }

    public function setSpeedLimitAttribute($speedLimit)
    {
        $this->speedLimit = Internationalisation::convertKilometersToMiles($speedLimit);
    }

}
