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

if (!function_exists('camelCaseToUnderscore')) {
    /**
     * Converts camel case string to underscore case, e.g.
     * camelCaseToUnderscore -> camel_case_to_underscore
     *
     * @param string $input
     *
     * @return string
     */
    function camelCaseToUnderscore(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}

if (!function_exists('classNameToUnderscore')) {
    /**
     * Converts class to underscore case, e.g.
     * Camel\Case\CamelClass -> camel_class
     *
     * @param mixed $class
     *
     * @return string
     */
    function classNameToUnderscore($class): string
    {
        return camelCaseToUnderscore(getClassName($class));
    }
}

if (!function_exists('getClassName')) {
    /**
     * Converts class to underscore case, e.g.
     * Camel\Case\CamelClass -> CamelClass
     *
     * @param mixed $class
     *
     * @return string
     */
    function getClassName($class): string
    {
        $class = explode("\\", get_class($class));
        return $class[count($class) - 1];
    }
}

if (!function_exists('dashHash')) {
    /**
     * Generates a hash like 12-1234-1234-1234
     *
     * @return string
     */
    function dashHash(): string
    {
        $hash = uniqid("", true);
        $hash = str_replace('.', '', $hash);
        $hash = str_split(strrev($hash), 4);
        $hash = implode("-", $hash);

        return strrev($hash);
    }
}

if (!function_exists('unique_hash')) {
    function unique_hash()
    {
        mt_srand((int)((microtime(true) * 10000) + memory_get_usage(true)));
        return md5(uniqid((string)mt_rand(), true));
    }
}


if (!function_exists("number_pad")) {

    function number_pad($number, $n)
    {
        return str_pad((string)$number, $n, "0", STR_PAD_LEFT);
    }

}

if (!function_exists("string_pad")) {

    function string_pad($string, $n, $padChar = "0")
    {
        return str_pad((string)$string, $n, $padChar, STR_PAD_LEFT);
    }

}
