<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
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


use App\Models\AssignedUnits;
use App\Models\Brand;
use App\Models\CatType;
use App\Models\Customer;
use App\Models\Dealership;
use App\Models\Log;
use App\Models\PendingCommand;
use App\Models\Subscription;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Vehicle;
use App\Models\VtsTag;
use App\Support\Facades\EmailAPI;
use App\Helpers\AuxProcessor;
use App\Support\Facades\SMSAPI;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * InstallerController
 *
 * @package App\Http\Controllers
 */
class InstallerController extends AbstractApiController
{
    public const MAX_INPUTS = 2;

    public const DRIVER_OPTIONS = [
        [
            'id' => 0,
            'title' => 'None',
            'allowedAuxConfig' => [
                0,
                1
            ],
        ],
        [
            'id' => 1,
            'title' => 'Touch Key Module',
            'driver_options' => [
                [
                    'id' => 0,
                    'title' => 'No Buzzer / LED'
                ],
                [
                    'id' => 1,
                    'title' => 'Buzzer / LED',
                ],
            ],
            'allowedAuxConfig' => [
                0,
            ]
        ],
        [
            'id' => 2,
            'title' => 'VTS Enabled',
            'driver_options' => [
                [
                    'id' => 0,
                    'title' => 'VTS Only'
                ],
                [
                    'id' => 10,
                    'title' => 'VTS + Wakeup Input'
                ],
                [
                    'id' => 20,
                    'title' => 'VTS + Immobiliser Relay'
                ],
                [
                    'id' => 30,
                    'title' => 'VTS + Immobiliser Relay + Wakeup Input'
                ],
                [
                    'id' => 400,
                    'title' => 'VTS + Dallas'
                ],
                [
                    'id' => 401,
                    'title' => 'VTS + Dallas + Constant Buzzer'
                ],
                [
                    'id' => 410,
                    'title' => 'VTS + Dallas + Wakeup Input '
                ],
                [
                    'id' => 411,
                    'title' => 'VTS + Dallas + Constant Buzzer + Wakeup Input'
                ],
            ],
            'allowedAuxConfig' => [
                0,
            ],
        ],
        [
            'id' => 3,
            'title' => 'Door Open Warning',
            'driver_options' => [],
            'allowedAuxConfig' => [
            ],
        ],
        [
            'id' => 4,
            'title' => 'Door Open Warning With Key Sense',
            'driver_options' => [],
            'allowedAuxConfig' => [
            ],
        ],
        [
            'id' => 5,
            'title' => 'Seat Monitor',
            'driver_options' => [],
            'allowedAuxConfig' => [
            ],
        ],
        [
            'id' => 6,
            'title' => 'Serial Diagnostics',
            'driver_options' => [],
            'allowedAuxConfig' => [
            ],
        ]
    ];

    public const AUX_OPTIONS = [
        [
            'type' => [
                'Input'
            ],
            'name' => 'Aux 0',
            'status_high' => 'ON',
            'status_low' => 'OFF',
            'config_1'=> [
                'No Trigger',
                'Rising Edge',
                'Falling Edge',
                'Rising/Falling Edge'
            ],
            'config_2' => [
                'Pull Up',
            ],
            'port_data' => [
                'On',
                'Off'
            ],
        ],
        [
            'type' => [
                'Input',
                'Output'
            ],
            'name' => 'Aux 1',
            'status_high' => 'ON',
            'status_low' => 'OFF',
            'config_1' => [
                'On Permanently',
                'Off Permanently'
            ],
            'config_2' => [
                'Active Low Output'
            ],
            'port_data' => [
                'On',
                'Off'
            ],
        ],
    ];
    
    /**
     * @param int $unitId
     *
     * @return JsonResponse
     */
    public function getTags(int $unitId): JsonResponse
    {
        if ($this->isAdmin() || $this->isFitter()) {
            $tagsList = VtsTag::where("UnitToVTSTag.unitId", $unitId)
                ->join('UnitToVTSTag', 'UnitToVTSTag.vtsId', '=', 'VTSTag.vtsId')
                ->get();
            return $this->transformCollection($tagsList,null,'vtsTags' )->respond();
        } else {
            return $this->respondWithForbidden("This feature is for admins, fitters and dealers");
        }
    }

    public function getCustomers()
    {
        $authUser = $this->request->get('user');

        $foundDealership = Dealership::select('brand')
            ->where('dealershipId', $authUser->dealershipId)
            ->get()
            ->first();
            
        if ($foundDealership && $foundDealership->brand) {
            $customers = Customer::select(['Customer.customerId', 'company', 'email'])
                ->where('Customer.dealershipId', $authUser->dealershipId)
                ->where(function ($query) {
                    $query->where('Customer.email', 'like', '%'.$this->request->get('query').'%')
                        ->orWhere('Customer.company', 'like', '%'.$this->request->get('query').'%');
                })
                ->get();
        } else {
            $customers = [];
        }

        $this->appendBody('customers', $customers);
        return $this->respond();
    }

    public function getUnit()
    {
        $unitId = $this->request->get('unitId');

        if (strlen($unitId) > 9 && strlen($unitId) < 11) {
            $unitId = substr($unitId, 4);

            while ($unitId[0] == "0") {
                $unitId = substr($unitId, 1);
            }
        }

        if (strlen($unitId) === 12) {
            //Full length of an STM unit, substr the last 6 digits
            $unitId = substr($unitId, 6);
        }

        $selectRaw = DB::raw('Unit.unitId, Unit.type, Vehicle.vehicleId is not null as inUse');

        $unit = Unit::
            select($selectRaw)
            ->join('Vehicle', 'Unit.unitId', '=', 'Vehicle.unitId', 'left outer')
            ->where('Unit.unitId', $unitId)
            ->get()
            ->first();

        if ($unit && $unit->type === CatType::UNIT_TYPE_STM01F) {
            $vtstags = VtsTag::where('unitId', $unit->unitId);
            if (!$vtstags) {
                $unit->type = 'STM02F';
            }
        }

        $this->appendBody('unit', $unit);
        return $this->respond();
    }

    /**
     * first_name
    last_name
    phone
    email
    address
    address_line_2
    address_line
    county
    postcode
    country
     customerId
     */
    public function createCustomerUser()
    {
        $validTypes = ['CustomerSuper', 'Customer'];

        $authUser = $this->request->get('user');
        $customerId = $this->request->get('customer_id');
        $customer = Customer::where('customerId',$customerId)->first();

        if ($customer === null) {
            return $this->respondWithInvalidRequest("No customer found with that ID");
        }

        if ($customer->dealershipId !== $authUser->dealershipId) {
            return $this->respondWithInvalidRequest("This customer does not belong to your dealership.");
        }

        if ($this->request->get('type') && !in_array($this->request->get('type'), $validTypes)) {
            return $this->respondWithInvalidRequest("User type must be Customer or CustomerSuper");
        }


        $userEmail = $this->request->get('email');
        $checkUser = User::where('email',$userEmail)->first();

        if ($checkUser !== null) {
            return $this->respondWithInvalidRequest("That email is already in use by another user");
        }


        $user = new User();
        $user->firstName = $this->request->get('first_name');
        $user->lastName = $this->request->get('last_name');
        $user->mobilePhone = $this->request->get('phone');
        $user->email = $this->request->get('email');

        $password = $this->request->get('password') ?? random_bytes(5);
        $passwordData = $user->hashPassword($password);

        $user->password = $passwordData['password'];
        $user->salt = $passwordData['salt'];
        $user->customerId = $this->request->get('customer_id');
        $user->type = 'CustomerSuper';

        $user->saveOrFail();

        $userDetails = new UserDetails();
        $userDetails->address = $this->request->get('address');
        $userDetails->address2 = $this->request->get('address_line_2');
        $userDetails->address3 = $this->request->get('address_line_3');
        $userDetails->county = $this->request->get('county');
        $userDetails->postcode = $this->request->get('postcode');
        $userDetails->country = $this->request->get('country');
        $userDetails->userId = $user->userId;
        $userDetails->saveOrFail();

        if ($customer->newUserNotify) {
            EmailAPI::sendNewCustomerUserAccountCustomerNotificationEmail(
                $customer,
                $user,
                $customer->brand
            );
        }

        if ($customer->brand === Brand::BRAND_BMW) {
            EmailAPI::sendCustomerWelcomeToDatatool(
                $user,
                $customer
            );
            EmailAPI::sendNewAccountStealthEmail(
                $user,
                $password,
                $customer
            );
        } elseif ($customer->brand !== Brand::BRAND_REWIRE) {
            EmailAPI::sendNewCustomerEmail(
                $customer,
                $user,
                $password
            );
        }


        return $this->transformItem($user)->respond();
    }

    /**
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function createCustomer()
    {
        $email = $this->request->get('email');
        $checkCustomer = Customer::where('email',$email)->first();
        if ($checkCustomer !== null) {
            return $this->respondWithInvalidRequest("Customer with email address already exists.");
        }

        $customer = new Customer();
        $customer->company = $this->request->get('name');
        $customer->address = $this->request->get('address');
        $customer->address2 = $this->request->get('address_line_2');
        $customer->address3 = $this->request->get('address_line_3');
        $customer->county = $this->request->get('county');
        $customer->postcode = $this->request->get('postcode');
        $customer->country = $this->request->get('country');
        $customer->primaryPhone = $this->request->get('phone');
        $customer->fax = $this->request->get('fax');
        $customer->email = $this->request->get('email');

        $customer->newUserNotify = $this->request->get('notification_user_created') ?? true;
        $customer->newDriverNotify = $this->request->get('notification_driver_created') ?? true;

        $customer->brand = $this->request->get('brand');

        $authUser = $this->request->get('user');
        $customer->dealershipId = $authUser->dealershipId;


        $customer->save();
        return $this->transformItem($customer)->respond();

//        $request->get('user')->type
//        return app('App\Http\Controllers\CustomerController')->createCustomerAction($this->getBrand());
    }

    private function _checkInputs(Request $request)
    {
        for ($i = 0; $i < self::MAX_INPUTS; $i++) {
            if ($request->get("aux_{$i}_type") != "Disabled") {

                $inputName = $request->get("aux_{$i}_name");
                if (empty($inputName)) {
                    $string = "The Name field is required.";

                    return [
                        false,
                        $i,
                        $string,
                    ];
                }
                $inputStringOn = $request->get("aux_{$i}_status_text_high");
                if (empty($inputStringOn)) {
                    $string = "The Status Text: On field is required.";

                    return [
                        false,
                        $i,
                        $string,
                    ];
                }
                $inputStringOff = $request->get("aux_{$i}_status_text_low");
                if (empty($inputStringOff)) {
                    $string = "The Status Text: Off field is required.";

                    return [
                        false,
                        $i,
                        $string,
                    ];
                }
            }
        }

        return [
            true,
            $i,
        ];
    }


    /**
     * See fleet for more information about this function
     * @param Request $request
     * @param $auxNumber
     * @return int
     */
    private function _getAuxConfigValue(Request $request, $auxNumber)
    {
        $inputType    = $request->get("aux_{$auxNumber}_Type");
        $inputConfig1 = $request->get("aux_{$auxNumber}_config_1");
        $inputConfig2 = $request->get("aux_{$auxNumber}_config_2");
        $portConfig   = $request->get("aux_{$auxNumber}_port_data");

        $config = 0;

        if ($inputType == AuxProcessor::AUX_TYPE_INPUT) {
            $config = AuxProcessor::BIT_0_INPUT + $config;
        }

        switch ($inputConfig1) {
            case AuxProcessor::AUX_INPUT_CONFIG1_RISING:
                $config = AuxProcessor::BIT_1_RISING + $config;
                break;
            case AuxProcessor::AUX_INPUT_CONFIG1_FALLING:
                $config = AuxProcessor::BIT_2_FALLING + $config;
                break;
            case AuxProcessor::AUX_INPUT_CONFIG1_RISING_AND_FALLING:
                $config = AuxProcessor::BIT_1_RISING + AuxProcessor::BIT_2_FALLING + $config;
                break;
            case AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM:
                $config = AuxProcessor::BIT_8_9_OUT_ON_PERM + $config;
                break;
        }

        switch ($inputConfig2) {
            case AuxProcessor::AUX_INPUT_CONFIG2_PULLUP:
                $config = AuxProcessor::BIT_3_PULLUP + $config;
                break;
            case AuxProcessor::AUX_INPUT_CONFIG2_PULLDOWN:
                $config = AuxProcessor::BIT_4_PULLDOWN + $config;
                break;
        }

        if ($portConfig == AuxProcessor::AUX_PORTDATA_CONFIG_ON) {
            $config = AuxProcessor::BIT_7_PORT + $config;
            $config |= AuxProcessor::BIT_1_RISING;
            $config |= AuxProcessor::BIT_2_FALLING;
            $config |= AuxProcessor::BIT_6_ALERT;
        }

        //Output config removed as it cannot be found in the fleet codebase

        return $config;
    }

    /**
     * @param $vehicle
     * @param bool $new
     */
    private function _storeAuxiliaryCommand($vehicle, $new = false)
    {

        for ($i = 0; $i < self::MAX_INPUTS; $i++) {
            // used to determine if db config flags are different to new value
            $varConfigFlags = "aux" . $i . "ConfigFlags";
            $value          = $this->_getAuxConfigValue($this->request, $i);

            // store command if row changed since db load
            if (isset($vehicle->$varConfigFlags)) {
                if ($value != $vehicle->$varConfigFlags || $new) {
                    switch ($i) {
                        case 0:
                            $command = PendingCommand::AUX_0;
                            break;
                        case 1:
                            $command = PendingCommand::AUX_1;
                            break;
                        case 2:
                            $command = PendingCommand::AUX_2;
                            break;
                        default:
                            return;
                    }

                    if ($vehicle->appId > 503) {
                        if ($command == PendingCommand::AUX_1 && $this->request->has('driver_module')) {
                            // do nothing, trying to edit output when driver ID is in use
                        } else {
                            $hex = dechex($value);
                            $pc = new PendingCommand();
                            $pc->storePendingCommand($vehicle->vehicleId,
                                $command,
                                string_pad($hex, 4, "0"));
                        }
                    }
                }
            }
        }
    }

    private function _storeDriverModuleCommand($vehicle, $driverModule, $buzzer)
    {
        // Disable Seat Module by default
        $pendingCommand = new PendingCommand();

        $unit = Unit::where('unitId', $vehicle->unitId)->first();

        //Rickshaw disable
        $unit->auxConfigType = 0;
        $unit->save();

        switch ($driverModule) {
            case 0: // Disable driver Module
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC, "0,0000");
                break;

            case 1: // Touch Key Module
                $buzzer = ($buzzer == 1) ? "0001" : "0000";
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_1, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC,
                    "1,$buzzer");
                break;

            case 2: // VTS Enabled
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_1, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC, "2,0000");
                break;

            case 3: // Door Open Module
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_0, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_1, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC, "3,000A");
                break;

            case 4: // Door open warning with keysense
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_0, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_1, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC, '4,000A');
                break;

            case 5: // Seat Monitor
                $this->unit_model->toggleRickshaw($vehicle->unitId, 1);
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_0, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_1, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC, "5,0000");
                break;
            case 6: // Serial Diagnostics Module
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::AUX_0, '0000');
                $pendingCommand->storePendingCommand($vehicle->vehicleId, PendingCommand::DRVREC, '6,0000');
                break;
            default:
                break;
        }
    }

    public function driverOptions()
    {
        $modules = self::DRIVER_OPTIONS;
        $auxOptions = self::AUX_OPTIONS;

        $this->appendBody('modules', $modules);
        $this->appendBody('aux_options', $auxOptions);

        return $this->respond();
    }

    public function createVehicle()
    {

        $authUser = $this->request->get('user');
        $customerId = $this->request->get('customer_id');
        $customer = Customer::where('customerId',$customerId)->first();
        /**
         * @var Unit $unit
         */
        $unit = Unit::where('unitId', $this->request->get('unit_id'))->first();

        if ($customer === null) {
            return $this->respondWithInvalidRequest("No customer found with that ID");
        }

        if ($customer->dealershipId !== $authUser->dealershipId) {
            return $this->respondWithInvalidRequest("This customer does not belong to your dealership.");
        }

        if (empty($unit)) {
            return $this->respondWithInvalidRequest('That unit could not be found');
        }

        $vehicle = Vehicle::where('unitId', $unit->unitId)->first();
        if ($vehicle) {
            return $this->respondWithInvalidRequest('That unit is already assigned to a vehicle');
        }

        $assignedUnit = AssignedUnits::where('unitId', $unit->unitId)->first();

        if ($assignedUnit && (
            ($this->request->get('customer_id') === '0' && $assignedUnit->customerId) ||
            ((string)$this->request->get('customer_id') !== (string)$assignedUnit->customerId && $assignedUnit->customerId))
        ) {
            return $this->respondWithInvalidRequest('This unit belongs to another customer');
        }


        //Checking AUX
        if ((!($customer->brand === Brand::BRAND_ADVENTURE) && !($customer->brand === Brand::BRAND_SUBARU)) || ($customer->brand === Brand::BRAND_BMW) ||
            !($customer->brand === Brand::BRAND_TRIUMPH)) {
            $checkResult = $this->_checkInputs($this->request);
        } else {
            $checkResult = [
                0 => true,
            ];
        }

        if ($checkResult[0] === false) {
            return $this->respondWithInvalidRequest("Auxiliary " . $checkResult[1] . " requires more configuration options - " . $checkResult[2]);
        }

        convert_kpl_to_mpg($this->request->get("avg_mpg"));


        $units = get_users_fuel_consumption_units($authUser->distanceUnits);
        if ($units == "kpl") {
            $convertedMPG = convert_kpl_to_mpg($this->request->get("avg_mpg"));
        } else {
            $convertedMPG = $this->request->get("avg_mpg");
        }

        $foundFitter = User::where('dealershipId', $authUser->dealershipId)
            ->where('active', true)
            ->where('userId', $this->request->get("fitter_id"))
            ->where(function ($query) {
                $query->where('type', User::USER_TYPE_FITTER)
                    ->orWhere('type', User::USER_TYPE_DEALER);
            })->get()->first();

        if (!$foundFitter) {
            return $this->respondWithInvalidRequest("The fitter selected is not on your dealership");
        }

        $vehicle = new Vehicle();

        $vehicle->registration = $this->request->get('registration');
        $vehicle->alias = $this->request->get('alias');
        $vehicle->vin = $this->request->get('vin');
        $vehicle->make = $this->request->get('make');
        $vehicle->model = $this->request->get('model');
        $vehicle->colour = $this->request->get('colour');
        $vehicle->type = $this->request->get('type');
        $vehicle->description = $this->request->get('description');
        $vehicle->co2 = $this->request->get('co2') ?? 0;
        $vehicle->avgMpg = $convertedMPG;
        $vehicle->fuelType = $this->request->get('fuel_type');
        $vehicle->unitId = $unit->unitId;
        $vehicle->fitterId = $foundFitter->userId;
        $vehicle->customerId = $customer->customerId;
        $vehicle->installCodeSide = $this->request->get('side_location');
        $vehicle->installCodeTop = $this->request->get('top_location');
        $vehicle->mountingLocation = $this->request->get('mounting_location');
        $vehicle->dealershipId = $authUser->dealershipId;
        $vehicle->driverModule  = $this->request->post("driver_module");
        $vehicle->driverOptions = $this->request->post("driver_options");

        for ($i = 0; $i < self::MAX_INPUTS; $i++) {
            $vehicle->{"aux{$i}Name"}        = $this->request->get("aux_{$i}_name");
            $vehicle->{"aux{$i}StringOn"}    = $this->request->get("aux_{$i}_status_text_high");
            $vehicle->{"aux{$i}StringOff"}   = $this->request->get("aux_{$i}_status_text_low");
            $vehicle->{"aux{$i}ConfigFlags"} = $this->_getAuxConfigValue($this->request, $i);
        }

        if ($customer->brand === Brand::BRAND_ADVENTURE || $customer->brand === Brand::BRAND_TRIUMPH) {
            $vehicle->batteryType = $this->request->get("battery_type");
        }

        if ($customer->brand === Brand::BRAND_OTL) {
            $vehicle->vehicleOwner = $this->request->get('owner');
        }

        $vehicle->saveOrFail();

        $unit->assignUnitToCustomer($unit, $customer);

        // EMAIL FITTER
        EmailAPI::sendFitterAssignedVehicle(
            $vehicle, $vehicle->registration, $foundFitter, $customer
        );

        //Todo
        $this->_storeAuxiliaryCommand($vehicle);

        $appId  = (string)$unit->appId;
        $appId3 = intval(substr($appId, -3));
        if (
            (strlen(strval($appId)) <= 3 || $appId3 <= 312) ||
            $vehicle->driverModule == 2
        ) {
            $this->_storeDriverModuleCommand($vehicle, $this->request->get('driver_module'),
                $this->request->get('vts_options'));
        }

        SMSAPI::sendDefault(
            $foundFitter->mobilePhone,
            "Vehicle {$vehicle->registration} is now ready to be fitted with a " . $customer->brand . " Unit " .
            number_pad(
                $unit->unitId,
                6
            ),
            $customer->customerId
        );

        // Add pending commands for the unit
        if ($customer->invoicedMonthly === 1) {
            // Add a subscription for the unit until the end of the month...
            $subscripion = new Subscription();
            $subscripion->subscribeUntilFirstOfNextMonth($unit->unitId, $customer->customerId);
        }

        $pendingCommand = new PendingCommand();

        $pendingCommand->storePendingCommand(
            $vehicle->vehicleId,
            PendingCommand::CUSTSMS_PHN,
            $customer->primaryPhone
        );
        $pendingCommand->storePendingCommand(
            $vehicle->vehicleId,
            PendingCommand::CUSTSMS_REG,
            $vehicle->registration
        );

        if ($this->request->get('electric_bike') === 'true') {
            $pendingCommand->storePendingCommand(
                $vehicle->vehicleId,
                PendingCommand::POWER,
                'IGNITION'
            );
        }


        $log = new Log();
        $log->log(
            $this->request->get('user'),
            Log::LOG_CREATE,
            Log::TYPE_VEHICLE,
            $vehicle->vehicleId,
            $foundFitter->userId,
            $vehicle->registration . " - " . $foundFitter->firstName . " " . $foundFitter->lastName
        );

        return $this->transformItem($vehicle)
            ->respond();
    }
}
