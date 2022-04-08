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

namespace App\Console\Commands;

use App\Models\ApiKey;
use App\Models\User;
use Firebase\JWT\JWT as FirebaseJWT;
use Illuminate\Console\Command;

/**
 * The command to generate an api key
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class GenerateUserTokenCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'user-token:generate {userId}';

    /**
     * @var string
     */
    protected $description = 'Generates a token for user <USER_ID>';


    public function handle()
    {
        $this->fire();
    }
    /**
     * Put the application into maintenance mode.
     */
    public function fire()
    {
        $user = User::where('userId', $this->argument("userId"))->first();

        if (!$user) {
            return $this->error("User doesn't exist");
        }

        $payload  = ['user_id' => $user->id, 'is_api_token' => true];
        $issuedAt = time();

        $token = FirebaseJWT::encode([
            "payload" => $payload,
            "iat"     => $issuedAt,
        ], config('app.apiKey'));

        $apiKey             = new ApiKey();
        $apiKey->customerId = $user->customerId;
        $apiKey->userId     = $user->id;
        $apiKey->token      = $token;
        $apiKey->save();

        $this->info(sprintf("Token created: %s", $token));
    }
}
