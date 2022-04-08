<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
 * (c)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c)
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CommunicationType
 *
 * @package App\Models
 * @author
 */
class CommunicationType extends Model
{


    protected $table = 'CommunicationType';

    /**
     * @return HasMany
     */
    public function communicationPreferences(): HasMany
    {
        return $this->hasMany(CommunicationPreferences::class, 'typeId', 'typeId');
    }

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'typeId';





}
