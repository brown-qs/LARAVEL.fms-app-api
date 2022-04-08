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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;

/**
 * Class PendingCommand
 *
 * @package App\Models
 * @author  Kirk
 */
class PendingCommand extends Model
{

    //Avaliable commands that can be sent to the server
    //Most of these command will not be used yet, but are here for future use
    public const ALARM                      = 1;
    public const CLEAR                      = 2;
    public const GEOFENCE_EXCEPTION         = 3;
    public const CLEAR_GEOFENCE_EXCEPTION   = 4;
    public const GEOFENCE_IGNITION_ON_ALARM = 5;
    public const CLEAR_GEOFENCE_IGNITION_ON = 6;
    public const HEALTH_CHECK               = 7;
    public const REPORT                     = 8;
    public const UNSUBSCRIBE                = 9;

    public const SERVER1 = 10;
    public const SERVER2 = 11;

    public const SMS       = 12;
    public const CLEAR_VBL = 13;
    public const CLEAR_BBF = 14;

    public const REDIRECT = 15;

    //new commands
    public const GFEXC2    = 16;
    public const GFIGN2    = 17;
    public const INSTALL   = 18;
    public const FACTORY   = 19;
    public const NONMON    = 20;
    public const FMS       = 21;
    public const FMSCONFIG = 22;
    public const AUXIN     = 23;
    public const AUXOUT    = 24;
    public const POWER     = 25;

    //driver recog
    public const DRVREC = 26;
    public const ADDTAG = 27;
    public const RMVTAG = 28;


    public const UPGRADE = 30;

    public const CUSTSMS_PHN = 31;
    public const CUSTSMS_ALT = 33;
    public const CUSTSMS_ADD = 34;
    public const CUSTSMS_CHG = 35;
    public const CUSTSMS_CLR = 36;
    public const CUSTSMS_REG = 37;
    public const CUSTSMS_MSG = 38;

    public const AUX_0 = 40;
    public const AUX_1 = 41;
    public const AUX_2 = 42;
    public const AUX_3 = 43;

    //new clear
    public const CLEAR_AUX = 50;

    public const RAW = 999;

    public const CUSTSMS_ALT_VBL        = 1;
    public const CUSTSMS_ALT_VPI        = 2;
    public const CUSTSMS_ALT_VPR        = 4;
    public const CUSTSMS_ALT_IPI        = 8;
    public const CUSTSMS_ALT_EMW        = 16;
    public const CUSTSMS_ALT_MWG_SMS    = 32; // mwg SMS alt
    public const CUSTSMS_ALT_IOV        = 64;
    public const CUSTSMS_ALT_GFV_CONT   = 128; // continous check
    public const CUSTSMS_ALT_DRE        = 256;
    public const CUSTSMS_ALT_GJA        = 512;
    public const CUSTSMS_ALT_BBF        = 1024;
    public const CUSTSMS_ALT_GFV_HOUR   = 2048; // hourly check
    public const CUSTSMS_ALT_MWG_SERVER = 4096;

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'PendingCommand';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'timestamp',
        'deletedTimestamp',
    ];

    /*
     * Functions Start
     */

    public static function getFriendlyName($command)
    {
        switch ($command) {
            case PendingCommand::CUSTSMS_ALT:
                return 'EWM';
                break;

            default:
                return 'UNSUPPORTED';
                break;
        }
    }

    public static function getFriendlyValue($command, $value)
    {
        switch ($command) {
            case PendingCommand::CUSTSMS_ALT:
                return (bool)(((int)hexdec(substr((STRING)$value, 0, 4)) & PendingCommand::CUSTSMS_ALT_EMW) == PendingCommand::CUSTSMS_ALT_EMW);
                break;

            default:
                return null;
                break;
        }
    }


    //Static Functions

    /**
     * @param $vehicle Vehicle
     * @param $unitId  int
     *
     * @return bool|null
     */
    public static function testEwmStatus($vehicle, $unitId)
    {
        // smsAlertStatus
        if ($unitId === null || is_null($vehicle)) {
            return null;
        }

        if (is_null($vehicle->unit)) {
            $vehicle->unit = Unit::where('unitId', $unitId)->first();
        }

        if (!is_null($vehicle->unit) && (stripos($vehicle->unit->type, "stx7") !== false ||  stripos($vehicle->unit->type, "stm") !== false ) ) {

            return (bool)(((int)hexdec(substr((STRING)$vehicle->smsAlertStatus, 0, 4)) &
                    PendingCommand::CUSTSMS_ALT_EMW) == PendingCommand::CUSTSMS_ALT_EMW);
        } else {
            return null;
        }
    }

    public function cancelCommand($vehicle, $command)
    {
        PendingCommand::where('vehicleId', $vehicle->vehicleId)
                      ->where('status', 'pending')
                      ->where('command', $command)
                      ->delete();
    }

    public function processEwm($vehicle, $state, $smsNumber, $userId = 0)
    {
        // check it's an STX71 unit, vehicle type is user defined and shouldn't be used as a check.
        $isSTX71Unit = stripos($vehicle->unit->type, 'STX71') !== false;

        if ($state === true) {
            $msg    = 'EWM ON';
            $status = '00100000';

            if ($isSTX71Unit) {
                $status = '10100000';
            }
        } else {
            $msg    = 'EWM OFF';
            $status = '00000000';

            if ($isSTX71Unit) {
                $status = '10000000';
            }
        }

        PendingCommand::where('vehicleId', $vehicle->vehicleId)
                      ->where('status', 'pending')
                      ->where(function ($query) {
                          $query->where('command', PendingCommand::CUSTSMS_ALT)
                                ->orWhere('command', PendingCommand::CUSTSMS_MSG)
                                ->orWhere('command', PendingCommand::CUSTSMS_PHN);
                      })
                      ->delete();

        $this->storePendingCommand(
            $vehicle->vehicleId,
            PendingCommand::CUSTSMS_ALT,
            substr($status, 0, 4),
            $userId
        );

        $this->storePendingCommand(
            $vehicle->vehicleId,
            PendingCommand::CUSTSMS_MSG,
            $msg,
            $userId
        );

        $this->storePendingCommand(
            $vehicle->vehicleId,
            PendingCommand::CUSTSMS_PHN,
            $smsNumber,
            $userId
        );
    }

    public function setEWMText($vehicle, $countryCode = 'en', $userId = 0)
    {
        $smsText = Lang::get('sms.wmv', [], $countryCode);

        PendingCommand::where('vehicleId', $vehicle->vehicleId)
            ->where('command', PendingCommand::RAW)
            ->where('status', 'pending')
            ->where('commandValue', 'like', 'set emwtext %')
            ->delete();

        $this->storePendingCommand(
            $vehicle->vehicleId,
            PendingCommand::RAW,
            'set emwtext ' . $smsText,
            $userId
        );
    }

    /*
     * Functions End
     */

    /*
     * @param $vehicleId
     * @param $command
     * @param $value
     *
     * Private function to save command to pendingCommand table
     */

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        //Supported commands to report
        $supportedCommands = [
            PendingCommand::CUSTSMS_ALT,
        ];

        return $query->where('status', 'pending')
                     ->whereIn('command', $supportedCommands);
    }


    /*
     * Scopes
     */

    public function storePendingCommand($vehicleId, $command, $value, $userId = 0)
    {
        $pendingCommand               = new PendingCommand();
        $pendingCommand->created      = Carbon::now();
        $pendingCommand->vehicleId    = $vehicleId;
        $pendingCommand->command      = $command;
        $pendingCommand->commandValue = $value;
        $pendingCommand->status       = 'pending';


        if ($userId) {
            $pendingCommand->userId = $userId;
        } else if(Auth::id()) {
            $pendingCommand->userId = Auth::id();
        }

        $pendingCommand->save();
    }

}
