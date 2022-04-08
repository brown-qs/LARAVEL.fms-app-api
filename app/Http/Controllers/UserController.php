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

use App\Models\CommunicationPreferences;
use App\Models\CommunicationType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * UserController
 *
 * @package App\Http\Controllers
 * @author  Tariq Tamuji <tariq@hare.digital>
 */
class UserController extends AbstractApiController
{

    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $users = User::where('customerId', $this->request->get('user')->customerId)
                     ->orderBy('lastActive', 'DESC');

        if ($this->request->has('search')) {
            $users = $users->where('firstName', 'like', '%' . $this->request->get('search') . '%')
                           ->orWhere('lastName', 'like', '%' . $this->request->get('search') . '%')
                           ->orWhere('mobilePhone', 'like', '%' . $this->request->get('search') . '%')
                           ->orWhere('email', 'like', '%' . $this->request->get('search') . '%');
        }

        $users = $users->paginate($this->request->get('limit') ?? Config::get('app.paginateDefault'));

        return $this->transformCollection($users)
                    ->respond();
    }

    /**
     * @param int $userId
     *
     * @return JsonResponse
     *
     */
    public function showAction(int $userId): JsonResponse
    {
        $user = User::where('userId', $userId)
                    ->where('customerId', $this->request->get('user')->customerId)
                    ->first();

        if (!$user) {
            return $this->respondWithNotFound('User not found');
        }

        return $this->transformItem($user, ['permissions'])
                    ->respond();
    }


    /**
     * @param int $userId
     *
     * @return JsonResponse
     *
     */
    public function updatePassword(int $userId) : JsonResponse
    {
        $jsonId = $this->request->get('user')->id;
        if ($jsonId !== $userId) {
            return $this->respondWithInvalidRequest("You may only change your own password");
        }

        if (!$this->request->has("current_pwd") || !$this->request->has("new_pwd")) {
            return $this->respondWithInvalidRequest("current_pwd and new_pwd must be set");
        }

        $currentPwd = $this->request->get("current_pwd");
        $newPwd = $this->request->get("new_pwd");

        $user = User::where('userId', $jsonId)->with('customer', 'permissions')->first();
        if (!$user->isValidPassword($currentPwd)) {
            return $this->respondWithForbidden('Incorrect Password');
        } else {
            $hash = scorpion_password_hash($newPwd, $user->salt);
            $user->password = $hash;
            $user->save();
            return $this->respond();
        }
    }

    /**
     * @param int $userId
     *
     * @return JsonResponse
     *
     */
    public function updateAction(int $userId): JsonResponse
    {
        $user = User::where('userId', $userId)
                    ->where('customerId', $this->request->get('user')->customerId)
                    ->first();

        if (!$user) {
            return $this->respondWithNotFound('User not found');
        }

        if ($this->request->get('user')->type !== "CustomerSuper" && $this->request->get('user')->userId !== $userId) {
            return $this->respondWithForbidden("You don't have permission to edit that user");
        }

        /**
         * EDITABLE FIELDS :
         *
         * first_name
         * last_name
         * email
         * timezone
         * mobile_phone
         * distance_units
         * volume_units
         * security_question
         * security_answer
         * cookie_policy
         * privacy_policy
         * terms_policy
         */

        if ($this->request->has('first_name')) {
            $user->firstName = $this->request->get('first_name');
        }

        if ($this->request->has('last_name')) {
            $user->lastName = $this->request->get('last_name');
        }

        if ($this->request->has('email')) {
            $user->email = $this->request->get('email');
        }

        if ($this->request->has('timezone')) {
            $user->timezone = $this->request->get('timezone');
        }

        if ($this->request->has('mobile_phone')) {
            $user->mobilePhone = $this->request->get('mobile_phone');
        }

        if ($this->request->has('distance_units')) {
            $user->distanceUnits = $this->request->get('distance_units');
        }

        if ($this->request->has('volume_units')) {
            $user->volumeUnits = $this->request->get('volume_units');
        }

        if ($this->request->has('security_question')) {
            $user->securityQuestion = $this->request->get('security_question');
        }

        if ($this->request->has('security_answer')) {
            $user->securityAnswer = $this->request->get('security_answer');
        }

        if ($this->request->has('cookie_policy')) {
            $user->cookieAccepted = $this->request->get('cookie_policy');
        }

        if ($this->request->has('privacy_policy')) {
            $user->privacyPolicyAccepted = $this->request->get('privacy_policy');
        }

        if ($this->request->has('terms_policy')) {
            $user->termsAccepted = $this->request->get('terms_policy');
        }

        // Save the updated user
        $user->save();

        return $this->transformItem($user)
                    ->respond();
    }

    public function getCommunicationPreferences()
    {
        $userId = $this->request->get('user')->userId;
        $availablePreferences = CommunicationType::all();
        $userPreferences = CommunicationPreferences::where('userId', $userId)->with("communicationType")->get();

        $upIds = [];
        foreach ($userPreferences as $preference) {
            array_push($upIds, $preference->typeId);
        }
        $avIds = [];
        foreach ($availablePreferences as $preference) {
            array_push($avIds, $preference->typeId);
        }
        $missingPreferences = array_diff($avIds, $upIds);

        foreach ($missingPreferences as $missingId) {
            $insert = new CommunicationPreferences();
            $insert->setUserId($userId);
            $insert->setTypeId($missingId);
            $insert->save();
        }

        $userPreferences = CommunicationPreferences::where('userId', $userId)->get();

        return $this->transformCollection($userPreferences, [
            'communicationType'], 'typeId')
            ->respond();
    }

    public function saveCommunicationPreferences()
    {
        $userId = $this->request->get('user')->userId;
        $preferencesId = $this->request->get('preferencesId');
        $userPreferences = CommunicationPreferences::where('userId', $userId)->where('preferencesId', $preferencesId)->first();

        if ($userPreferences) {
            $userPreferences->email = $this->request->get('email');
            $userPreferences->sms = $this->request->get('sms');
            $userPreferences->push = $this->request->get('push');
            $userPreferences->save();
        }
        return $userPreferences;
    }

    public function getUsersForCustomerAction($customerId, $brand)
    {
        if ($brand !== '') {
            $users = User::where('brandAdminFor', $brand)->where('customerId', $customerId)->get();
        } else {
            $users = User::where('customerId', $customerId)->get();
        }

        return $this->transformCollection($users, null,'users')->respond();
    }

}
