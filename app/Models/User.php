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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Lumen\Auth\Authorizable;

/**
 * Class User
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;

    /*
     * User types constants
     */
    public const USER_TYPE_CUSTOMER_SUPER = "CustomerSuper";
    public const USER_TYPE_CUSTOMER       = "Customer";
    public const USER_TYPE_FITTER         = "Fitter";
    public const USER_TYPE_ADMIN          = "Admin";
    public const USER_TYPE_DEALER         = "Dealer";
    public const USER_TYPE_SUPER          = "Super";
    public const USER_TYPE_BRAND_ADMIN    = "BrandAdmin";
    public const USER_TYPE_WAREHOUSE      = "Warehouse";
    public const USER_TYPE_POLICE         = "Police";

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'User';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'userId';

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
        'salt',
        'securityAnswer',
    ];

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'lastLogin',
        'lastActive',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @return array[string]
     */
    public static function getUserTypes(): array
    {
        return [
            self::USER_TYPE_CUSTOMER_SUPER,
            self::USER_TYPE_CUSTOMER,
            self::USER_TYPE_FITTER,
            self::USER_TYPE_ADMIN,
            self::USER_TYPE_DEALER,
            self::USER_TYPE_SUPER,
            self::USER_TYPE_WAREHOUSE,
            self::USER_TYPE_POLICE,
        ];
    }

    /**
     * @return bool
     */
    public function getIsCustomerAttribute(): bool
    {
        return $this->type === self::USER_TYPE_CUSTOMER_SUPER || $this->type === self::USER_TYPE_CUSTOMER;
    }

    /**
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->type === self::USER_TYPE_ADMIN || $this->type === self::USER_TYPE_SUPER || $this->type === self::USER_TYPE_BRAND_ADMIN;
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return sprintf("%s %s", $this->firstName, $this->lastName);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function isValidPassword(string $password): bool
    {
        return scorpion_password_verify($password, $this->salt, $this->password);

    }

    /**
     * @return int
     */
    public function getIdAttribute(): int
    {
        return $this->userId;
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }

    /**
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permissions::class, 'userId', 'userId');
    }

    /**
     * @return HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class, 'userId', 'userId');
    }

    /**
     * @return BelongsToMany
     */
    public function permittedGroups(): BelongsToMany
    {
        return $this->belongsToMany(VehicleGroup::class,
            'AssignedPreferences',
            'customerId',
            'preferenceId'
        );
    }

    public function hashPassword($password)
    {
        $salt = unique_hash();
        $pass = html_entity_decode($password, ENT_IGNORE, 'UTF-8') . $salt;
        return [
            'salt' => $salt,
            'password' => sha1($pass),
        ];
    }
}
