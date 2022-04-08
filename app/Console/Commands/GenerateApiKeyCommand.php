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
use App\Models\Customer;
use App\Models\User;
use Firebase\JWT\JWT as FirebaseJWT;
use Illuminate\Console\Command;

/**
 * The command to generate an api key
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class GenerateApiKeyCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'api-key:generate {customerId}';

    /**
     * @var string
     */
    protected $description = 'Generates a api key for customer <CUSTOMER_ID>';

    /**
     * Put the application into maintenance mode.
     */
    public function fire()
    {
        $customer = Customer::where('customerId', $this->argument("customerId"))->first();

        if (!$customer) {
            return $this->error("Customer doesn't exist");
        }

        $user                = new User();
        $user->customerId    = $customer->customerId;
        $user->dealershipId  = 0;
        $user->firstName     = "API";
        $user->lastName      = "TOKEN";
        $user->type          = "CustomerSuper";
        $user->email         = sprintf("api+%s+%s+%s@scorpiontrack.com", $customer->customerId, time(), uniqid());
        $user->password      = "0";
        $user->salt          = "0";
        $user->active        = true;
        $user->timezone      = $customer->timezone;
        $user->dismissed     = true;
        $user->distanceUnits = "kilometers";
        $user->volumeUnits   = "litres";
        $user->save();

        $payload  = ['user_id' => $user->id, 'is_api_token' => true];
        $issuedAt = time();

        $token = FirebaseJWT::encode([
            "payload" => $payload,
            "iat"     => $issuedAt,
        ], config('app.apiKey'));

        $apiKey             = new ApiKey();
        $apiKey->customerId = $customer->customerId;
        $apiKey->userId     = $user->id;
        $apiKey->token      = $token;
        $apiKey->save();

        $this->info(sprintf("Token created: %s", $token));
    }
}
