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

use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use App\Support\Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * The api middleware to handle JWT
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class UserMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->get('user_id')) {
            if (Config::get('app.debug')) {
                return $next($request);
            }

            return $this->withRequest($request)
                        ->respondWithForbidden('Your credentials/token could not be authenticated');
        }

        $user = User::where('userId', $request->get('user_id'))
                    ->with('customer')
                    ->first();

        if (is_null($user)) {
            return $this->withRequest($request)
                        ->respondWithForbidden('Your credentials/token could not be authenticated');
        }

        if (!$user->active) {
            return $this->withRequest($request)
                        ->respondWithForbidden('Your user has been disabled');
        }

        if (Config::get('app.debug') !== "production" && Config::get('app.mockUserId')) {
            $user = User::where('userId', Config::get('app.mockUserId'))
                        ->with('customer')
                        ->first();
        }

        if ($request->get('is_api_token')) {
            $user->lastActive = Carbon::now();
            $user->save();
        }

        $request->attributes->add([
            'is_customer'  => true,
            'user'         => $user,
            'user_id'      => $user->id,
            'return_token' => new Auth(['user_id' => $user->id]),
        ]);

        return $next($request);
    }
}
