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

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * DealershipController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class DealershipController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function getCustomersAction(): JsonResponse
    {
        $authUser = $this->request->get('user');

        $customers = Customer::where('dealershipId', $authUser->dealershipId)->get();
        if (!$customers) {
            return $this->respondWithNotFound();
        }

        return $this->transformCollection($customers, null, 'customers')->respond();
    }

    /**
     * @return JsonResponse
     */
    public function getFittersAction(): JsonResponse
    {
        $authUser = $this->request->get('user');

        $users = User::where('dealershipId', $authUser->dealershipId)
                         ->where('active', true)
                         ->where(function ($query) {
                             $query->where('type', User::USER_TYPE_FITTER)
                                   ->orWhere('type', User::USER_TYPE_DEALER);
                         })->get();
        if (!$users) {
            return $this->respondWithNotFound();
        }

        return $this->transformCollection($users, null, 'users')->respond();
    }
}
