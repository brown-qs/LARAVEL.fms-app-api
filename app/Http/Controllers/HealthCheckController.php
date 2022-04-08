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


use App\Models\HealthCheck;
use Illuminate\Support\Facades\Config;

/**
 * Class HealthCheckController
 *
 * @package App\Http\Controllers
 * @author  Luke Vincent <luke@hare.digital>
 */
class HealthCheckController extends AbstractApiController
{
    /**
     * Get a list of health checks for the given vehicle.
     *
     * @param $vehicleId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByVehicleAction($vehicleId)
    {
        $limit = $this->request->get('limit');

        $query = HealthCheck::where('vehicleId', $vehicleId)->orderBy('timestamp', 'desc');

        // allow request to bypass pagination if limit set to 0
        if (isset($limit) && (int)$limit === 0) {
            $healthChecks = $query->get();
        } else {
            $limit = empty($limit) ? Config::get('app.paginateDefault') : $limit;

            $healthChecks = $query->paginate($limit);
        }

        return $this->transformCollection($healthChecks, null, 'health_checks')->respond();
    }
}
