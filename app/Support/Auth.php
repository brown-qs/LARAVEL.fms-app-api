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

namespace App\Support;

use Firebase\JWT\JWT as FirebaseJWT;
use Illuminate\Support\Facades\Config;

/**
 * JsonWebToken
 *
 * @package App\Support
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Auth
{
    /**
     * @var array
     */
    public $payload;

    /**
     * @var int
     */
    public $issuedAt;

    /**
     * @var false|int
     */
    public $expires;

    /**
     * @var string
     */
    public $token;

    /**
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload  = $payload;
        $this->issuedAt = time();
        $this->expires  = strtotime('+' . config('app.tokenExpiresHours', 24) . ' hour');

        if (config("app.debug")) {
            $this->expires = strtotime('+' . 72 . ' hour');
        }

        $this->token = FirebaseJWT::encode([
            'payload' => $payload,
            "iat"     => $this->issuedAt,
            "exp"     => $this->expires,
        ], config('app.apiKey'));
    }
}
