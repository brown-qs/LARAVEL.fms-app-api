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
use App\Http\Traits\ValidationTrait;
use Closure;
use Illuminate\Http\Request;

/**
 * The middleware to validate routes
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class ValidationMiddleware
{
    use ApiResponseTrait, ValidationTrait;

    /**
     * The request object
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;


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
        $this->request = $request;

        $controller = $request->get('_controller');
        $action     = $request->get('_action');

        if (!is_null($controller) &&
            !is_null($action) &&
            !$this->validates($controller, $action)) {
            return $this->respondWithValidationErrors();
        }

        return $next($request);
    }

    /**
     * Responding with JSON shortcut.
     *
     * @return \Illuminate\Http\Response
     */
    public function respondWithValidationErrors()
    {
        $error = $this->getValidationBody();

        return $this->appendErrors($error['errors'])
                    ->setStatusCode($error['status'])
                    ->respond();
    }
}
