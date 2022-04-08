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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CatType
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class CatType extends Model
{
    public const CAT_OTHER = "CAT_OTHER";
    public const CAT_6     = "CAT_6";
    public const CAT_S5    = "S5";
    public const CAT_S7    = "S7";

    public const UNIT_TYPE_ST70    = "ST70";
    public const UNIT_TYPE_ST71    = "ST71";
    public const UNIT_TYPE_STX70   = "STX70";
    public const UNIT_TYPE_STX71   = "STX71";
    public const UNIT_TYPE_STX71F  = "STX71F";
    public const UNIT_TYPE_STM01F  = "STM01F";
    public const UNIT_TYPE_STX70LK = "STX70lk";
    public const UNIT_TYPE_STX70EK = "STX70ek";

    public const AFRICA_TRACKERS = [
        self::UNIT_TYPE_STX70LK,
        self::UNIT_TYPE_STX70EK,
    ];

    public const APPROVAL_NUM_TQA523 = "TQA523";
    public const APPROVAL_NUM_TSC111 = "TSC111";
    public const APPROVAL_NUM_TSC086 = "TSC086";
    public const APPROVAL_NUM_TSC079 = "TSC079";
    public const APPROVAL_NUM_TSC082 = "TSC082";
    public const APPROVAL_NUM_TQA524 = "TQA524";
    public const APPROVAL_NUM_TSC080 = "TSC080";
    public const APPROVAL_NUM_TSC081 = "TSC081";
    public const APPROVAL_NUM_TSC085 = "TSC085";
    public const APPROVAL_NUM_TQA314 = "TQA314";
    public const APPROVAL_NUM_TSC087 = "TSC087";

    //STM Values
    public const APPROVAL_NUM_TSC206 = "TSC206"; //Datatool Stealth S5
    public const APPROVAL_NUM_TSC209 = "TSC209"; //Datatool Stealth S7
    public const APPROVAL_NUM_TSC205 = "TSC205"; //Scorpion M Series S5
    public const APPROVAL_NUM_TSC208 = "TSC208"; //Scorpion M Series Tracker S7
    public const APPROVAL_NUM_TSC207 = "TSC207"; //Triumph Track+

    public const APPROVAL_NUM_TBC    = "TBC";
    public const APPROVAL_NUM_TSC241 = "TSC241";
    public const APPROVAL_NUM_TSC242 = "TSC242";

    public const APPROVAL_STANDARD_TEXT_CAT_6  = "Thatcham Category 6: Stolen Vehicle Tracking System";
    public const APPROVAL_STANDARD_TEXT_CAT_S5 = "Thatcham Category S5: Vehicle Tracking System";
    public const APPROVAL_STANDARD_TEXT_CAT_S7 = "Thatcham Category S7: Asset Location System";

    public const LOGO_TYPE_TQA = "TQA";
    public const LOGO_TYPE_TSC = "TSC";

    public const PRODUCT_NAME_STFS        = "ScorpionTrack Fleet Secure";
    public const PRODUCT_NAME_ST_S7       = "ScorpionTrack S7-ALS";
    public const PRODUCT_NAME_STFS_HD     = "ScorpionTrack Fleet Secure HD";
    public const PRODUCT_NAME_ST_HD_S7    = "ScorpionTrack HD S7-ALS";
    public const PRODUCT_NAME_ST_HD_S7_RF = "ScorpionTrack HD S7-ALS RF";
    public const PRODUCT_NAME_STD_S5      = "ScorpionTrack Driver S5-VTS";
    public const PRODUCT_NAME_ADV         = "Datatool TrakKING Adventure";
    public const PRODUCT_NAME_ADV_S7      = "Datatool TrakKING Adventure S7";
    public const PRODUCT_NAME_ADV_S5      = "Datatool TrakKING Adventure S5";
    public const PRODUCT_NAME_REWIRE_S5   = "Vehicle Tracking Systems - Category S5";
    public const PRODUCT_NAME_REWIRE_S7   = "Asset Location System - Category S7";

    public const PRODUCT_NAME_DTS_S5 = 'Datatool Stealth S5';
    public const PRODUCT_NAME_DTS_S7 = 'Datatool Stealth S7';
    public const PRODUCT_NAME_SMS_S5 = 'Scorpion M Series S5';
    public const PRODUCT_NAME_SMS_S7 = 'Scorpion M Series Tracker S7';
    public const PRODUCT_NAME_TTP    = 'Triumph Track+';


    public $approvalNo;
    public $approvalStandardText;
    public $logoType;
    public $cat;
    public $certType;
    public $productName;

    /**
     * @param $vehicle
     *
     * @return bool
     */
    public static function isStx($vehicle): bool
    {
        return in_array($vehicle->unitType, [
            self::UNIT_TYPE_STX71,
        ]);
    }

    /**
     * @param Vehicle   $vehicle
     * @param string $brand
     *
     * @return CatType
     */
    public static function getCatType($vehicle, $brand = Brand::BRAND_FLEET_STR): CatType
    {
        $unit = $vehicle->unit;

        $hardwareId    = str_pad((string)$unit->hardwareId, 8, "0", STR_PAD_LEFT);
        $hardwareId2nd = (int)substr($hardwareId, 1, 1);

        $catType                       = new CatType();
        $catType->approvalNo           = self::APPROVAL_NUM_TQA314;
        $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_6;
        $catType->logoType             = self::LOGO_TYPE_TQA;
        $catType->cat                  = self::CAT_6;
        $catType->productName          = self::getProductName($catType->approvalNo);

        $installed = null;
        if (!is_null($vehicle->installed)) {
            $installed = Carbon::parse($vehicle->installed)->year;
        }

        if ($unit->type === self::UNIT_TYPE_ST70 || $unit->type === self::UNIT_TYPE_ST71) {
            return $catType;
        }

        if ($unit->type === self::UNIT_TYPE_STX70) {
            if (is_null($installed) || $installed < 2019) {
                return $catType;
            }

            $catType->approvalNo           = self::APPROVAL_NUM_TSC082;
            $catType->logoType             = self::LOGO_TYPE_TSC;
            $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S7;
            $catType->cat                  = self::CAT_S7;

            if ($brand === Brand::BRAND_REWIRE) {
                $catType->approvalNo = self::APPROVAL_NUM_TSC242;
            }

            $catType->productName = self::getProductName($catType->approvalNo);
            return $catType;
        }

        if ($unit->type === self::UNIT_TYPE_STX71) {
            if (is_null($installed) || $installed < 2019) {
                if ($brand === Brand::BRAND_DRIVER) {
                    $catType->approvalNo           = self::APPROVAL_NUM_TQA524;
                    $catType->logoType             = self::LOGO_TYPE_TQA;
                    $catType->cat                  = self::CAT_6;
                    $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_6;
                    $catType->productName          = self::getProductName($catType->approvalNo);
                    return $catType;
                }

                return $catType;
            }


            if ($hardwareId2nd === 5) {
                if ($unit->driverModule == 2) {
                    $catType->approvalNo           = self::APPROVAL_NUM_TSC085;
                    $catType->logoType             = self::LOGO_TYPE_TSC;
                    $catType->cat                  = self::CAT_S5;
                    $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S5;

                    if ($brand === Brand::BRAND_REWIRE) {
                        $catType->approvalNo = self::APPROVAL_NUM_TSC241;
                    }

                    $catType->productName = self::getProductName($catType->approvalNo);
                    return $catType;
                }

                $catType->approvalNo           = self::APPROVAL_NUM_TSC081;
                $catType->logoType             = self::LOGO_TYPE_TSC;
                $catType->cat                  = self::CAT_S7;
                $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S7;
                if ($brand === Brand::BRAND_REWIRE) {
                    $catType->approvalNo = self::APPROVAL_NUM_TSC242;
                }

                $catType->productName = self::getProductName($catType->approvalNo);
                return $catType;
            }

            $catType->approvalNo = self::APPROVAL_NUM_TSC080;
            $catType->logoType   = self::LOGO_TYPE_TSC;
            $catType->cat        = self::CAT_S7;
            if ($brand === Brand::BRAND_REWIRE) {
                $catType->approvalNo = self::APPROVAL_NUM_TSC242;
            }

            $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S7;
            $catType->productName = self::getProductName($catType->approvalNo);
            return $catType;
        }


        if ($unit->type === self::UNIT_TYPE_STX71F) {
            if ($installed === null || $installed < 2019) {
                $catType->approvalNo           = self::APPROVAL_NUM_TQA523;
                $catType->logoType             = self::LOGO_TYPE_TQA;
                $catType->cat                  = self::CAT_6;
                $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_6;
                $catType->productName          = self::getProductName($catType->approvalNo);
                return $catType;
            }

            if ($hardwareId2nd === 5) {
                if ($unit->driverModule == 2) {
                    $catType->approvalNo           = self::APPROVAL_NUM_TSC086;
                    $catType->logoType             = self::LOGO_TYPE_TSC;
                    $catType->cat                  = self::CAT_S5;
                    $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S5;
                    if ($brand === Brand::BRAND_REWIRE) {
                        $catType->approvalNo = self::APPROVAL_NUM_TSC241;
                    }

                    $catType->productName = self::getProductName($catType->approvalNo);
                    return $catType;
                }

                $catType->approvalNo           = self::APPROVAL_NUM_TSC079;
                $catType->logoType             = self::LOGO_TYPE_TSC;
                $catType->cat                  = self::CAT_S7;
                $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S7;
                if ($brand === Brand::BRAND_REWIRE) {
                    $catType->approvalNo = self::APPROVAL_NUM_TSC242;
                }

                $catType->productName = self::getProductName($catType->approvalNo);
                return $catType;
            }


            $catType->approvalNo           = self::APPROVAL_NUM_TSC111;
            $catType->logoType             = self::LOGO_TYPE_TSC;
            $catType->cat                  = self::CAT_S7;
            $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S7;
            if ($brand === Brand::BRAND_REWIRE) {
                $catType->approvalNo = self::APPROVAL_NUM_TSC242;
            }

            $catType->productName = self::getProductName($catType->approvalNo);
            return $catType;
        }

        if ($unit->type === self::UNIT_TYPE_STM01F) {
            if ($unit->driverModule == 2) {

                $catType->approvalNo = self::APPROVAL_NUM_TSC205;
                if ($brand === Brand::BRAND_TRIUMPH) {
                    $catType->approvalNo = self::APPROVAL_NUM_TSC207;
                }
                if ($brand === Brand::BRAND_ADVENTURE) {
                    $catType->approvalNo = self::APPROVAL_NUM_TSC206;
                }

                if ($brand === Brand::BRAND_REWIRE) {
                    $catType->approvalNo = self::APPROVAL_NUM_TSC241;
                }

                $catType->logoType             = self::LOGO_TYPE_TSC;
                $catType->cat                  = self::CAT_S5;
                $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S5;
                $catType->productName          = self::getProductName($catType->approvalNo);
                return $catType;
            }

            $catType->approvalNo = self::APPROVAL_NUM_TSC208;
            if ($brand === Brand::BRAND_ADVENTURE) {
                $catType->approvalNo = self::APPROVAL_NUM_TSC209;
            }

            if ($brand === Brand::BRAND_REWIRE) {
                $catType->approvalNo = self::APPROVAL_NUM_TSC242;
            }

            $catType->logoType             = self::LOGO_TYPE_TSC;
            $catType->cat                  = self::CAT_S7;
            $catType->approvalStandardText = self::APPROVAL_STANDARD_TEXT_CAT_S7;
            $catType->productName          = self::getProductName($catType->approvalNo);
            return $catType;
        }
        return $catType;
    }

    /**
     * @param string $approvalNo
     *
     * @return string
     */
    public static function getProductName(string $approvalNo): string
    {
        switch ($approvalNo) {
            case self::APPROVAL_NUM_TSC111:
            case self::APPROVAL_NUM_TQA523:
                return self::PRODUCT_NAME_ADV;
            case self::APPROVAL_NUM_TQA524:
                return self::PRODUCT_NAME_STFS_HD;
            case self::APPROVAL_NUM_TSC079:
                return self::PRODUCT_NAME_ADV_S7;
            case self::APPROVAL_NUM_TSC080:
                return self::PRODUCT_NAME_ST_HD_S7;
            case self::APPROVAL_NUM_TSC081:
                return self::PRODUCT_NAME_ST_HD_S7_RF;
            case self::APPROVAL_NUM_TSC082:
                return self::PRODUCT_NAME_ST_S7;
            case self::APPROVAL_NUM_TSC085:
                return self::PRODUCT_NAME_STD_S5;
            case self::APPROVAL_NUM_TSC086:
                return self::PRODUCT_NAME_ADV_S5;
            case self::APPROVAL_NUM_TSC206:
                return self::PRODUCT_NAME_DTS_S5;
            case self::APPROVAL_NUM_TSC209:
                return self::PRODUCT_NAME_DTS_S7;
            case self::APPROVAL_NUM_TSC205:
                return self::PRODUCT_NAME_SMS_S5;
            case self::APPROVAL_NUM_TSC208:
                return self::PRODUCT_NAME_SMS_S7;
            case self::APPROVAL_NUM_TSC207:
                return self::PRODUCT_NAME_TTP;
            case self::APPROVAL_NUM_TSC241:
                return self::PRODUCT_NAME_REWIRE_S5;
            case self::APPROVAL_NUM_TSC242:
                return self::PRODUCT_NAME_REWIRE_S7;
            default:
            case self::APPROVAL_NUM_TQA314:
                return self::PRODUCT_NAME_STFS;
        }
    }
}