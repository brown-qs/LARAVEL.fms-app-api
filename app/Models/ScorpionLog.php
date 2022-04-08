<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Driver
 *
 * @package App\Models
 */
class ScorpionLog extends Model
{
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
    protected $primaryKey = 'logId';

    /**
     * @param $userId
     * @param $customerId
     * @param $type
     * @param $action
     * @param $logType
     * @param $description
     * @param int $linkedId
     * @param int $linkedId2
     */
    public function logMessage($userId, $customerId, $type, $action, $logType, $description, $ip, $linkedId = 0, $linkedId2 = 0)
    {
        $log = new ScorpionLog();
        $log->userId = $userId;
        $log->customerId = $customerId;
        $log->type = $type;
        $log->action = $action;
        $log->description = $description;
        $log->logType = $logType;
        $log->linkedId = $linkedId;
        $log->linkedId2 = $linkedId2;
        $log->ip = $ip;
        $log->timestamp = Carbon::now();

        $log->save();
    }
}
