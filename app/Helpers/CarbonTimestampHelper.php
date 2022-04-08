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
use Carbon\Carbon;

/**
 * @author Miles Croxford <hello@milescroxford.com>
 */

if (!function_exists('carbon_timestamp')) {
    /**
     * Return a carbon timestamp if not null, else returns null
     *
     * @param Carbon|null $carbonObject
     *
     * @return int|null
     */
    function carbon_timestamp(?Carbon $carbonObject): ?int
    {
        return $carbonObject ? $carbonObject->getTimestamp() : null;
    }
}

if (!function_exists('carbon_date')) {
    /**
     * Creates a unix timestamp for a date.
     *
     * @param string|null $date
     *
     * @return int|null
     */
    function carbon_date(?string $date): ?int
    {
        if ($date) {
            if ($date === '0000-00-00') {
                return null;
            }

            return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->timestamp;
        }

        return null;
    }
}
