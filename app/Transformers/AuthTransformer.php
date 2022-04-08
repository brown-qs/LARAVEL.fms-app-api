<?php

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

use App\Support\Auth;

/**
 * AuthTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class AuthTransformer extends DefaultTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param Auth $auth
     *
     * @return array
     */
    public function transform(Auth $auth): array
    {
        $data = ['token' => $auth->token];

        if (config('app.debug')) {
            $data['payload']   = $auth->payload;
            $data['expires']   = $auth->expires;
            $data['issued_at'] = $auth->issuedAt;
        }

        return $data;
    }
}
