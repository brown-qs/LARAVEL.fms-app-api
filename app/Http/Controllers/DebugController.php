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

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class DebugController extends AbstractApiController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function configAction(): JsonResponse
    {
        return $this->appendBody('data', [
            'api_url'     => Config::get('app.url'),
            'api_version' => Config::get('app.apiVersion'),
            'api_debug'   => Config::get('app.debug'),
            'api_env'     => Config::get('app.env'),
            'api_locale'  => Config::get('app.locale'),
            'version'     => Config::get('app.apiVersion'),
            'debug'       => Config::get('app.debug'),
            'environment' => Config::get('app.env'),
        ])->respond();
    }
}
