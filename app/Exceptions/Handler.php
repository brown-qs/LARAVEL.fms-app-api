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

namespace App\Exceptions;

use App\Http\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The base exception handler
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (!Config::get('app.debug', false)) {
            $status_code = (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500;

            if ($e->getMessage()) {
                $this->appendErrors($this->getFormattedMessage($e));
            } elseif ($status_code === 405 || $status_code === 404) {
                $this->appendErrors(sprintf(
                        '%s %s route not defined',
                        $request->getMethod(),
                        $request->getRequestUri()
                    )
                );
            }

            return $this->withRequest($request)
                        ->setStatusCode($status_code)
                        ->respond();
        }

        return parent::render($request, $e);
    }

    /**
     * Render an exception into a string
     *
     * @param  \Exception $e
     *
     * @return string
     */
    private function getFormattedMessage($e): string
    {
        $error = $e->getMessage();

        if (method_exists($e, 'getLine') && $e->getLine()) {
            $error = $error . ' at line: ' . $e->getLine();
        }

        if (method_exists($e, 'getFile') && $e->getFile()) {
            $error = $error . ' in file: ' . $e->getFile();
        }
        return $error;
    }
}
