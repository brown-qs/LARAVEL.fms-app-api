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
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class CommunicationPreferences
 *
 * @package App\Models
 * @author
 */
class CommunicationPreferences extends Model
{
    /**
     * One Product has One Shipment.
     * @OneToOne(targetEntity="CommunicationType")
     * @JoinColumn(name="typeId", mappedBy="typeId")
     */
    protected $table = 'CommunicationPreferences';


    /**
     * @return HasOne
     */
    public function communicationType(): HasOne
    {
        return $this->hasOne(CommunicationType::class, 'typeId', 'typeId');
    }


    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'preferencesId';


    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    public function setTypeId($typeId) {
        $this->typeId = $typeId;
        return $this;
    }

}
