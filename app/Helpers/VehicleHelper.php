<?php declare(strict_types=1);

/**
 * The number of kilometers in a mile
 */
const KILOMETERS_IN_A_MILE = 1.609344;

/**
 * The number of miles in a kilometer
 */
const MILES_IN_A_KILOMETER = 0.621371192;

/**
 *  VOLUME UNITS RELATED FUNCTIONALITY
 */

/**
 * The number of gallons in a litre
 */
const GALLONS_IN_A_LITRE = 0.219969;

/**
 * The number of litres in a gallon
 */
const LITRES_IN_A_GALLON = 4.54609;

if (!function_exists("convert_kpl_to_mpg")) {

    /**
     * @param $kpl
     *
     * @return mixed
     */
    function convert_kpl_to_mpg($kpl)
    {
        return $kpl * MILES_IN_A_KILOMETER * (1 / GALLONS_IN_A_LITRE);
    }

}


/**
 *  FUEL CONSUMPTION UNITS RELATED FUNCTIONALITY
 */
if (!function_exists("get_users_fuel_consumption_units")) {

    /**
     * @return string
     */
    function get_users_fuel_consumption_units($units)
    {
        // In this case only look at distance units, we don't want to be mixing imperial and metric units
        switch ($units) {
            case "miles" :
            {
                return "mpg";
            }
            case "kilometers" :
            {
                return "kpl";
            }
            default :
            {
                return "none";
            }
        }
    }

}