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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Brand extends Model
{
    public const BRAND_FLEET        = "default";
    public const BRAND_FLEET_STR    = "fleet";
    public const BRAND_OTL          = "otl";
    public const BRAND_ADVENTURE    = "adventure";
    public const BRAND_REWIRE       = "rewire";
    public const BRAND_BMW          = "bmw";
    public const BRAND_SUBARU       = "subaru";
    public const BRAND_FLEETCORE    = "fleetcore";
    public const BRAND_SEMITAFLEET  = "semitafleet";
    public const BRAND_FLEETCORE_CA = "fleetcore_ca";
    public const BRAND_VTS          = "vts";
    public const BRAND_DRIVER       = "driver";
    public const BRAND_TRIUMPH      = "triumph";
    public const BRAND_BDI          = "bdi";
    public const BRAND_WHITELABEL   = "whitelabel";
    public const BRAND_ATOM         = "atom";
    public const BRAND_AMPERE       = "ampere";
    public const BRAND_UAE          = "uae";
    public const BRAND_TRACKED      = "tracked";

    /**
     * @return array
     */
    public function validBrandsMap(): array
    {
        {
            return [
                self::BRAND_FLEET_STR    => "Scorpion Fleet",
                self::BRAND_FLEET        => "Scorpion Fleet",
                self::BRAND_OTL          => "DeliveryMates",
                self::BRAND_ADVENTURE    => "Datatool",
                self::BRAND_FLEETCORE    => "FleetCore",
                self::BRAND_FLEETCORE_CA => "FleetCore Canada",
                self::BRAND_DRIVER       => "Scorpion Driver",
                self::BRAND_TRIUMPH      => "Triumph",
                self::BRAND_BDI          => "BDi",
                self::BRAND_AMPERE       => "Ampere",
                self::BRAND_UAE          => "UAE",
                self::BRAND_REWIRE       => "Rewire",
            ];
        }
    }

    /**
     * @param string|null $brand
     *
     * @return bool
     */
    public function isValidBrand(?string $brand): bool
    {
        return in_array($brand, self::validBrands());
    }

    /**
     * @return array
     */
    public function validBrands(): array
    {
        return [
            self::BRAND_FLEET,
            self::BRAND_OTL,
            self::BRAND_ADVENTURE,
            self::BRAND_BMW,
            self::BRAND_SUBARU,
            self::BRAND_FLEETCORE,
            self::BRAND_SEMITAFLEET,
            self::BRAND_FLEETCORE_CA,
            self::BRAND_VTS,
            self::BRAND_DRIVER,
            self::BRAND_TRIUMPH,
            self::BRAND_BDI,
            self::BRAND_WHITELABEL,
            self::BRAND_ATOM,
            self::BRAND_AMPERE,
            self::BRAND_UAE,
            self::BRAND_TRACKED,
            self::BRAND_REWIRE,
        ];
    }
}