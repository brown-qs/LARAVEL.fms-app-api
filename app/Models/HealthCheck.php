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
 * Class HealthCheck
 *
 * @package App\Models
 * @author  Luke Vincent <luke@hare.digital>
 */
class HealthCheck extends Model
{
    public const SHORT_HEALTH_CHECK_LENGTH = 56;

    // constants that dictate the positioning of the various data sets within the diagnostic hex string;
    public const DEFAULT_DIAGNOSTIC_LENGTH = 4;
    public const GPS_ANTENNA_VOLTAGE_DIAGNOSTIC_START = 8;
    public const GPS_ANTENNA_CURRENT_DIAGNOSTIC_START = 12;
    public const GPS_TIME_DIAGNOSTIC_START = 16;
    public const DATETIME_DIAGNOSTIC_LENGTH = 6;
    public const GPS_DATE_DIAGNOSTIC_START = 22;
    public const BACKUP_BATTERY_VOLTAGE_DIAGNOSTIC_START = 28;
    public const VEHICLE_BATTERY_VOLTAGE_DIAGNOSTIC_START = 32;
    public const VEHICLE_SYSTEM_VOLTAGE_DIAGNOSTIC_INDEX = 53;
    public const UNIT_STATE_DIAGNOSTIC_INDEX = 41;

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'HealthCheckReport';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'HealthCheckReportId';

    /**
     * {@inheritDoc}
     */
    protected $dates = ['timestamp'];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'gpsAntennaVoltage' => 'float',
        'backupVoltage'     => 'float',
        'batteryVoltage'    => 'float',
        'is_ignition_on'    => 'bool',
    ];

    /**
     * HealthCheck constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns voltage in volts (V)
     *
     * @return float|int
     */
    public function getGpsAntennaVoltageAttribute()
    {
        $antennaVoltageFlag = substr(
            $this->diagnosticData,
            self::GPS_ANTENNA_VOLTAGE_DIAGNOSTIC_START,
            self::DEFAULT_DIAGNOSTIC_LENGTH
        );

        return round(hexdec($antennaVoltageFlag) / 1000, 2);
    }

    /**
     * Returns in current in milliamperes (mA)
     *
     * @return float|int
     */
    public function getGpsAntennaCurrentAttribute()
    {
        return hexdec(substr(
            $this->diagnosticData,
            self::GPS_ANTENNA_CURRENT_DIAGNOSTIC_START,
            self::DEFAULT_DIAGNOSTIC_LENGTH
        ));
    }

    /**
     * Returns a carbon instance of the last know GPS fix
     *
     * @return Carbon
     */
    public function getLastGpsFixAttribute()
    {
        $timeString = implode(':', str_split(substr(
            $this->diagnosticData,
            self::GPS_TIME_DIAGNOSTIC_START,
            self::DATETIME_DIAGNOSTIC_LENGTH
        ), 2));

        $dateString = implode('-', str_split(substr(
            $this->diagnosticData,
            self::GPS_DATE_DIAGNOSTIC_START,
            self::DATETIME_DIAGNOSTIC_LENGTH
        ), 2));

        return Carbon::createFromFormat('y-m-d H:i:s', "$dateString $timeString");
    }

    /**
     * @return float|int
     */
    public function getBackupVoltageAttribute()
    {
        $backupVoltageFlag = substr(
            $this->diagnosticData,
            self::BACKUP_BATTERY_VOLTAGE_DIAGNOSTIC_START,
            self::DEFAULT_DIAGNOSTIC_LENGTH
        );

        // TODO: replace integer with constant (no magic numbers! Why are we multiplying by 0.0048828125?)
        return round(hexdec($backupVoltageFlag) * 0.0048828125, 2);
    }

    /**
     * @return float
     */
    public function getBatteryVoltageAttribute()
    {
        $batteryVoltageFlag = substr(
            $this->diagnosticData,
            self::VEHICLE_BATTERY_VOLTAGE_DIAGNOSTIC_START,
            self::DEFAULT_DIAGNOSTIC_LENGTH
        );

        // TODO: replace integer with constant (no magic numbers! Why are we multiplying by 0.026855468?)
        return round(hexdec($batteryVoltageFlag) * 0.026855468, 2);
    }

    /**
     * @return bool|string
     */
    public function getVehicleSystemVoltageAttribute()
    {
        $isLongHealthCheck = strlen($this->diagnosticData) > self::SHORT_HEALTH_CHECK_LENGTH;
        $offset            = $isLongHealthCheck ? 4 : 0;

        $vehicleSystemVoltageFlag = substr(
            $this->diagnosticData,
            self::VEHICLE_SYSTEM_VOLTAGE_DIAGNOSTIC_INDEX + $offset,
            1
        );

        switch ($vehicleSystemVoltageFlag) {
            case '1':
                return 12;
            case '2':
                return 24;
            default:
                return null;
        }
    }

    /**
     * @return null|string
     */
    public function getUnitStateAttribute()
    {
        $isLongHealthCheck = strlen($this->diagnosticData) > self::SHORT_HEALTH_CHECK_LENGTH;
        $offset            = $isLongHealthCheck ? 4 : 0;

        $unitStateFlag = substr(
            $this->diagnosticData,
            self::UNIT_STATE_DIAGNOSTIC_INDEX + $offset,
            1
        );

        switch ($unitStateFlag) {
            case '0':
                return 'reset';
            case '1':
                return 'set';
            case '2':
                return 'unset';
            case '3':
                return 'alert';
            case '4':
                return 'alarm';
            case '5':
                return 'test';
            case '6':
                return 'installation';
            case '7':
                return 'unsubscribed';
            default:
                return null;
        }
    }

    /**
     * @return bool
     */
    public function getIsIgnitionOnAttribute()
    {
        return $this->unitState === '2';
    }

    /**
     * @return mixed
     */
    public function getReceivedAtAttribute()
    {
        return $this->timestamp;
    }
}
