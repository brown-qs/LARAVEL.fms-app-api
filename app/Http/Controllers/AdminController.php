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

//use App\Support\Auth;
use App\Models\Brand;
use App\Models\CatType;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Vehicle;
use App\Support\Facades\EmailAPI;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;


/**
 * DriverController
 *
 * @package App\Http\Controllers
 * @author
 */
class AdminController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        return $this->appendBody('data', [
            'api_url'     => Config::get('app.url'),
            'api_version' => Config::get('app.apiVersion'),
            'api_debug'   => Config::get('app.debug'),
            'api_env'     => Config::get('app.env'),
            'api_locale'  => Config::get('app.locale'),
            'version'     => Config::get('app.apiVersion'),
            'debug'       => Config::get('app.debug'),
            'environment' => Config::get('app.env'),
        ])->respond();
    }


    public function convertUnitId($unitId): JsonResponse
    {
        $this->appendBody('unitId', getInternalUnitID($unitId));
        return $this->respond();
    }

    public function aliasUserAction($userId): JsonResponse
    {
        if (!$this->isAdmin() && !$this->isBrandAdmin()) {
            return $this->withRequest($this->request)
                        ->respondWithForbidden("You don't have sufficient permission to view this.");
        }
        return app('App\Http\Controllers\AuthController')->mockUser($userId);
    }


    public function getCustomersAction()
    {
        return app('App\Http\Controllers\CustomerController')->getCustomersAction($this->getBrand());
    }

    public function findCustomersAction()
    {
        return app('App\Http\Controllers\CustomerController')->findCustomersAction($this->getBrand());
    }

    public function getUsersForCustomerAction($customerId)
    {
        return app('App\Http\Controllers\UserController')->getUsersForCustomerAction($customerId, $this->getBrand());
    }

    public function getVehiclesForCustomerAction($customerId)
    {
        return app('App\Http\Controllers\VehicleController')->getVehiclesForCustomerAction($customerId, $this->getBrand());
    }

    public function getVehicleForCustomerAction($customerId, $vehicleId)
    {
        return app('App\Http\Controllers\VehicleController')->getVehicleForCustomerAction($customerId, $vehicleId, $this->getBrand());
    }

    public function createCustomerAction()
    {
        return app('App\Http\Controllers\CustomerController')->createCustomerAction($this->getBrand());
    }

    public function updateCustomerAction($customerId)
    {
        return app('App\Http\Controllers\CustomerController')->updateCustomerAction($customerId, $this->getBrand());
    }

    public function getCustomerAction($customerId)
    {
        return app('App\Http\Controllers\CustomerController')->getCustomerAction($customerId, $this->getBrand());
    }

    public function createVehicleAction()
    {
        return app('App\Http\Controllers\VehicleController')->createVehicleAction();
    }

    public function updateVehicleAction($vehicleId)
    {
        return app('App\Http\Controllers\VehicleController')->updateVehicleAction($vehicleId);
    }

    public function deleteVehicleAction($vehicleId)
    {
        return app('App\Http\Controllers\VehicleController')->deleteVehicleAction($vehicleId);
    }

    public function restockUnitAction($unitId)
    {
        return app('App\Http\Controllers\VehicleController')->restockUnitAction($unitId);
    }

    public function activateVehicleAction($vehicleId)
    {
        return app('App\Http\Controllers\VehicleController')->activateVehicleAction($vehicleId);
    }

    public function deactivateVehicleAction($vehicleId)
    {
        return app('App\Http\Controllers\VehicleController')->deactivateVehicleAction($vehicleId);
    }

    public function isActiveVehicleAction($vehicleId)
    {
        return app('App\Http\Controllers\VehicleController')->isActiveVehicleAction($vehicleId);
    }

    public function sendUnitCertificateAction($unitId)
    {
        $vehicle = Vehicle::where('Vehicle.unitId', $unitId)->get();

        if (!$vehicle || !$vehicle[0]->vehicleId) {
            return $this->respondWithNotFound('Failed to find linked vehicle to that unit ID');
        }
        return $this->sendVehicleCertificateAction($vehicle[0]->vehicleId);
    }

    public function sendVehicleCertificateAction($vehicleId)
    {
        $vehicle = Vehicle::join("Customer", "Customer.customerId", "=", "Vehicle.customerId")
                ->where('Vehicle.vehicleId', $vehicleId)
                ->with("unit.subscription", "customer")
                ->first();

        if (!$vehicle) {
            return $this->respondWithNotFound('Vehicle not found');
        }

        if (!$vehicle->unit) {
            return $this->respondWithNotFound('No unit on this vehicle');
        }

        if ($vehicle->unit->unitId === 0) {
            return $this->respondWithNotFound('No unit on this vehicle');
        }

        $catType = CatType::getCatType($vehicle, $vehicle->customer->brand);

        if (!$vehicle->unit->subscription) {
            return $this->respondWithNotFound('Vehicle does not have a valid subscription');
        }

        if ($vehicle->unit->subscription->monitored !== true) {
            return $this->respondWithNotFound('Only monitored vehicles are eligible for certificates');
        }

        $pdf = app()->make('dompdf.wrapper');

        try {
            $dt = new Carbon($vehicle->unit->subscription->subStart, "UTC");
            $dt->timezone($vehicle->customer->timeZone);
            $subDateIssued = $dt->format("d/m/Y");
        } catch (Exception $e) {
            $subDateIssued = $vehicle->unit->subscription->subStart;
        }

        try {
            $dt = new Carbon($vehicle->unit->subscription->subEnd, "UTC");
            $dt->timezone($vehicle->customer->timeZone);
            $subDateExpires = $dt->format("d/m/Y");
        } catch (Exception $e) {
            $subDateExpires = $vehicle->unit->subscription->subEnd;
        }

        $pdf->loadView('admin.vehicle.certificate', [
            "vehicle"        => $vehicle,
            "unit"           => $vehicle->unit,
            "catType"        => $catType,
            "customer"       => $vehicle->customer,
            "subDateIssued"  => $subDateIssued,
            "subDateExpires" => $subDateExpires,
            "brand"          => $vehicle->customer->brand,
        ])->setPaper('a4', 'portrait');
        $filename = storage_path('app/YourTrackerCertificate-'.$vehicle->vehicleId.'.pdf');
        $pdf->save($filename);
        EmailAPI::customerCertificate(["to" => [$vehicle->customer->email]], $vehicle->customer->company, $vehicle->registration, $filename, $vehicle->customer->brand);
        unlink($filename);
        return $this->respond();
    }

    public function getVehicleCertificateAction($vehicleId)
    {
        $authUser = $this->request->get('user');
        $brand    = $this->getBrandAdmin();
        if (!is_null($brand)) {
            $vehicle = Vehicle::join("Customer", "Customer.customerId", "=", "Vehicle.customerId")
                              ->where('Vehicle.vehicleId', $vehicleId)
                              ->where('Customer.brand', $this->getBrandAdmin())
                              ->with("unit.subscription", "customer")
                              ->first();
            if (!$vehicle) {
                return $this->respondWithNotFound();
            }
        } else {
            $vehicle = Vehicle::where('Vehicle.vehicleId', $vehicleId)
                              ->with("unit.subscription", "customer")
                              ->first();
            $brand   = Brand::BRAND_FLEET_STR;
        }

        $catType = CatType::getCatType($vehicle, $brand);

        $pdf = app()->make('dompdf.wrapper');

        try {
            $dt = new Carbon($vehicle->unit->subscription->subStart, "UTC");
            $dt->timezone($authUser->timeZone);
            $subDateIssued = $dt->format("d/m/Y");
        } catch (Exception $e) {
            $subDateIssued = $vehicle->unit->subscription->subStart;
        }

        try {
            $dt = new Carbon($vehicle->unit->subscription->subEnd, "UTC");
            $dt->timezone($authUser->timeZone);
            $subDateExpires = $dt->format("d/m/Y");
        } catch (Exception $e) {
            $subDateExpires = $vehicle->unit->subscription->subEnd;
        }

        $pdf->loadView('admin.vehicle.certificate', [
            "vehicle"        => $vehicle,
            "unit"           => $vehicle->unit,
            "catType"        => $catType,
            "customer"       => $vehicle->customer,
            "subDateIssued"  => $subDateIssued,
            "subDateExpires" => $subDateExpires,
            "brand"          => $brand,
        ])->setPaper('a4', 'portrait');
        return $pdf->download('invoice.pdf');
    }

    function fetch() {
        $request = $this->request;
        if (!$request->has(['type', 'value'])) {
            return 'type and/or value missing';
        }

        $types = array('vehicle', 'unit', 'customer');
        $type = $this->request->input('type');
        $value = $this->request->input('value');
        if (!in_array($type, $types)) {
            return 'type must be: ' . implode(', ', $types);
        }

        $result = (object)[
            "customer" => null,
            "units" => null,
            "vehicles" => null,
            "type"=> null,
            "result" => null
        ];

        switch ($type) {
            case 'vehicle':
                $vehicle = Vehicle::where('Vehicle.vehicleId', $value)->first();

                if (!$vehicle) {
                    return $type . ' with id ' . $value . ' not found.';
                }
                //load the customer
                $customer = Customer::where('Customer.customerId', $vehicle->customerId)->first();
                $customerVehicles = Vehicle::where('Vehicle.customerId', $customer->customerId)->get();
                $customerUnits = Unit::select('Unit.*')->join('Vehicle', 'Vehicle.unitId', '=' ,'Unit.unitId')
                    ->where('Vehicle.customerId', $vehicle->customerId)->get();




                $this->appendBody('customer', $customer);
                $this->appendBody('vehicles', $customerVehicles);
                $this->appendBody('units', $customerUnits);
                $this->appendBody('type', $type);
                $this->appendBody('result', $vehicle);

                return $this->respond();

            case 'unit':


                return 'unit';


            case 'customer':
                return 'customer';
        }






        //check it has a valid type
        //check it has a value



        //switch case on how to load data
        //vehicles
        //units
        //customer

        //special case, load that specific








        return 'asd';
    }


    function searchElastic()  {
        $client = new Client([
            'base_uri' => 'https://esearch.scorpiontrack.com/',
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        $query = $this->request->input('query');
        $numeric = is_numeric($query) ? $query : '';
        $wildcardQuery = '{
                  "query" : {
                    "bool": {
                      "should": [
                        {
                          "query_string": {
                            "query": "*' . $query . '*",
                            "fields": ["registration","email", "make", "model", "company","primaryphone^6"]
                          }
                        }
                      ]
                    }
                  }
                }';
        $indexQuery = '{
                  "query" : {
                    "bool": {
                      "should": [
                        {
                          "query_string": {
                            "query": "'.$numeric.'",
                            "fields": ["unitid", "vehicleid","customerid","tagid"]
                          }
                        }
                      ]
                    }
                  }
                }';

        $lookupQuery = is_numeric($query) ? $indexQuery : $wildcardQuery;

        $response = $client->request('GET', '_all/_search',
            [
                'auth' => [
                    'admin',
                    'Scorpion123!'
                ],
                'body' => $lookupQuery
            ]);

        if ($response->getBody()) {
            $value = $response->getBody()->getContents();
            $returnVal = json_decode($value);
            return response()->json($returnVal);
        }
    }
}



