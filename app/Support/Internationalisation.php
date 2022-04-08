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

namespace App\Support;

use App\Models\Customer;
use App\Models\Vehicle;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;

/**
 * Class Internationalisation
 * @package App\Support
 */
class Internationalisation
{
    const UK_COUNTRY_CODE     = "GB";
    const UK_DEFAULT_TIMEZONE = "Europe/London";

    const KILOMETERS_IN_A_MILE = 1.609344;
    const MILES_IN_A_KILOMETER = 0.621371192;

    const GALLONS_IN_A_LITRE = 0.219969;
    const LITRES_IN_A_GALLON = 4.54609;

    /**
     * Compares two timezone locales and determines if they are equivalent, i.e. have the same time of day despite
     * being different countries
     *
     * @param $timezone1 string The first timezone
     * @param $timezone2 string The second timezone to compare to the first
     *
     * @return bool TRUE if the timezone have the same times, FALSE otherwise
     */
    public function timezonesAreEquivalent($timezone1, $timezone2)
    {
        $now = time();

        $time1 = Carbon::createFromTimestamp($now, 'UTC');
        $time2 = $time1->copy();

        $time1->timezone($timezone1);
        $time2->timezone($timezone2);

        return $time1->toDateTimeString() === $time2->toDateTimeString();
    }

    /**
     * @return array|null
     */
    public function timezoneList()
    {

        $timezones = [];
        $offsets   = [];
        $now       = new DateTime('now', new DateTimeZone('UTC'));

        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $now->setTimezone(new DateTimeZone($timezone));
            $offsets[]            = $offset = $now->getOffset();
            $timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
        }

        array_multisort($offsets, $timezones);

        return $timezones;
    }

    /**
     * @param $value int A distance in kilometers
     *
     * @return int The distance converted to miles
     */
    public function convertKilometersToMiles($value)
    {
        return ($value * self::MILES_IN_A_KILOMETER);
    }

    /**
     * @param $value int A distance in miles
     *
     * @return int The distance converted to kilometers
     */
    public function convertMilesToKilometers($value)
    {
        return ($value * self::KILOMETERS_IN_A_MILE);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function convertGallonsToLitres($value)
    {
        return ($value * self::LITRES_IN_A_GALLON);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function convertLitresToGallons($value)
    {
        return ($value * self::GALLONS_IN_A_LITRE);
    }

    /**
     * @param $mpg
     *
     * @return mixed
     */
    public function convertMpgToKpl($mpg)
    {
        return $mpg * self::KILOMETERS_IN_A_MILE * (1 / self::LITRES_IN_A_GALLON);
    }

    /**
     * @param $kpl
     *
     * @return mixed
     */
    public function convertKplToMpg($kpl)
    {
        return $kpl * self::MILES_IN_A_KILOMETER * (1 / self::GALLONS_IN_A_LITRE);
    }

    /**
     * @param Customer $customer
     *
     * @return bool
     *
     */
    public function isUKCustomer(Customer $customer)
    {
        return $customer->country === self::UK_COUNTRY_CODE;
    }

    /**
     * @param Customer $customer
     *
     * @return bool
     *
     */
    public function isInternationalCustomer(Customer $customer)
    {
        return $customer->country !== self::UK_COUNTRY_CODE;
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return string
     */
    public function vehicleBelongsToUKCustomer(Vehicle $vehicle)
    {
        return $vehicle->customer->country === self::UK_COUNTRY_CODE;
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return string
     */
    public function vehicleBelongsToInternationalCustomer(Vehicle $vehicle)
    {
        return $vehicle->customer->country !== self::UK_COUNTRY_CODE;
    }

    /**
     * @param $offset
     *
     * @return string
     */
    private function format_GMT_offset($offset)
    {
        $hours   = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));

        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    private function format_timezone_name($name)
    {
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);

        return $name;
    }
}
