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

use App\Models\PendingCommand;
use App\Models\Vehicle;
use App\Models\VehicleJourney;
use App\Models\VehicleNote;
use App\Models\VehiclePosition;
use App\Transformers\VehicleJourneyTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * CommandController
 *
 * @package App\Http\Controllers
 * @author  Kirk
 */
class CommandController extends AbstractApiController
{

    /**
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function sendCommand(int $vehicleId): JsonResponse
    {
        if ($this->request->has('type')) {

            $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
                              ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
                              ->with('groups', 'latestJourney', 'unit')
                              ->first();

            if (!$vehicle) {
                return $this->respondWithNotFound('Vehicle not found');
            }

            $pendingCommand = new PendingCommand();

            //Available commands
            switch ($this->request->get('type')) {
                case 'ewm':
                    // ewm is only for STX71 units at the moment but this may change in the future
                    if (stripos($vehicle->unit->type, 'STX71') === false) {
                        return $this->respondWithInvalidRequest('Invalid request for unit type');
                    }

                    $state     = $this->request->get('status');
                    $smsNumber = $this->request->get('sms_number');
                    $pendingCommand->processEwm($vehicle, $state, $smsNumber, $this->request->get('user')->id);
                    break;


                default:
                    return $this->respondWithNotFound('Command type not recognised');
            }

            return $this->transformItem($vehicle, ['groups', 'latest_journey', 'latest_position', 'unit.subscription', 'pending_commands'])
                        ->respond();

        } else {
            return $this->respondWithInvalidRequest('Please provide command type');
        }
    }

    /*
     * @param int $vehicleId
     *
     * @return JsonResponse
     */
    public function cancelCommand(int $vehicleId): JsonResponse
    {
        if ($this->request->has('type')) {

            $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
                              ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
                              ->with('groups', 'latestJourney', 'unit')
                              ->first();

            if (!$vehicle) {
                return $this->respondWithNotFound('Vehicle not found');
            }

            $pendingCommand = new PendingCommand();

            //Available commands
            switch ($this->request->get('type')) {
                case 'ewm':
                    $pendingCommand->cancelCommand($vehicle, PendingCommand::CUSTSMS_ALT);
                    $pendingCommand->cancelCommand($vehicle, PendingCommand::CUSTSMS_MSG);
                    $pendingCommand->cancelCommand($vehicle, PendingCommand::CUSTSMS_PHN);
                    break;


                default:
                    return $this->respondWithNotFound('Command type not recognised');
            }

            return $this->transformItem($vehicle, ['groups', 'latest_journey', 'latest_position', 'unit.subscription', 'pending_commands'])
                        ->respond();


        } else {
            return $this->respondWithInvalidRequest('Please provide command type');
        }
    }


}
