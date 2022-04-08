## Docs

To view the docs: http://docs.api2.fleet.scorpiontrack.com/

## Run

Documentation on how to run containers on google cloud can be found here https://gitlab.scorpiontrack.com/web/fleet.scorpiontrack.com#run

`docker-compose up` to run the project.

## Build Docs

To compile and read docs: 

- Make sure `node`, `npm` and `gulp` are installed
- Run command: `npm install && gulp`
- Open `build/docs/index.html`

## Quick Reference

````
+--------+--------------------------------------------------------------------------------+---------------------------------+------------+---------------------------------+---------------------------------------------------------------+
| Method | URI                                                                            | Name                            | Action     | Middleware                      | Map To                                                        |
+--------+--------------------------------------------------------------------------------+---------------------------------+------------+---------------------------------+---------------------------------------------------------------+
| GET    | /config                                                                        | config                          | Controller | api,validate                    | App\Http\Controllers\ConfigController@indexAction             |
| GET    | /config/timezones                                                              | config                          | Controller | api,validate                    | App\Http\Controllers\ConfigController@timezonesAction         |
| POST   | /v1/auth/login                                                                 | auth.login                      | Controller | api,validate                    | App\Http\Controllers\AuthController@loginAction               |
| GET    | /v1/vehicle-groups                                                             | vehicle-groups                  | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleGroupController@indexAction       |
| POST   | /v1/vehicle-groups                                                             | vehicle-groups                  | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleGroupController@createAction      |
| GET    | /v1/vehicle-groups/{vehicleGroupId}                                            | vehicle-groups.show             | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleGroupController@showAction        |
| PUT    | /v1/vehicle-groups/{vehicleGroupId}                                            | vehicle-groups.edit             | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleGroupController@editAction        |
| DELETE | /v1/vehicle-groups/{vehicleGroupId}                                            | vehicle-groups.delete           | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleGroupController@deleteAction      |
| GET    | /v1/vehicles                                                                   | vehicles                        | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleController@indexAction            |
| GET    | /v1/vehicles/{vehicleId}                                                       | vehicles.show                   | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleController@showAction             |
| GET    | /v1/vehicles/{vehicleId}/journeys                                              | vehicles.show-journeys          | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleController@paginateJourneysAction |
| GET    | /v1/vehicles/{vehicleId}/journeys/{startTime}/{endTime}                        | vehicles.show-journey           | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleController@showJourneyAction      |
| GET    | /v1/vehicles/{vehicleId}/positions/{time}                                      | vehicles.show-position          | Controller | api,validate,jwt,user           | App\Http\Controllers\VehicleController@showPositionAction     |
| GET    | /v1/vehicles/{vehicleId}/health-checks                                         | vehicles.health-checks          | Controller | api,validate,jwt,user           | App\Http\Controllers\HealthCheckController@getByVehicleAction |
| GET    | /v1/journeys                                                                   | journeys                        | Controller | api,validate,jwt,user           | App\Http\Controllers\JourneyController@paginateAction         |
| GET    | /v1/geofences                                                                  | geofences                       | Controller | api,validate,jwt,user           | App\Http\Controllers\GeofenceController@indexAction           |
| GET    | /v1/geofences/lookup                                                           | geofences                       | Controller | api,validate,jwt,user           | App\Http\Controllers\GeofenceController@lookupAction          |
| GET    | /v1/geofences/{geofenceId}                                                     | geofences                       | Controller | api,validate,jwt,user           | App\Http\Controllers\GeofenceController@showAction            |
| GET    | /v1/drivers                                                                    | drivers                         | Controller | api,validate,jwt,user           | App\Http\Controllers\DriverController@indexAction             |
| GET    | /v1/drivers/{driverId}                                                         | drivers.show                    | Controller | api,validate,jwt,user           | App\Http\Controllers\DriverController@showAction              |
| GET    | /v1/drivers/{driverId}/journeys                                                | drivers.show-journeys           | Controller | api,validate,jwt,user           | App\Http\Controllers\DriverController@paginateJourneysAction  |
| GET    | /v1/drivers/{driverId}/journeys/{startTime}/{endTime}                          | drivers.show-journey            | Controller | api,validate,jwt,user           | App\Http\Controllers\DriverController@showJourneyAction       |
| GET    | /v1/users                                                                      | users                           | Controller | api,validate,jwt,user           | App\Http\Controllers\UserController@indexAction               |
| GET    | /v1/users/{userId}                                                             | users.show                      | Controller | api,validate,jwt,user           | App\Http\Controllers\UserController@showAction                |
| PUT    | /v1/users/{userId}                                                             | users.update                    | Controller | api,validate,jwt,user           | App\Http\Controllers\UserController@updateAction              |
| GET    | /v1/alerts                                                                     | alerts                          | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertController@indexAction              |
| POST   | /v1/alerts                                                                     | alerts.create                   | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertController@createAction             |
| GET    | /v1/alerts/{alertId}                                                           | alerts.show                     | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertController@showAction               |
| PUT    | /v1/alerts/{alertId}                                                           | alerts.edit                     | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertController@editAction               |
| DELETE | /v1/alerts/{alertId}                                                           | alerts.delete                   | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertController@deleteAction             |
| GET    | /v1/alert-events                                                               | alert-events                    | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertEventController@indexAction         |
| GET    | /v1/alert-events/{alertEventId}                                                | alert-events.show               | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertEventController@showAction          |
| POST   | /v1/alert-events/mark-read                                                     | alert-events.mark-read          | Controller | api,validate,jwt,user           | App\Http\Controllers\AlertEventController@markAsReadAction    |
| GET    | /v1/geocode                                                                    | geocode                         | Controller | api,validate,jwt,user           | App\Http\Controllers\GeocodeController@indexAction            |
| GET    | /v1/customers                                                                  | customers                       | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\CustomerController@indexAction           |
| GET    | /v1/customers/{customerId}                                                     | customers.show                  | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\CustomerController@showAction            |
| GET    | /v1/customers/{customerId}/vehicles                                            | customers.vehicles              | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\VehicleController@indexAction            |
| GET    | /v1/customers/{customerId}/vehicles/{vehicleId}                                | customers.vehicles.show         | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\VehicleController@showAction             |
| GET    | /v1/customers/{customerId}/vehicles/{vehicleId}/journeys                       | customers.vehicles              | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\VehicleController@paginateJourneysAction |
| GET    | /v1/customers/{customerId}/vehicles/{vehicleId}/journeys/{startTime}/{endTime} | customers.vehicles.show-journey | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\VehicleController@showJourneyAction      |
| GET    | /v1/customers/{customerId}/drivers                                             | customers.drivers               | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\DriverController@indexAction             |
| GET    | /v1/customers/{customerId}/drivers/{driverId}                                  | customers.drivers.show          | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\DriverController@showAction              |
| GET    | /v1/customers/{customerId}/drivers/{driverId}/journeys                         | customers.drivers               | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\DriverController@paginateJourneysAction  |
| GET    | /v1/customers/{customerId}/drivers/{driverId}/journeys/{startTime}/{endTime}   | customers.drivers.show-journey  | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\DriverController@showJourneyAction       |
| GET    | /v1/customers/{customerId}/vehicle-groups                                      | customers.vehicle-groups        | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\VehicleGroupController@indexAction       |
| GET    | /v1/customers/{customerId}/vehicle-groups/{vehicleGroupId}                     | customers.vehicle-groups.show   | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\VehicleGroupController@showAction        |
| GET    | /v1/customers/{customerId}/journeys                                            | customers.journeys              | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\JourneyController@paginateAction         |
| GET    | /v1/customers/{customerId}/geofences                                           | customers.geofences             | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\GeofenceController@indexAction           |
| GET    | /v1/customers/{customerId}/geofences/{geofenceId}                              | customers.geofences             | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\GeofenceController@showAction            |
| POST   | /v1/customers/{customerId}/geofences/lookup                                    | customers.geofences             | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\GeofenceController@lookupAction          |
| GET    | /v1/installer/tags/{unitId}                                                    | installer.tags                  | Controller | api,validate,jwt,user,superuser | App\Http\Controllers\InstallerController@getTags              |
+--------+--------------------------------------------------------------------------------+---------------------------------+------------+---------------------------------+---------------------------------------------------------------+
````
