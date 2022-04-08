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

/**
 * All the helpers that lumen should have but does not >:)
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */

// if (!function_exists('route')) {
//     /**
//      * Generate a URL to a named route.
//      *
//      * @param  string $name
//      * @param  array  $parameters
//      * @param  bool   $absolute
//      *
//      * @return string
//      */
//     function route(string $name, array $parameters = [], bool $absolute = true): string
//     {
//         $url = app('url')->route($name, $parameters, $absolute);
//         if (config('app.env') === 'production') {
//             $url = str_replace('http://', 'https://', $url);
//         }
//
//         return $url;
//     }
// }

if (!function_exists('route_url')) {
    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return string
     */
    function route_url(string $name, array $parameters = [], bool $absolute = true): string
    {
        $url = app('url')->route($name, $parameters, $absolute);

        if (config('app.env') === 'production') {
            $url = str_replace('http://', 'https://', $url);
        }

        return $url;
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     *
     * @return string
     */
    function config_path(string $path = ''): string
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}
