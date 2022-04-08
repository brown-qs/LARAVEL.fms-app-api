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

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The Internationalisation Facade
 *
 * @see    \App\Support\Internationalisation
 * @author Tariq Tamuji <tariq@hare.digital>
 */
class Internationalisation extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'internationalisation';
    }

}
