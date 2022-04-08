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

use App\Models\User;
use App\Models\UserDevice;
use App\Support\Auth;
use App\Transformers\NotificationsTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class AuthController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function loginAction(): JsonResponse
    {
        $mockingUser = false;

        if (Config::get('app.env') !== "production" && Config::get('app.mockUserId')) {
            $query = User::where('userId', Config::get('app.mockUserId'));
            $mockingUser = true;
        } else {
            $query = User::where('email', $this->request->get('email'));
        }

        $user = $query->with('customer', 'permissions')->first();

        if (is_null($user)) {
            return $this->respondWithNotFound('User Not Found');
        }

        if (!$mockingUser && !$user->isValidPassword($this->request->get('password'))) {
            return $this->respondWithForbidden('Incorrect Password');
        }

        Log::info(sprintf(
            "FCM try: user_id:%s; fcm_token:%s; app:%s; app_version:%s platform:%s",
            $user->id,
            $this->request->get('fcm_token'),
            $this->request->get('app'),
            $this->request->get('app_version'),
            $this->request->get('app_platform')
        ));

        if ($this->request->has('fcm_token') && $this->request->has('app') && $this->request->has('app_version') && $this->request->has('app_platform')) {
            $userDevice = UserDevice::firstOrNew([
                "userId"   => $user->id,
                "fcmToken" => $this->request->get('fcm_token'),
            ]);
            $userDevice->appPlatform = $this->request->get('app_platform');
            $userDevice->app = $this->request->get('app');
            $userDevice->appVersion = $this->request->get('app_version');
            $userDevice->lastLogin = Carbon::now();
            $userDevice->save();

            Log::info(sprintf(
                "FCM saved: user_id:%s; fcm_token:%s; app:%s; app_version:%s platform:%s",
                $user->id,
                $this->request->get('fcm_token'),
                $this->request->get('app'),
                $this->request->get('app_version'),
                $this->request->get('app_platform')
            ));
        }

        return $this->addReturnToken(new Auth(['user_id' => $user->id]))
            ->transformItem($user, ['customer', 'permissions'])->respond();
    }

    public function mockUser($userId): JsonResponse
    {
        $query = User::where('userId', $userId);
        $user = $query->with('customer', 'permissions')->first();

        if (!$user) {
            return $this->respondWithNotFound();
        }

        if ($this->isBrandAdmin()) {
            $adminBrand = $this->request->get('user')->brandAdminFor;
            $userBrand = $user->brandAdminFor;
            if ($adminBrand !== $userBrand) {
                return $this->respondWithNotFound();
            }
        }

        if (!$user->isAdmin) {
            return $this->addReturnToken(new Auth(['user_id' => $user->id]))
                ->transformItem($user, ['customer', 'permissions'])->respond();
        } else {
            return $this->respondWithForbidden('You can not alias as another admin / super');
        }

    }
}
