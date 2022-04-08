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

use App\Models\VehicleGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * VehicleGroupController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleGroupController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $authUser = $this->request->get('user');

        if ($authUser->permittedGroups()->exists()) {
            $query = $authUser->permittedGroups();
        } else {
            $query = VehicleGroup::where('customerId', $authUser->customerId);
        }

        if ($this->request->has('search')) {
            $query->where('groupName', 'like', '%' . $this->request->get('search') . '%')
                  ->orWhere('groupDescription', 'like', '%' . $this->request->get('search') . '%');
        }

        $vehicleGroups = $query->orderBy('groupId', 'DESC')
                               ->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($vehicleGroups, null, "vehicle_groups")
                    ->respond();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function showAction(int $id): JsonResponse
    {
        $vehicleGroup = VehicleGroup::where('groupId', $id)
                                    ->where('customerId', $this->request->get('user')->customerId)
                                    ->with('vehicles')
                                    ->first();

        if (!$vehicleGroup) {
            return $this->respondWithNotFound('Vehicle group not found');
        }

        return $this->transformItem($vehicleGroup, 'vehicles.latest_position')
                    ->respond();
    }

    /**
     * @return JsonResponse
     */
    public function createAction(): JsonResponse
    {
        $name        = $this->request->get('group_name');
        $description = $this->request->get('group_description');
        $vehicleIds  = $this->request->get('vehicles');

        $vehicleGroup                   = new VehicleGroup();
        $vehicleGroup->customerId       = $this->request->get('user')->customerId;
        $vehicleGroup->groupName        = $name;
        $vehicleGroup->groupDescription = $description;
        $vehicleGroup->save();

        $vehicleGroup->vehicles()->attach($vehicleIds);
        $vehicleGroup = $vehicleGroup->load('vehicles');

        return $this->transformItem($vehicleGroup, 'vehicles.latest_position')
                    ->respond();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id)
    {
        $vehicleGroup = VehicleGroup::where('groupId', $id)
                      ->where('customerId', $this->request->get('user')->customerId)
                      ->first();

        if (!$vehicleGroup) {
            return $this->respondWithNotFound('Vehicle group not found');
        }

        // Clean AssignedVehicleGroup records
        $vehicleGroup->vehicles()->detach();
        
        $vehicleGroup->delete();

        return $this->respond();
    }

    /**
     * @param int $userId
     *
     * @return JsonResponse
     *
     */
    public function updateAction(int $id): JsonResponse
    {
        $vehicleGroup = VehicleGroup::where('groupId', $id)
                      ->where('customerId', $this->request->get('user')->customerId)
                      ->first();

        if (!$vehicleGroup) {
            return $this->respondWithNotFound('Vehicle group not found');
        }

        if ($this->request->has('group_name')) {
            $vehicleGroup->groupName = $this->request->get('group_name');
        }

        if ($this->request->has('group_description')) {
            $vehicleGroup->groupDescription = $this->request->get('group_description');
        }

        if ($this->request->has('vehicles')) {
            // Clean previous AssignedVehicleGroup records
            $vehicleGroup->vehicles()->detach();
            $vehicleGroup->vehicles()->attach($this->request->get('vehicles'));
            $vehicleGroup = $vehicleGroup->load('vehicles');
        }

        $vehicleGroup->save();

        return $this->transformItem($vehicleGroup)
                    ->respond();
    }
}
