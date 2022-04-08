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

if (!function_exists('scorpion_password_hash')) {
    /**
     * Generate a password hash
     *
     * @param string $password
     * @param string $salt
     *
     * @return string
     */
    function scorpion_password_hash(string $password, string $salt): string
    {
        return sha1($password . $salt);
    }
}

if (!function_exists('scorpion_password_verify')) {
    /**
     * Generate a password hash
     *
     * @param string $password
     * @param string $salt
     * @param string $hash The hash to compare against
     *
     * @return bool
     */
    function scorpion_password_verify(string $password, string $salt, string $hash): bool
    {
        return sha1($password . $salt) === $hash;
    }
}

if (!function_exists('scorpion_unique_hash')) {
    /**
     * Generate a `unique hash`, used for salt
     *
     * @return string
     */
    function scorpion_unique_hash(): string
    {
        mt_srand((int)round(microtime(true) * 100000 + memory_get_usage(true), 0) * 1000);

        return md5(uniqid((string)mt_rand(), true));
    }
}
