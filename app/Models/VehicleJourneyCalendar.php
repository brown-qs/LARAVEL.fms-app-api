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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class VehicleJourney
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleJourneyCalendar extends Model
{
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'VehicleJourney';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = null;

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'startTime',
        'endTime',
        'journeyDate',
    ];

    protected static function boot()
    {
        static::addGlobalScope('journeyCalendar', function (Builder $builder) {
            $builder->select(DB::raw('COUNT(*) AS journeyCount'))
                    ->addSelect(DB::raw('DATE(startTime) AS journeyDate'))
                    ->groupBy(DB::raw('DAY(startTime)'));
        });

        parent::boot();
    }
}
