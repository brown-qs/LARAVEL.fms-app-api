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
 * Class UnitInstallStatus
 *
 * @package App\Models

 */
class UnitInstallStatus extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = null;

    /**
     * {@inheritDoc}
     */
    protected $table = 'UnitInstallStatus';

}