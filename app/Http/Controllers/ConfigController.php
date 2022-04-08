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

use App\Models\AccessRights;
use App\Models\Country;
use App\Models\Geofence;
use App\Models\Vehicle;
use App\Support\Facades\Internationalisation;
use App\Transformers\AccessRightsTransformer;
use App\Transformers\NotificationsTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class ConfigController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $enabledCountries = Country::where("active", true)->get();

        $countries = [];

        foreach ($enabledCountries as $country) {
            $countries[$country->countryCode] = $country->name;
        }

        $accessRights = AccessRights::get();

        return $this->appendBody('config', [
            'access_rights'   =>
                fractal()
                    ->collection($accessRights, AccessRightsTransformer::class)
                    ->serializeWith($this->getSerializer())
                    ->toArray(),
            'api_url'         => Config::get('app.url'),
            'api_version'     => Config::get('app.apiVersion'),
            'api_debug'       => Config::get('app.debug'),
            'api_env'         => Config::get('app.env'),
            'api_locale'      => Config::get('app.locale'),
            'vehicle_states'  => [
                Vehicle::STATE_UNSET => Vehicle::STATE_UNSET_ID,
                Vehicle::STATE_SET   => Vehicle::STATE_SET_ID,
                Vehicle::STATE_ALT   => Vehicle::STATE_ALT_ID,
                Vehicle::STATE_ALM   => Vehicle::STATE_ALM_ID,
                Vehicle::STATE_UNSUB => Vehicle::STATE_UNSUB_ID,
                Vehicle::STATE_INST  => Vehicle::STATE_INST_ID,
                Vehicle::STATE_SLP   => Vehicle::STATE_SLP_ID,
            ],
            'geofence_types'  => [
                Geofence::TYPE_FIXED   => Geofence::TYPE_FIXED_ID,
                Geofence::TYPE_PLOT    => Geofence::TYPE_PLOT_ID,
                Geofence::TYPE_POLYGON => Geofence::TYPE_POLYGON_ID,
            ],
            'min_app_version' => 1,
            'countries'       => $countries,
        ])->respond();
    }

    public function timezonesAction(): JsonResponse
    {
        return $this->appendBody('timezones', Internationalisation::timezoneList())->respond();
    }
}
