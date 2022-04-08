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

namespace App\Http\Controllers;

use App\Models\AlertEvent;
use App\Support\Traits\BelongsToCustomerValidationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * AlertController
 *
 * @package App\Http\Controllers
 * @author  Tariq Tamuji <tariq@hare.digital>
 */
class AlertEventController extends AbstractApiController
{

    use BelongsToCustomerValidationTrait;

    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $includeRead = $this->request->get('include_read', false);

        $query = AlertEvent::where('customerId', $this->request->get('user')->customerId)
                           ->with('alert');

        if (!$includeRead) {
            $query->where("markRead", false);
        }

        $alertEvents = $query->orderBy('timestamp', 'DESC')
                             ->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($alertEvents, 'alert', 'alert_events')
                    ->respond();
    }

    /**
     * @param int $alertEventId
     *
     * @return JsonResponse
     */
    public function showAction(int $alertEventId): JsonResponse
    {
        $alert = AlertEvent::where('alertEventId', $alertEventId)
                           ->with('alert')
                           ->where('customerId', $this->request->get('user')->customerId)
                           ->first();

        if (!$alert) {
            return $this->respondWithNotFound('Alert Event not found');
        }

        return $this->transformItem($alert, ['position', 'alert'])
                    ->respond();
    }

    /**
     * @return JsonResponse
     */
    public function markAsReadAction(): JsonResponse
    {
        // Check for bad ids
        $unownedAlertEvents = AlertEvent::whereIn("alertEventId", $this->request->get('alert_event_ids'))
                                        ->where('customerId', "!=", $this->request->get('user')->customerId)
                                        ->get();

        if ($unownedAlertEvents->count()) {
            $this->respondWithError(
                403,
                sprintf(
                    "Passed Alert Event IDs do not belong to User : [%s]",
                    $unownedAlertEvents->implode("alertEventId", ",")
                )
            );
        }

        // Update the Alert Events
        AlertEvent::whereIn("alertEventId", $this->request->get('alert_event_ids'))
                  ->update(["markRead" => true]);

        // Return success response
        return $this->respond();
    }

}
