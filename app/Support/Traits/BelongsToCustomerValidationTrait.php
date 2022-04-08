<?php

/**
 * This file is part of the Scorpion API
 * (c) Hare Digital
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c) Hare Digital
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Support\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

/**
 *
 * @author Tariq Tamuji <tariq@hare.digital>
 */
trait BelongsToCustomerValidationTrait
{

    private function modelBelongsToCustomer($table, $primaryKeyName, $primaryKeyValue): bool
    {
        $query = DB::table($table);

        // Get the model from the specified table by the specified id
        $query->where($primaryKeyName, $primaryKeyValue);

        // Add a condition to check customerId is the same one as in the authenticated user's
        $query->where('customerId', Request::get('user')->customerId);

        // Do the validation
        return $query->count() > 0;
    }

}