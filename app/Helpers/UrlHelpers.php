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
use Illuminate\Support\Facades\Request;

/**
 * @author Miles Croxford <hello@milescroxford.com>
 */

if (!function_exists('customerRoute')) {
    /**
     * Gets URL route, dependant on if the user is a customer or not
     *
     * @param string $route
     * @param array  $params
     *
     * @return string
     */
    function customerRoute(string $route, array $params): string
    {
        if (!Request::get('is_customer')) {
            $route                = 'customers.' . $route;
            $params['customerId'] = Request::get('customerId');
        }

        return route($route, $params);
    }
}
