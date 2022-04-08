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
 * @author Miles Croxford <hello@milescroxford.com>
 */

if (!function_exists('getCompassDirection')) {
    /**
     * Get bearing and output compass rose direction
     *
     * @param float|double|int $bearing
     *
     * @return array
     */
    function getCompassDirection($bearing): array
    {
        $tmp = round(($bearing % 360) / 22.5);
        switch ($tmp) {
            case 1:
                $shortDirection = "NNE";
                $longDirection  = "North-northeast";
                break;
            case 2:
                $shortDirection = "NE";
                $longDirection  = "Northeast";
                break;
            case 3:
                $shortDirection = "ENE";
                $longDirection  = "East-northeast";
                break;
            case 4:
                $shortDirection = "E";
                $longDirection  = "East";
                break;
            case 5:
                $shortDirection = "ESE";
                $longDirection  = "East-southeast";
                break;
            case 6:
                $shortDirection = "SE";
                $longDirection  = "Southeast";
                break;
            case 7:
                $shortDirection = "SSE";
                $longDirection  = "South-southeast";
                break;
            case 8:
                $shortDirection = "S";
                $longDirection  = "South";
                break;
            case 9:
                $shortDirection = "SSW";
                $longDirection  = "South-southwest";
                break;
            case 10:
                $shortDirection = "SW";
                $longDirection  = "Southwest";
                break;
            case 11:
                $shortDirection = "WSW";
                $longDirection  = "West-southwest";
                break;
            case 12:
                $shortDirection = "W";
                $longDirection  = "West";
                break;
            case 13:
                $shortDirection = "WNW";
                $longDirection  = "West-northwest";
                break;
            case 14:
                $shortDirection = "NW";
                $longDirection  = "Northwest";
                break;
            case 15:
                $shortDirection = "NNW";
                $longDirection  = "North-northwest";
                break;
            default:
                $shortDirection = "N";
                $longDirection  = "North";
        }

        return ['short' => $shortDirection, 'long' => $longDirection];
    }
}


if (!function_exists('kmh2Mph')) {
    /**
     * Convert kph to mph
     *
     * @param float|int $speed
     *
     * @return float
     */
    function kph2Mph($speed): float
    {
        return $speed * 0.621371;
    }
}

if (!function_exists('mph2kph')) {
    /**
     * Convert mph to kph
     *
     * @param float|int $speed
     *
     * @return float
     */
    function mph2Kph($speed): float
    {
        return $speed / 0.621371;
    }
}

if (!function_exists('distance')) {
    /**
     * @param array $from
     * @param array $to
     *
     * @return float
     */
    function distance(array $from, array $to): float
    {
        $from[0] = floatval($from[0]);
        $from[1] = floatval($from[1]);
        $to[0]   = floatval($to[0]);
        $to[1]   = floatval($to[1]);

        $from[0] = floatval($from[0]);
        if ($from[0] === $to[0] && $from[1] === $to[1]) {
            return 0;
        }

        $theta = $from[1] - $to[1];

        $dist = sin(deg2rad($from[0])) *
            sin(deg2rad($to[0])) +
            cos(deg2rad($from[0])) *
            cos(deg2rad($to[0])) *
            cos(deg2rad($theta));

        $dist  = acos($dist);
        $dist  = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles;
    }
}

if (!function_exists('distanceInKilometers')) {
    /**
     * @param array $from
     * @param array $to
     *
     * @return float
     */
    function distanceInKilometers(array $from, array $to): float
    {
        return (distance($from, $to) * 1.609344);
    }
}
