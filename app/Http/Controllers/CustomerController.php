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
 * @copyright
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PendingCommand;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

/**
 * CustomerController
 * @package App\Http\Controllers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class CustomerController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        return $this->transformItem(
            Customer::where('customerId', $this->request->get('user')->customerId)->first()
        )->respond();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function showAction(int $id): JsonResponse
    {
        $customer = Customer::where('customerId', $id)
                            ->first();

        if (!$customer) {
            return $this->respondWithNotFound('Customer does not exist');
        }

        return $this->transformItem($customer)
                    ->respond();
    }

    /**
     * @return JsonResponse
     */
    public function updateAction(): JsonResponse
    {
        $customer = Customer::where('customerId', $this->request->get('user')->customerId)
                            ->first();

        if (!$customer) {
            return $this->respondWithNotFound('Customer does not exist');
        }

        $customer->showMapSpeed = $this->request->get('show_map_speed');
        $customer->save();

        return $this->transformItem($customer)
                    ->respond();
    }

    public function languageAction($customerId)
    {
        $customer = Customer::where('customerId', $this->request->get('user')->customerId)
            ->first();

        if ($this->isAdmin()) {
            //Admins don't need permission to get the customer in question
            $customer = Customer::where('customerId', $customerId)
                ->first();
        }

        if (!$customer) {
            return $this->respondWithNotFound('Customer does not exist.');
        }

        $newCountry = strtolower($this->request->get('language'));

        if ($customer->brand === 'triumph') {
            //Updated country, send command if allowed country
            switch ($newCountry) {
                case 'fr':
                case 'de':
                case 'es':
                case 'it':
                    $countryCode = strtolower($newCountry);
                    break;
                case 'gb':
                case 'en':
                default:
                    $countryCode = 'en';
            }

            if (!$this->isAdmin()) {
                try {
                    $userId = $this->request->get('user')->id;
                    $user = User::where('User.userId', $userId)->first();
                    $user->lang = $countryCode;
                    $user->save();
                } catch (\Exception $ex) {
                    //todo actually catch this
                }
            }


            $customerVehicles = Vehicle::where('Vehicle.customerId', $customer->customerId)->get();

            /**
             * @var Vehicle $vehicle
             */

            foreach ($customerVehicles as $vehicle) {
                $pendingCommand = new PendingCommand();
                $pendingCommand->setEWMText($vehicle, $countryCode, $this->request->get('user')->id);
            }
        }

        return $this->respond();
    }


    public function getCustomersAction($brand) : JsonResponse {
        if ($brand ===''){
            $customers = Customer::all();
        }else {
            $customers = Customer::where('brand', $brand)->get();
        }

        if (!$customers) {
            return $this->respondWithNotFound();
        }

        return $this->transformCollection($customers, null, 'customers')->respond();
    }

    public function findCustomersAction($brand) : JsonResponse
    {
        $where = [];
        $fields = [
            'company',
            'address',
            'postcode',
            'primary_phone',
            'email',
        ];

        foreach ($fields as $field) {
            if ($this->request->has($field) ) {
                $where[$field] = $this->request->get($field);
            }
        }

        if (count($where) === 0) {
            //No field present, throw error
            return $this->respondWithInvalidRequest('At least one searchable field must be added: company, address, postcode, primary_phone, email');
        }

        if ($brand){
            $where['brand'] = $brand;
        }

        $customers = Customer::where($where)->get();

        return $this->transformCollection($customers, null, 'customers')->respond();
    }


    public function getCustomerAction($customerId, $brand) : JsonResponse {
        if (!$brand){
            $customer = Customer::where('customerId', $customerId)->first();
        }else {
            $customer = Customer::where('brand', $brand)->where("customerId", $customerId)->first();
        }

        return $this->transformItem($customer, null, 'customer')->respond();
    }

    public function createCustomerAction($brand)
    {
        if ($this->request->has('email')) {
            $email = $this->request->get('email');
            $checkCustomer = Customer::where('email',$email)->first();
            if ($checkCustomer !== null) {
                return $this->respondWithInvalidRequest("Customer with email address already exists.");
            }
        }

        $customer = new Customer();
        if ($this->request->has('company') &&
            $this->request->has('address') &&
            $this->request->has('postcode') &&
            $this->request->has('primary_phone') &&
            $this->request->has('email')){
            $customer->company = $this->request->get('company');
            $customer->address = $this->request->get('address');
            $customer->postcode = $this->request->get('postcode');
            $customer->primaryPhone = $this->request->get('primary_phone');
            $customer->email = $this->request->get('email');
            $customer->brand = $brand;
            $customer->save();
            return $this->transformItem($customer)->respond();
        } else {
            return $this->respondWithInvalidRequest("Must have company, address, postcode, primary_phone and email.
             Optional fields are address2, address3, county, country, fax and description");
        }
    }

    public function updateCustomerAction($customerId, $brand)
    {
        $customer = Customer::where('brand', $brand)->where('customerId', $customerId)->first();
        if (!$customer) {
            return $this->respondWithNotFound();
        }
        if ($this->request->has('company')) {
            $customer->company = $this->request->get('company');
        }
        if ($this->request->has('address')) {
            $customer->address = $this->request->get('address');
        }
        if ($this->request->has('address2')) {
            $customer->address2 = $this->request->get('address2');
        }
        if ($this->request->has('address3')) {
            $customer->address3 = $this->request->get('address3');
        }
        if ($this->request->has('county')) {
            $customer->county = $this->request->get('county');
        }
        if ($this->request->has('postcode')) {
            $customer->postcode = $this->request->get('postcode');
        }
        if ($this->request->has('country')) {
            $customer->country = $this->request->get('country');
        }
        if ($this->request->has('email')) {
            $customer->email = $this->request->get('email');
        }
        if ($this->request->has('primary_phone')) {
            $customer->primaryPhone = $this->request->get('primary_phone');
        }
        if ($this->request->has('description')) {
            $customer->description = $this->request->get('description');
        }
        if ($this->request->has('fax')) {
            $customer->fax = $this->request->get('fax');
        }
        $customer->save();
        return $this->transformItem($customer)->respond();
    }
}
