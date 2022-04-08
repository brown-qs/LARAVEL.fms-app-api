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
use Closure;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;

/**
 * The api middleware to handle JWT
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class JsonWebTokenMiddleware
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
        $auth_header = $request->headers->get('Authorization');

        if (!$auth_header) {
            if (config('app.debug')) {
                return $next($request);
            }

            return $this->withRequest($request)
                        ->respondWithForbidden('No token has been set');
        }

        $token = trim(base64_decode(trim(str_replace('Basic ', '', $auth_header))), ':');

        try {
            $decoded = json_decode(json_encode(JWT::decode($token, config('app.apiKey'), ['HS256'])), true);
            if (!isset($decoded['payload'])) {
                return $this->withRequest($request)
                            ->respondWithInvalidRequest('Payload not sent');
            }
        } catch (\Exception $e) {
            if ($e instanceof BeforeValidException) {
                $error = 'Token used before valid';
            } elseif ($e instanceof ExpiredException) {
                $error = 'Token Expired';
            } elseif ($e instanceof SignatureInvalidException) {
                $error = 'Token Signature Invalid';
            } else {
                $error = $e->getMessage();
            }

            return $this->withRequest($request)
                        ->respondWithForbidden([$error]);
        }

        foreach ($decoded['payload'] as $payloadKey => $payloadValue) {
            $request->attributes->add([$payloadKey => $payloadValue]);
        }

        $request->attributes->add(['jwt' => $token]);

        return $next($request);
    }
}
