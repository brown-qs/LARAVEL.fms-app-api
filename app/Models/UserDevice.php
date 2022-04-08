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

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Lumen\Auth\Authorizable;

/**
 * Class UserDevice
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class UserDevice extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'UserDevice';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = null;

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'createdAt',
        'lastLogin',
    ];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'userId',
        'fcmToken',
    ];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    /**
     * {@inheritDoc}
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query->where('userId', '=', $this->getAttribute('userId'))
                     ->where('fcmToken', '=', $this->getAttribute('fcmToken'));
    }
}
