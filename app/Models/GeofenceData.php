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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Class Driver
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class GeofenceData extends Model
{
    const POLY_POLYGON_STRING = "POLYGON";
    const POLY_POINT_STRING   = "POINT";

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
    protected $table = 'GeofenceData';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = null;

    /**
     * {@inheritDoc}
     */
    protected $appends = ['lat', 'lng'];

    /**
     * {@inheritDoc}
     */
    public function newQuery()
    {
        return parent::newQuery()->addSelect(DB::raw('GeofenceData.*, ST_AsText(poly) AS poly'));
    }

    public function getPointsAttribute()
    {
        $poly = $this->poly;

        if (substr($poly, 0, strlen(self::POLY_POLYGON_STRING)) === self::POLY_POLYGON_STRING) {
            $polyString = self::POLY_POLYGON_STRING;
        } elseif (substr($poly, 0, strlen(self::POLY_POINT_STRING)) === self::POLY_POINT_STRING) {
            $polyString = self::POLY_POINT_STRING;
        } else {
            throw new \RuntimeException(sprintf('Excepted %s or %s as poly string',
                self::POLY_POINT_STRING,
                self::POLY_POLYGON_STRING));
        }

        $poly = str_replace([$polyString, ')', '('], '', $poly);
        $poly = array_map(function ($point) {
            $latlng = explode(' ', $point);

            return ['lat' => floatval($latlng[0]), 'lng' => floatval($latlng[1])];
        }, explode(',', $poly));

        return $poly;
    }

    /**
     * @return BelongsTo
     */
    public function geofence(): BelongsTo
    {
        return $this->belongsTo(Geofence::class, 'geofenceId', 'geofenceId');
    }
}
