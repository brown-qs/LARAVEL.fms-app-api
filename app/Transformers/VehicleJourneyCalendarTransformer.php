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

namespace App\Transformers;

use App\Models\VehicleJourneyCalendar;

/**
 * UnitTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleJourneyCalendarTransformer extends DefaultTransformer
{
    /**
     * @param VehicleJourneyCalendar $vehicleJourneyCalendar
     *
     * @return array
     */
    public function transform(VehicleJourneyCalendar $vehicleJourneyCalendar): array
    {
        return [
            'date'  => $vehicleJourneyCalendar->journeyDate->toDateString(),
            'count' => $vehicleJourneyCalendar->journeyCount,
        ];
    }
}
