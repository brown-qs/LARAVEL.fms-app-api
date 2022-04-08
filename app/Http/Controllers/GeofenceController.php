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

use App\Models\Geofence;
use App\Models\GeofenceData;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * VehicleGroupController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class GeofenceController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $geofences = Geofence::where('customerId', $this->request->get('user')->customerId)
                             ->has('data')
                             ->with('data')
                             ->orderBy('geofenceId', 'desc')
                             ->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($geofences, ['geofence_data'], 'geofences')
                    ->respond();

        return redirect()->action();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function showAction(int $id): JsonResponse
    {
        $geofence = Geofence::where('customerId', $this->request->get('user')->customerId)
                            ->where('geofenceId', $id)
                            ->has('data')
                            ->with('data')
                            ->first();

        return $this->transformItem($geofence, ['geofence_data'])
                    ->respond();
    }

    public function createAction(): JsonResponse
    {
        $type = $this->request->get('type');

        if ($type === Geofence::TYPE_FIXED) {
            $position = $this->request->get('position');
            $radius   = $this->request->get('radius');

            if ($position === null ||
                !array_key_exists('lat', $position) ||
                !array_key_exists('lng', $position) ||
                $radius === null) {
                return $this->respondWithInvalidRequest('position, position.lat, position.lng and radius are required to create a fixed geofence');
            }

            $geofence              = new Geofence();
            $geofence->name        = $this->request->get('name');
            $geofence->customerId  = $this->request->get('user')->customerId;
            $geofence->lat         = $position['lat'];
            $geofence->lng         = $position['lng'];
            $geofence->radius      = $radius;
            $geofence->description = $this->request->get('description');
            $geofence->colour      = 'FF0000';
            $geofence->groupId     = 0;
            $geofence->type        = Geofence::TYPE_FIXED;
            $geofence->save();

            $geofence->groupId = $geofence->geofenceId;
            $geofence->save();

            $fixedPoly = sprintf("POINT(%s %s)", $position['lat'], $position['lng']);

            $geofenceData             = new GeofenceData();
            $geofenceData->geofenceId = $geofence->geofenceId;
            $geofenceData->radius     = $geofence->radius;
            $geofenceData->poly       = DB::raw(sprintf("ST_GeomFromText('%s')", $fixedPoly));
            $geofenceData->save();

            $geofence->with('data');

            return $this->transformItem($geofence, ['geofence_data'])
                        ->respond();
        } else {
            $positions = $this->request->get('positions');

            if (count($positions) <= 2) {
                return $this->respondWithInvalidRequest('Require more than 2 positions/points');
            }

            $geofence              = new Geofence();
            $geofence->name        = $this->request->get('name');
            $geofence->customerId  = $this->request->get('user')->customerId;
            $geofence->lat         = $positions[0]['lat'];
            $geofence->lng         = $positions[0]['lng'];
            $geofence->radius      = 0;
            $geofence->description = $this->request->get('description');
            $geofence->colour      = 'FF0000';
            $geofence->groupId     = 0;
            $geofence->type        = Geofence::TYPE_POLYGON;
            $geofence->save();

            $geofence->groupId = $geofence->geofenceId;
            $geofence->save();

            $polygonPoly = "POLYGON((%s))";
            $polyPoints  = [];

            foreach ($positions as $position) {
                $polyPoints[] = sprintf('%s %s', $position['lat'], $position['lng']);
            }

            // have to end the polygon with the first point
            $polyPoints[] = sprintf('%s %s', $positions[0]['lat'], $positions[0]['lng']);

            $polygonPoly = sprintf($polygonPoly, implode(",", $polyPoints));

            $geofenceData             = new GeofenceData();
            $geofenceData->geofenceId = $geofence->geofenceId;
            $geofenceData->radius     = $geofence->radius;
            $geofenceData->poly       = DB::raw(sprintf("ST_GeomFromText('%s')", $polygonPoly));
            $geofenceData->save();

            $geofence->with('data');

            return $this->transformItem($geofence, ['geofence_data'])
                        ->respond();
        }
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateAction(int $id): JsonResponse
    {
        $geofence = Geofence::where('geofenceId', $id)
                            ->where('customerId', $this->request->get('user')->customerId)
                            ->first();

        if (!$geofence) {
            return $this->respondWithNotFound('Geofence not found');
        }

        if ($geofence->type === Geofence::TYPE_FIXED) {
            $position = $this->request->get('position');

            if (!is_null($position) &&
                (!array_key_exists('lat', $position) ||
                    !array_key_exists('lng', $position))) {
                return $this->respondWithInvalidRequest("both position.lat and position.lng are required to update a fixed geofence");
            }

            if ($this->request->get('name')) {
                $geofence->name = $this->request->get('name');
            }

            if ($this->request->get('radius')) {
                $geofence->radius = $this->request->get('radius');
            }

            if ($this->request->get('description')) {
                $geofence->description = $this->request->get('description');
            }

            if (!is_null($position)) {
                $geofence->lat = $position['lat'];
                $geofence->lng = $position['lng'];
            }

            $geofence->save();

            if (!is_null($position)) {
                $fixedPoly = sprintf("POINT(%s %s)", $position['lat'], $position['lng']);

                $geofenceData = GeofenceData::where('geofenceId', $id)->first();

                if (!count($geofenceData)) {
                    return $this->respondWithNotFound('Geofence not found');
                }

                $geofenceData->radius = $geofence->radius;
                $geofenceData->poly   = DB::raw(sprintf("ST_GeomFromText('%s')", $fixedPoly));
                $geofenceData->save();
            }

            $geofence->with('data');

            return $this->transformItem($geofence, ['geofence_data'])
                        ->respond();
        } else {
            $positions = $this->request->get('positions');

            if (!is_null($positions) && count($positions) <= 2) {
                return $this->respondWithInvalidRequest('Require more than 2 positions/points');
            }

            if ($this->request->get('name')) {
                $geofence->name = $this->request->get('name');
            }

            if ($this->request->get('description')) {
                $geofence->description = $this->request->get('description');
            }

            if (!is_null($positions)) {
                $geofence->lat = $positions[0]['lat'];
                $geofence->lng = $positions[0]['lng'];
            }

            $geofence->save();

            if (!is_null($positions)) {
                $polygonPoly = "POLYGON((%s))";
                $polyPoints  = [];

                foreach ($positions as $position) {
                    $polyPoints[] = sprintf('%s %s', $position['lat'], $position['lng']);
                }

                // have to end the polygon with the first point
                $polyPoints[] = sprintf('%s %s', $positions[0]['lat'], $positions[0]['lng']);
                $polygonPoly  = sprintf($polygonPoly, implode(",", $polyPoints));

                $geofenceData = GeofenceData::where('geofenceId', $id)->first();

                if (!count($geofenceData)) {
                    return $this->respondWithNotFound('Geofence not found');
                }

                $geofenceData->poly = DB::raw(sprintf("ST_GeomFromText('%s')", $polygonPoly));
                $geofenceData->save();
            }

            $geofence->with('data');

            return $this->transformItem($geofence, ['geofence_data'])
                        ->respond();
        }
    }

    /**
     * @return JsonResponse
     */
    public function lookupAction(): JsonResponse
    {
        if (!$this->request->get('lat') || !$this->request->get('lng')) {
            return $this->respondWithInvalidRequest('Lat and lng are required for geofence lookup');
        }

        $polyGeofences = Geofence::where('customerId', $this->request->get('user')->customerId)
                                 ->where('type', 'polygon')
                                 ->whereHas('data', function ($q) {
                                     $q->whereRaw("ST_CONTAINS(poly, POINT(?, ?))", [
                                         $this->request->get('lat'),
                                         $this->request->get('lng'),
                                     ]);
                                 })
                                 ->with('data')
                                 ->get();

        $fixedGeofences = Geofence::where('customerId', $this->request->get('user')->customerId)
                                  ->where('type', 'fixed')
                                  ->whereHas('data', function ($q) {
                                      $q->whereRaw("ST_DISTANCE(poly, POINT(?, ?)) * 1000 < radius / 100", [
                                          $this->request->get('lat'),
                                          $this->request->get('lng'),
                                      ]);
                                  })
                                  ->with('data')
                                  ->get();

        $polyGeofences = $polyGeofences->merge($fixedGeofences);

        return $this->transformCollection($polyGeofences, ['geofence_data'], 'geofences')
                    ->respond();
    }
}
