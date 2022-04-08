<?php

/**
 * This file is part of the Scorpion API
 */

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The Bench Facade
 *
 * @see    \App\Support\Bench
 * @author Miles Croxford <hello@milescroxford.com>
 */
class SMSAPI extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'smsapi';
    }
}
