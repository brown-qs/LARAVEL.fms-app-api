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

use App\Models\Alert;
use App\Models\AlertEvent;
use App\Support\Facades\Internationalisation;
use App\Support\Traits\BelongsToCustomerValidationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * AlertController
 *
 * @package App\Http\Controllers
 * @author  Tariq Tamuji <tariq@hare.digital>
 */
class AlertController extends AbstractApiController
{

    use BelongsToCustomerValidationTrait;

    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $alerts = Alert::where('customerId', $this->request->get('user')->customerId)
                       ->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($alerts, null, 'alerts')
                    ->respond();
    }

    /**
     * @param int $alertId
     *
     * @return JsonResponse
     */
    public function showAction(int $alertId): JsonResponse
    {
        $alert = Alert::where('alertId', $alertId)
                      ->where('customerId', $this->request->get('user')->customerId)
                      ->first();

        if (!$alert) {
            return $this->respondWithNotFound('Alert not found');
        }

        return $this->transformItem($alert)
                    ->respond();
    }

    public function createAction(): JsonResponse
    {
        // Construct the new alert model
        $newAlert = new Alert();

        // Set the mandatory fields
        $newAlert->customerId = $this->request->get('user')->customerId;

        $newAlert->name  = $this->request->get('name');
        $newAlert->type  = $this->request->get('type');
        $newAlert->level = $this->request->get('level');

        $newAlert->timezone       = $this->request->get('timezone');
        $newAlert->sundayStart    = $this->request->get('sunday_start');
        $newAlert->sundayEnd      = $this->request->get('sunday_end');
        $newAlert->mondayStart    = $this->request->get('monday_start');
        $newAlert->mondayEnd      = $this->request->get('monday_end');
        $newAlert->tuesdayStart   = $this->request->get('tuesday_start');
        $newAlert->tuesdayEnd     = $this->request->get('tuesday_end');
        $newAlert->wednesdayStart = $this->request->get('wednesday_start');
        $newAlert->wednesdayEnd   = $this->request->get('wednesday_end');
        $newAlert->thursdayStart  = $this->request->get('thursday_start');
        $newAlert->thursdayEnd    = $this->request->get('thursday_end');
        $newAlert->fridayStart    = $this->request->get('friday_start');
        $newAlert->fridayEnd      = $this->request->get('friday_end');
        $newAlert->saturdayStart  = $this->request->get('saturday_start');
        $newAlert->saturdayEnd    = $this->request->get('saturday_end');

        // Populate the optional/conditional fields
        if ($this->request->has('vehicle_id')) {
            $vehicleId = intval($this->request->get('vehicle_id'));

            if ($vehicleId > 0) {
                if ($this->modelBelongsToCustomer("Vehicle", "vehicleId", $this->request->get("vehicle_id"))) {
                    $newAlert->vehicleId = $this->request->get("vehicle_id");
                } else {
                    $this->respondWithError(422, "Vehicle ID provided does not belong to logged in User",
                        "Validation Error");
                }
            } else {
                $newAlert->vehicleId = 0;
            }
        }

        if ($this->request->has('group_id')) {
            $groupId = intval($this->request->get('group_id'));

            if ($groupId > 0) {
                if ($this->modelBelongsToCustomer("VehicleGroup", "groupId", $this->request->get("group_id"))) {
                    $newAlert->groupId = $this->request->get("group_id");
                } else {
                    $this->respondWithError(422, "Vehicle Group ID provided does not belong to logged in User",
                        "Validation Error");
                }
            } else {
                $newAlert->groupId = 0;
            }
        }

        if ($this->request->has('description')) {
            $newAlert->description = $this->request->get("description");
        }

        if ($this->request->has('email')) {
            $newAlert->email = $this->request->get("email");
        }

        if ($this->request->has('txt')) {
            $newAlert->txt = $this->request->get("txt");
        }

        if ($this->request->has('aux_id')) {
            $newAlert->auxId = $this->request->get("aux_id");
        }

        if ($this->request->has('speed_limit')) {
            $newAlert->speedLimit = Internationalisation::convertKilometersToMiles($this->request->get("speed_limit"));
        }

        if ($this->request->has('idle_limit')) {
            $newAlert->idleLimit = $this->request->get("idle_limit");
        }

        if ($this->request->has('engine_limit')) {
            $newAlert->engineLimit = $this->request->get("engine_limit");
        }

        if ($this->request->has('geofence_id')) {
            if ($this->modelBelongsToCustomer("Geofence", "geofenceId", $this->request->get("geofence_id"))) {
                $newAlert->geofenceId = $this->request->get("geofence_id");
            } else {
                $this->respondWithError(422, "Geofence ID provided does not belong to logged in User",
                    "Validation Error");
            }
        }

        // Save the alert
        $newAlert->save();

        // Return the newly constructed Alert
        return $this->transformItem($newAlert)
                    ->respond();
    }

    public function editAction(int $alertId): JsonResponse
    {
        $alert = Alert::where('alertId', $alertId)
                      ->where('customerId', $this->request->get('user')->customerId)
                      ->first();

        if (!$alert) {
            return $this->respondWithNotFound('Alert not found');
        }

        // Only the Type is mandatory when updating
        $alert->type = $this->request->get('type');

        // Check conditional fields
        if ($this->request->has('vehicle_id')) {
            $vehicleId = intval($this->request->get('vehicle_id'));

            if ($vehicleId > 0) {
                if ($this->modelBelongsToCustomer("Vehicle", "vehicleId", $this->request->get("vehicle_id"))) {
                    $alert->vehicleId = $this->request->get('vehicle_id');
                } else {
                    $this->respondWithError(422, "Vehicle ID provided does not belong to logged in User",
                        "Validation Error");
                }
            } else {
                $alert->vehicleId = 0;
            }

        }

        if ($this->request->has('group_id')) {
            $groupId = intval($this->request->get('group_id'));

            if ($groupId > 0) {
                if ($this->modelBelongsToCustomer("VehicleGroup", "groupId", $this->request->get("group_id"))) {
                    $alert->groupId = $this->request->get("group_id");
                } else {
                    $this->respondWithError(422, "Vehicle Group ID provided does not belong to logged in User",
                        "Validation Error");
                }
            } else {
                $alert->groupId = 0;
            }
        }

        if ($this->request->has('geofence_id')) {
            if ($this->modelBelongsToCustomer("Geofence", "geofenceId", $this->request->get("geofence_id"))) {
                $alert->geofence_id = $this->request->get('geofence_id');
            } else {
                $this->respondWithError(422, "Geofence ID provided does not belong to logged in User",
                    "Validation Error");
            }
        }

        // Update optional fields

        if ($this->request->has('name')) {
            $alert->name = $this->request->get('name');
        }

        if ($this->request->has('description')) {
            $alert->description = $this->request->get('description');
        }

        if ($this->request->has('level')) {
            $alert->level = $this->request->get('level');
        }

        if ($this->request->has('email')) {
            $alert->email = $this->request->get('email');
        }

        if ($this->request->has('txt')) {
            $alert->txt = $this->request->get('txt');
        }

        if ($this->request->has('aux_id')) {
            $alert->auxId = $this->request->get('aux_id');
        }

        if ($this->request->has('speed_limit')) {
            $alert->speedLimit = Internationalisation::convertKilometersToMiles($this->request->get("speed_limit"));
        }

        if ($this->request->has('idle_limit')) {
            $alert->idleLimit = $this->request->get('idle_limit');
        }

        if ($this->request->has('engine_limit')) {
            $alert->engineLimit = $this->request->get('engine_limit');
        }

        if ($this->request->has('sunday_start')) {
            $alert->sundayStart = $this->request->get('sunday_start');
        }

        if ($this->request->has('sunday_end')) {
            $alert->sundayEnd = $this->request->get('sunday_end');
        }

        if ($this->request->has('monday_start')) {
            $alert->mondayStart = $this->request->get('monday_start');
        }

        if ($this->request->has('monday_end')) {
            $alert->mondayEnd = $this->request->get('monday_end');
        }

        if ($this->request->has('tuesday_start')) {
            $alert->tuesdayStart = $this->request->get('tuesday_start');
        }

        if ($this->request->has('tuesday_end')) {
            $alert->tuesdayEnd = $this->request->get('tuesday_end');
        }

        if ($this->request->has('wednesday_start')) {
            $alert->wednesdayStart = $this->request->get('wednesday_start');
        }

        if ($this->request->has('wednesday_end')) {
            $alert->wednesdayEnd = $this->request->get('wednesday_end');
        }

        if ($this->request->has('thursday_start')) {
            $alert->thursdayStart = $this->request->get('thursday_start');
        }

        if ($this->request->has('thursday_end')) {
            $alert->thursdayEnd = $this->request->get('thursday_end');
        }

        if ($this->request->has('friday_start')) {
            $alert->fridayStart = $this->request->get('friday_start');
        }

        if ($this->request->has('friday_end')) {
            $alert->fridayEnd = $this->request->get('friday_end');
        }

        if ($this->request->has('saturday_start')) {
            $alert->saturdayStart = $this->request->get('saturday_start');
        }

        if ($this->request->has('saturday_end')) {
            $alert->saturdayEnd = $this->request->get('saturday_end');
        }

        if ($this->request->has('timezone')) {
            $alert->timezone = $this->request->get('timezone');
        }

        // Save the alert
        $alert->save();

        // Return the updated Alert
        return $this->transformItem($alert)
                    ->respond();

    }

    public function deleteAction(int $alertId)
    {
        $alert = Alert::where('alertId', $alertId)
                      ->where('customerId', $this->request->get('user')->customerId)
                      ->first();

        if (!$alert) {
            return $this->respondWithNotFound('Alert not found');
        }

        // Delete all associated alert events
        AlertEvent::where("alertId", $alert->alertId)->delete();

        // Delete the alert
        $alert->delete();

        // Return success response
        return $this->respond();
    }
}
