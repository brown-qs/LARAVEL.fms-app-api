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

namespace App\Http\Middleware;

use App\Support\Facades\Bench;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * The api middleware to get the controller and action names
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class ApiRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->attributes->add(['request_id' => dashHash()]);

        // Is app in maintenance?
        if (file_exists(storage_path() . '/framework/maintenance') &&
            !in_array($this->getIp(), Config::get('app.maintenanceAllowedIps', []))
        ) {
            return response()->json(['status' => 503, 'status_desc' => 'Down for maintenance'], 503);
        }


        // Add controller and action name to request bag
        if (!is_null($request->route()) && array_key_exists('uses', $request->route()[1])) {
            $req = explode('\\', $request->route()[1]['uses']);

            if ($req) {
                $req = $req[count($req) - 1];
                list($controller, $action) = explode('@', $req, 2);

                $controller = lcfirst(str_replace('Controller', '', $controller));
                $action     = lcfirst(str_replace('Action', '', $action));

                $request->attributes->add([
                    '_controller' => $controller,
                    '_action'     => $action,
                ]);

                $request->attributes->add($request->route()[2]);
            }
        }

        // Debugging stuff
        if (($request->get('debug') && Config::get('app.allowDebugParameter', false)) ||
            Config::get('app.forceShowDebug', false)) {
            Config::set('app.showDebug', true);
        }

        //Config::set('api.debug', true);
        Bench::start();

        return $next($request);
    }

    /**
     * Get IP of the user
     *
     * @return mixed
     */
    private function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
