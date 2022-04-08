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

namespace App\Support\Models;

/**
 * Class Address
 * @package App\Support\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Address
{
    /**
     * @var float
     */
    public $lat;

    /**
     * @var float
     */
    public $lng;

    /**
     * @var string
     */
    public $house;

    /**
     * @var string
     */
    public $street;

    /**
     * @var string
     */
    public $locality;

    /**
     * @var string
     */
    public $postCode;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $countryCode;

    /**
     * @var string
     */
    public $address;

    /**
     * @param string $addressCache
     *
     * @return Address
     */
    public function fill(string $addressCache): Address
    {
        foreach (json_decode($addressCache) as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }
}
