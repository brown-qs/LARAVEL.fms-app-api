<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserDevice
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Log extends Model
{
    //ACTIONS: CUSTOMER

    const LOG_CREATE   = "CREATE";
    const LOG_VIEW     = "VIEW";
    const LOG_EDIT     = "EDIT";
    const LOG_DELETE   = "DELETE";
    const LOG_ASSIGN   = "ASSIGN";
    const LOG_UPLOAD   = "UPLOAD";
    const LOG_LOGIN    = "LOGIN";
    const LOG_LOGOUT   = "LOGOUT";
    const LOG_GENERATE = "GENERATE";
    const LOG_DISMISS  = "DISMISS";


    //ACTIONS: ADMIN
    const LOG_DISABLE              = "DISABLE";
    const LOG_ADD                  = "ADD";
    const LOG_ASSIGN_TO_CUSTOMER   = "ASSIGN (TO CUSTOMER)";
    const LOG_ASSIGN_TO_DEALERSHIP = "ASSIGN (TO DEALERSHIP)";
    const LOG_ASSIGN_TO_SCORPION   = "ASSIGN (TO SCORPION)";
    const LOG_MARK_READ            = "MARK READ";
    const LOG_RESET                = "RESET";
    const LOG_REMOVE               = "REMOVE";
    const LOG_REINSTALL            = "REINSTALL";
    const LOG_REASSIGN             = "REASSIGN";
    const LOG_SEND                 = "SEND";
    const LOG_TXTTOPUP             = "TXTTOPUP";

    //ACTIONS: FLAGS
    const LOG_ZERO_SPEED_ENABLED   = "ZERO SPEED ENABLED";
    const LOG_ZERO_SPEED_DISABLED   = "ZERO SPEED DISABLED";
    const LOG_PRIVACY_MODE_ENABLED   = "PRIVACY MODE ENABLED";
    const LOG_PRIVACY_MODE_DISABLED   = "PRIVACY MODE DISABLED";

    //TYPES: CUSTOMER
    const TYPE_LIVE_MAP         = "LIVE MAP";
    const TYPE_GRAPH            = "GRAPH";
    const TYPE_VEHICLE          = "VEHICLE";
    const TYPE_GROUP            = "GROUP";
    const TYPE_ALERT            = "ALERT";
    const TYPE_TRIGGERED_ALERT  = "TRIGGERED ALERT";
    const TYPE_GEOFENCE         = "GEOFENCE";
    const TYPE_DRIVER           = "DRIVER";
    const TYPE_USER             = "USER";
    const TYPE_USER_PERMISSIONS = "USER PERMISSIONS";
    const TYPE_HEALTH           = "HEALTH";
    const TYPE_UNIT             = "UNIT";
    const TYPE_PREFERENCE       = "PREFERENCE";
    const TYPE_NOTE             = "NOTE";
    const TYPE_MY_ACCOUNT       = "MY ACCOUNT";
    const TYPE_MY_DETAILS       = "MY DETAILS";
    const TYPE_COMPANY_DETAILS  = "COMPANY DETAILS";
    const TYPE_PASSWORD         = "PASSWORD";
    const TYPE_CONTACT_US       = "CONTACT US";
    const TYPE_FLAGGED_JOURNEY  = "FLAGGED JOURNEY";
    const TYPE_TYPE             = "TYPE"; //used for login/logut
    const TYPE_REPORT           = "REPORT";
    const TYPE_SCHEDULED_REPORT = "SCHEDULED REPORT";


    //TYPES: ADMIn
    const TYPE_CUSTOMER              = "CUSTOMER"; //
    const TYPE_CUSTOMER_FEATURES     = "CUSTOMER FEATURES"; //
    const TYPE_CUSTOMER_TO_VEHICLE   = "CUSTOMER TO VEHICLE"; //
    const TYPE_CUSTOMER_FROM_VEHICLE = "CUSTOMER FROM VEHICLE"; //
    const TYPE_LOGIN_AS              = "LOGIN AS"; //
    const TYPE_CUSTOMER_UNIT         = "CUSTOMER UNIT"; //
    const TYPE_CUSTOMER_UNITS        = "CUSTOMER UNITS"; //
    const TYPE_UNITS                 = "UNITS";
    const TYPE_DEALERSHIP_UNIT       = "DEALERSHIP UNIT"; //
    const TYPE_DEALERSHIP_UNITS      = "DEALERSHIP UNITS"; //
    const TYPE_DEALERSHIP            = "DEALERSHIP"; //
    const TYPE_SUPPORT_CONTACT       = "SUPPORT CONTACT"; //
    const TYPE_SUBSCRIPTION          = "SUBSCRIPTION"; //
    const TYPE_FITTER                = "FITTER"; //
    const TYPE_VEHICLE_COMMAND       = "VEHICLE COMMAND"; //


    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Log';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = null;

    public $incrementing = false;

    public function log(User $user, $action, $type, $linkedId = 0, $linkedId2 = 0, $linkedReference = "")
    {
        /** GET THE USERS IP * */
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (isset($_SERVER["REMOTE_ADDR"])) {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        }

        $customerId   = $user->customerId ?? false;
        $dealershipId = $user->dealershipId ?? false;

        if ($dealershipId) {
            $logType = "Admin";
        }
        else {
            if ($customerId) {
                $logType = "Customer";
            } else {
                $logType = "Admin";
            }
        }

        $log = new Log();
        $log->userId = $user->userId;
        $log->customerId = $customerId;
        $log->dealershipId = $dealershipId;
        $log->linkedId = $linkedId;
        $log->linkedId2 = $linkedId2;
        $log->ip = $ip;
        $log->timestamp = Carbon::now('UTC')->format('Y-m-d H:i:s');
        $log->action = $action;
        $log->type = $type;
        $log->description = $linkedReference;
        $log->logType = $logType;

        return $log->saveOrFail();
    }

}
