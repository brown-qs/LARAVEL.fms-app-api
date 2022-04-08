<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Models;

use Carbon\Carbon;


/**
 * Class BatteryType
 *
 * @package App\Models
 * @author
 */
class BatteryType
{
    public const BATTERY_TYPE_COMMAND   = "battery type ";
    public const BATTERY_TYPE_12VLA     = 12;
    public const BATTERY_TYPE_12VLI     = 14;
    public const BATTERY_TYPE_6VLA      = 6;

    public const BATTERY_TYPE_12VLA_S   = '12VLA';
    public const BATTERY_TYPE_12VLI_S   = '12VLI';
    public const BATTERY_TYPE_6VLA_S    = '6VLA';

}
