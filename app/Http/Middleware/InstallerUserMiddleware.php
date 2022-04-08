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
use Closure;
use Illuminate\Http\Request;

/**
 * The api middleware to handle JWT
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class InstallerUserMiddleware
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
        if ($request->get('user')->type !== User::USER_TYPE_FITTER &&
            $request->get('user')->type !== User::USER_TYPE_DEALER) {
            return $this->withRequest($request)
                        ->respondWithForbidden("You don't have sufficient permission to view this.");
        }

        $request->get('user')->customerId = $request->get('customerId');
        $request->attributes->set('is_customer', false);

        return $next($request);
    }
}
