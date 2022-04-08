<?php declare(strict_types=1);

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => 'config', 'as' => 'config'], function () use ($router) {
    $router->get('', 'ConfigController@indexAction');
    $router->get('timezones', 'ConfigController@timezonesAction');
});

$router->group(['prefix' => 'debug', 'as' => 'debug'], function () use ($router) {
    $router->get('config', 'DebugController@configAction');
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    /*
     * Auth endpoints
     * */
    $router->post('/auth/login', [
        'as'   => 'auth.login',
        'uses' => 'AuthController@loginAction',
    ]);

    /*
    * Auth endpoints
    * */
    $router->get('/lastseen/{unitId}', [
        'as'   => 'lastSeen',
        'uses' => 'VehicleController@lastSeen',
    ]);

    $router->post('/installstatus/{unitId}', [
        'as'   => 'installStatus',
        'uses' => 'VehicleController@installStatus',
    ]);

    $router->get('{vehicleId}/journeys/{startTime}/{endTime}', [
        'as'   => 'show-journey',
        'uses' => 'VehicleController@showJourneyAction',
    ]);

    $router->get('/vehicles/marker.png', 'VehicleController@generateMarkerIconAction');

    /*
     *  Customer Authenticated required endpoints
     * */
    $router->group(['middleware' => ['throttle:300,1', 'jwt', 'user']], function () use ($router) {

        /*
         * Vehicle group endpoints
         * */
        $router->group(['prefix' => 'vehicle-groups', 'as' => 'vehicle-groups'], function () use ($router) {
            $router->get('', 'VehicleGroupController@indexAction');
            $router->post('', 'VehicleGroupController@createAction');
            $router->get('{vehicleGroupId}', ['as' => 'show', 'uses' => 'VehicleGroupController@showAction']);
            $router->delete('{vehicleGroupId}', ['as' => 'delete', 'uses' => 'VehicleGroupController@deleteAction']);
            $router->put('{vehicleGroupId}', ['as'   => 'update', 'uses' => 'VehicleGroupController@updateAction']);
        });

        /*
         * Vehicle endpoints
         * */
        $router->group(['prefix' => 'vehicles', 'as' => 'vehicles'], function () use ($router) {
            $router->get('', 'VehicleController@indexAction');
            $router->get('poll', 'VehicleController@pollAction');

            $router->get('{vehicleId}', [
                'as'   => 'show',
                'uses' => 'VehicleController@showAction',
            ]);

            $router->get('{vehicleId}/key', [
                'as'   => 'get-key',
                'uses' => 'VehicleController@getKey',
            ]);

            $router->post('{vehicleId}/key', [
                'as'   => 'generate-key',
                'uses' => 'VehicleController@generateKey',
            ]);

            $router->delete('{vehicleId}/key', [
                'as'   => 'delete-key',
                'uses' => 'VehicleController@removeKey',
            ]);

            $router->get('{vehicleId}/coupons', [
                'as'   => 'coupon',
                'uses' => 'VehicleController@getCoupons',
            ]);

            $router->put('{vehicleId}', [
                'as'   => 'update',
                'uses' => 'VehicleController@updateAction',
            ]);

            $router->get('{vehicleId}/journeys', [
                'as'   => 'show-journeys',
                'uses' => 'VehicleController@paginateJourneysAction',
            ]);

            $router->post('{vehicleId}/incidents', [
                'as'   => 'incidents',
                'uses' => 'VehicleController@incidentsAction',
            ]);

            $router->get('{vehicleId}/journeys/{startTime}/{endTime}', [
                'as'   => 'show-journey',
                'uses' => 'VehicleController@showJourneyAction',
            ]);

            $router->get('{vehicleId}/positions/{time}', [
                'as'   => 'show-position',
                'uses' => 'VehicleController@showPositionAction',
            ]);

            $router->get('{vehicleId}/positions/{startTime}/{endTime}', [
                'as'   => 'show-positions',
                'uses' => 'VehicleController@showPositionsAction',
            ]);

            $router->put('{vehicleId}/gsense', [
                'as'   => 'update-gsense',
                'uses' => 'VehicleController@updateGsenseAction',
            ]);

            $router->put('{vehicleId}/modes', [
                'as'   => 'update-mode',
                'uses' => 'VehicleController@updateModeAction',
            ]);

            $router->put('{vehicleId}/no-alerts/disable', [
                'as'   => 'disable-alerts',
                'uses' => 'VehicleController@disableNoAlertsAction',
            ]);

            $router->put('{vehicleId}/no-alerts/enable', [
                'as'   => 'enable-alerts',
                'uses' => 'VehicleController@enableNoAlertsAction',
            ]);

            $router->put('{vehicleId}/command', [
                'as'   => 'send-command',
                'uses' => 'CommandController@sendCommand',
            ]);

            $router->put('{vehicleId}/command/cancel', [
                'as'   => 'send-command',
                'uses' => 'CommandController@cancelCommand',
            ]);

            $router->get('{vehicleId}/health-checks', [
                'as'   => 'health-check',
                'uses' => 'HealthCheckController@getByVehicleAction',
            ]);

            $router->put('{vehicleId}/set-driver', [
                'as'   => 'set-driver',
                'uses' => 'VehicleController@setDriverAction',
            ]);
        });


        /*
         * OTA endpoints
         */
        $router->group(['prefix' => 'ota', 'as' => 'ota'], function () use ($router) {
            $router->get('', 'OTAController@indexAction');

            $router->get('{vehicleId}/checkFirmwareUpdate', [
                'as'   => 'checkFirmwareUpdate',
                'uses' => 'OTAController@checkFirmwareUpdate',
            ]);

            $router->get('{vehicleId}/startUpdateFirmware', [
                'as'   => 'startUpdateFirmware',
                'uses' => 'OTAController@startUpdateFirmware',
            ]);
        });

        /*
         * Journey group endpoints
         * */
        $router->group(['prefix' => 'journeys', 'as' => 'journeys'], function () use ($router) {
            $router->get('', 'JourneyController@paginateAction');
            $router->get('/calendar/{year}/{month}', 'JourneyController@calendarAction');
        });

        /*
         * Journey group endpoints
         * */
        $router->group(['prefix' => 'geofences', 'as' => 'geofences'], function () use ($router) {
            $router->get('', 'GeofenceController@indexAction');
            $router->post('', 'GeofenceController@createAction');
            $router->get('lookup', 'GeofenceController@lookupAction');
            $router->get('{geofenceId}', 'GeofenceController@showAction');
            $router->put('{geofenceId}', 'GeofenceController@updateAction');
        });

        /*
         * Driver endpoints
         * */
        $router->group(['prefix' => 'drivers', 'as' => 'drivers'], function () use ($router) {
            $router->get('', 'DriverController@indexAction');

            $router->get('{driverId}', [
                'as'   => 'show',
                'uses' => 'DriverController@showAction',
            ]);

            $router->get('{driverId}/journeys', [
                'as'   => 'show-journeys',
                'uses' => 'DriverController@paginateJourneysAction',
            ]);

            $router->get('{driverId}/journeys/{startTime}/{endTime}', [
                'as'   => 'show-journey',
                'uses' => 'DriverController@showJourneyAction',
            ]);
        });

        /*
         * User endpoints
         * */
        $router->group(['prefix' => 'users', 'as' => 'users'], function () use ($router) {
            $router->get('', 'UserController@indexAction');

            $router->get('{userId}', [
                'as'   => 'show',
                'uses' => 'UserController@showAction',
            ]);

            $router->put('{userId}', [
                'as'   => 'update',
                'uses' => 'UserController@updateAction',
            ]);

            $router->put('{userId}/update-password', [
                'as'   => 'update',
                'uses' => 'UserController@updatePassword',
            ]);

        });

        $router->group(['prefix' => 'user', 'as' => 'user'], function () use ($router) {
            $router->get('communication-preferences/', [
                'as'   => 'get',
                'uses' => 'UserController@getCommunicationPreferences',
            ]);

            $router->post('communication-preferences', [
                'as'   => 'save',
                'uses' => 'UserController@saveCommunicationPreferences',
            ]);
        });


        /*
         * Alert endpoints
         * */
        $router->group(['prefix' => 'alerts', 'as' => 'alerts'], function () use ($router) {

            // Index
            $router->get('', 'AlertController@indexAction');

            // Create
            $router->post('', [
                'as'   => 'create',
                'uses' => 'AlertController@createAction',
            ]);

            // Show
            $router->get('{alertId}', [
                'as'   => 'show',
                'uses' => 'AlertController@showAction',
            ]);

            // Edit
            $router->put('{alertId}', [
                'as'   => 'edit',
                'uses' => 'AlertController@editAction',
            ]);

            // Delete
            $router->delete('{alertId}', [
                'as'   => 'delete',
                'uses' => 'AlertController@deleteAction',
            ]);

        });

        /*
         * Alert Event endpoints
         * */
        $router->group(['prefix' => 'alert-events', 'as' => 'alert-events'], function () use ($router) {

            // Index
            $router->get('', 'AlertEventController@indexAction');

            // Show
            $router->get('{alertEventId}', [
                'as'   => 'show',
                'uses' => 'AlertEventController@showAction',
            ]);

            // Mark as Read
            $router->post('mark-read', [
                'as'   => 'mark-read',
                'uses' => 'AlertEventController@markAsReadAction',
            ]);

        });

        /*
         * Customer endpoints
         * */
        $router->group(['prefix' => 'customers', 'as' => 'customers'], function () use ($router) {
            $router->get('', 'CustomerController@indexAction');
            $router->put('', 'CustomerController@updateAction');
            $router->post('{customerId}/language', 'CustomerController@languageAction');
        });

        $router->group(['middleware' => ['user.brandadmin']], function () use ($router) {
            /*
            * Admin group endpoints
            */
            $router->group(['prefix' => 'admin', 'as' => 'admin'], function () use ($router) {
                $router->get('', 'AdminController@indexAction');
                $router->get('convertUnitId/{unitId}', 'AdminController@convertUnitId');
                $router->get('alias/{userId}', 'AdminController@aliasUserAction');
                $router->get('getCustomers', 'AdminController@getCustomersAction');
                $router->post('findCustomers', 'AdminController@findCustomersAction');
                $router->get('getUsers/{customerId}', 'AdminController@getUsersForCustomerAction');
                $router->get('getVehicles/{customerId}', 'AdminController@getVehiclesForCustomerAction');
                $router->get('getVehicle/{customerId}/{vehicleId}', 'AdminController@getVehicleForCustomerAction');
                $router->post('createCustomer', 'AdminController@createCustomerAction');
                $router->get('getCustomer/{customerId}', 'AdminController@getCustomerAction');
                $router->post('updateCustomer/{customerId}', 'AdminController@updateCustomerAction');
                $router->post('createVehicle', 'AdminController@createVehicleAction');
                $router->post('updateVehicle/{vehicleId}', 'AdminController@updateVehicleAction');
                $router->get('deleteVehicle/{vehicleId}', 'AdminController@deleteVehicleAction');
                $router->get('restockUnit/{unitId}', 'AdminController@restockUnitAction');
                $router->post('activateVehicle/{vehicleId}', 'AdminController@activateVehicleAction');
                $router->post('deactivateVehicle/{vehicleId}', 'AdminController@deactivateVehicleAction');
                $router->get('isActiveVehicle/{vehicleId}', 'AdminController@isActiveVehicleAction');


                $router->get("vehicles/{vehicleId}/certificate", 'AdminController@getVehicleCertificateAction');
            });
        });

        /*
         * Superuser endpoints
         * */
        $router->group(['middleware' => ['user.admin']], function () use ($router) {

            $router->group(['prefix' => 'admin', 'as' => 'admin'], function () use ($router) {
                $router->get("vehicles/{vehicleId}/send-certificate", ['as' => 'vehicle-send-certificate', 'uses' => 'AdminController@sendVehicleCertificateAction']);
                $router->get("units/{unitId}/send-certificate", ['as' => 'unit-send-certificate', 'uses' => 'AdminController@sendUnitCertificateAction']);

                $router->get("search", ['as' => 'admin-search', 'uses' => 'AdminController@searchElastic']);

                $router->post("fetch", ['as' => 'admin-fetch', 'uses' => 'AdminController@fetch']);
            });

            $router->group(['prefix' => 'customers', 'as' => 'customers'], function () use ($router) {
                $router->group(['prefix' => '{customerId}'], function () use ($router) {
                    $router->get('', [
                        'as'   => 'show',
                        'uses' => 'CustomerController@showAction',
                    ]);

                    $router->group(['prefix' => 'vehicles', 'as' => 'vehicles'], function () use ($router) {
                        $router->get('', 'VehicleController@indexAction');
                        $router->get('{vehicleId}', [
                            'as'   => 'show',
                            'uses' => 'VehicleController@showAction',
                        ]);

                        $router->get('{vehicleId}/journeys', 'VehicleController@paginateJourneysAction');

                        $router->get('{vehicleId}/journeys/{startTime}/{endTime}', [
                            'as'   => 'show-journey',
                            'uses' => 'VehicleController@showJourneyAction',
                        ]);
                    });

                    $router->group(['prefix' => 'drivers', 'as' => 'drivers'], function () use ($router) {
                        $router->get('', 'DriverController@indexAction');

                        $router->get('{driverId}', [
                            'as'   => 'show',
                            'uses' => 'DriverController@showAction',
                        ]);

                        $router->get('{driverId}/journeys', 'DriverController@paginateJourneysAction');

                        $router->get('{driverId}/journeys/{startTime}/{endTime}', [
                            'as'   => 'show-journey',
                            'uses' => 'DriverController@showJourneyAction',
                        ]);
                    });

                    /*
                     * Vehicle group endpoints
                     * */
                    $router->group(['prefix' => 'vehicle-groups', 'as' => 'vehicle-groups'], function () use ($router) {
                        $router->get('', 'VehicleGroupController@indexAction');
                        $router->get('{vehicleGroupId}', ['as' => 'show', 'uses' => 'VehicleGroupController@showAction']);
                    });

                    /*
                     * Journey group endpoints
                     * */
                    $router->group(['prefix' => 'journeys', 'as' => 'journeys'], function () use ($router) {
                        $router->get('', 'JourneyController@paginateAction');
                    });

                    /*
                     * Journey group endpoints
                     * */
                    $router->group(['prefix' => 'geofences', 'as' => 'geofences'], function () use ($router) {
                        $router->get('', 'GeofenceController@indexAction');
                        $router->get('{geofenceId}', 'GeofenceController@showAction');
                        $router->post('lookup', 'GeofenceController@lookupAction');
                    });
                });
            });
        });

        /*
         * Install endpoints
         * */
        $router->group(['middleware' => ['user.installer']], function () use ($router) {
            $router->group(['prefix' => 'installer', 'as' => 'installer'], function () use ($router) {
                $router->get('customers', ['as' => 'get-customers', 'uses' => 'InstallerController@getCustomers']);
                $router->post('customers', ['as' => 'create-customer', 'uses' => 'InstallerController@createCustomer']);
                $router->post('customers/user', ['as' => 'create-customer-user', 'uses' => 'InstallerController@createCustomerUser']);
                $router->post('vehicles', ['as' => 'create-vehicle', 'uses' => 'InstallerController@createVehicle']);
                $router->get('tags/{unitId}', ['as' => 'get-tags', 'uses' => 'InstallerController@getTags']);
                $router->get('units/{unitId}', ['as' => 'get-unit', 'uses' => 'InstallerController@getUnit']);
                $router->get('vehicles/options', ['as' => 'get-vts-options', 'uses' => 'InstallerController@driverOptions']);
            });

            $router->group(['prefix' => 'dealership', 'as' => 'dealership'], function () use ($router) {
                $router->get('customers', ['as' => 'get-customers', 'uses' => 'DealershipController@getCustomersAction']);
                $router->get('fitters', ['as' => 'get-fitters', 'uses' => 'DealershipController@getFittersAction']);
            });
        });

    });
});
