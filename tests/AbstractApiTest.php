<?php

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

use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The base api test class
 *
 * @author James Wallen-Jones <james@jamosaur.xyz>
 * @author Miles Croxford <hello@milescroxford.com>
 */
abstract class AbstractApiTest extends TestCase
{
    protected static $token;
    protected static $emergencyContactsCount;
    protected static $acceptedTerms;
    use DatabaseTransactions;

    protected static function setToken($token)
    {
        self::$token = $token;
    }

    protected function doApiCall($method, $url, $params = [], $decode = true)
    {
        $call = $this->call($method, $url, $params, [], [], [
            'HTTP_Authorization' => ['Basic ' . base64_encode(self::$token) . ':', null],
        ])->getContent();

        if ($decode) {
            return json_decode($call);
        }

        return $call;
    }

    protected function login($username = "chris+customer@haredigital.com", $password = "password")
    {
        $call = $this->call('POST', '/v1/auth/login', [
            'email'    => $username,
            'password' => $password,
        ]);

        $resp = json_decode($call->getContent());

        if (property_exists($resp, 'auth')) {
            self::$token = $resp->auth->data->token;
        }

        if (property_exists($resp, 'user')) {
            if (property_exists($resp->user->data, 'emergency_contacts')) {
                self::$emergencyContactsCount = count($resp->user->data->emergency_contacts->data);
            }
            self::$acceptedTerms = (bool)$resp->user->data->accepted_app_terms;
        }

        return $resp;
    }
}
