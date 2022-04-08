# Group Admin


## Convert Unit ID [/v1/admin/convertUnitId/{unitId}]
    
### Convert Unit ID  [GET]

Gets the customers for the admin brand

+ Parameters
    + unitId: 10000 (integer, required) - User ID to become
    
+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "unitId": 12345,
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
      
      
## Alias User [/v1/admin/alias/{userId}]
    
### Alias User [GET]

Mocks the current session as the specified user

+ Parameters
    + userId: 1000 (integer, required) - User ID to become

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        },
        "user": {
          "data": {
            "id": 5209,
            "customer_id": 1000,
            "dealership_id": 1000,
            "type": "Customer",
            "first_name": "user",
            "last_name": "scorpion",
            "full_name": "user scorpion",
            "email": "user@test.com",
            "active": 1,
            "timezone": "Europe\/London",
            "mobile_phone": "+441111111111",
            "last_login": 1579619797,
            "last_active": 1579623678,
            "distance_units": "miles",
            "volume_units": "gallons",
            "security_question": null,
            "cookie_policy": "2020-01-09 09:28:25",
            "privacy_policy": "2020-01-09 09:28:27",
            "terms_policy": "2020-01-09 09:28:27",
            "brand_admin_for": null,
            "links": {
              "self": "http:\/\/localhost:8080\/v1\/users\/5209"
            },
            "customer": {
              "data": {
                "id": 1000,
                "dealership_id": 1000,
                "company": "Scorpion Automotive LTD",
                "address": "Scorpion House, Drumhead Road, Chorley North Business Park",
                "address2": "",
                "address3": "Chorley",
                "county": "Lancashire",
                "postcode": "PR2 1RB",
                "country": "GB",
                "timezone": "Europe\/London",
                "primary_phone": "+441234567",
                "fax": "",
                "email": "development@scorpionauto.com",
                "description": "",
                "texts": 13331,
                "new_user_notify": 0,
                "new_driver_notify": 0,
                "show_map_speed": true,
                "gsense": 0,
                "invoiced_monthly": 0
              }
            },
            "permissions": {
              "data": []
            }
          }
        }
      }
      

## Get Customers [/v1/admin/getCustomers]
    
### Get Customers [GET]

Gets the customers for the admin brand

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "customers": {
          "data": [
            {
              "id": 1000,
              "dealership_id": 1000,
              "company": "Scorpion Automotive LTD",
              "address": "Scorpion House, Drumhead Road, Chorley North Business Park",
              "address2": "",
              "address3": "Chorley",
              "county": "Lancashire",
              "postcode": "PR2 1RB",
              "country": "GB",
              "timezone": "Europe\/London",
              "primary_phone": "+441234567",
              "fax": "",
              "email": "development@scorpionauto.com",
              "description": "",
              "texts": 13331,
              "new_user_notify": 0,
              "new_driver_notify": 0,
              "show_map_speed": true,
              "gsense": 0,
              "invoiced_monthly": 0
            }
          ]
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
      
## Find Customers [/v1/admin/findCustomers]
    
### Find Customers [POST]

Find customers given a certain criteria, at least one option must be specified

+ Request (application/json)
    + Headers
    
            Authorization: Basic {JWT-TOKEN}
        
    + Attributes (Admin Find Customers)
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "customers": {
          "data": [
            {
              "id": 1000,
              "dealership_id": 1000,
              "company": "Scorpion Automotive LTD",
              "address": "Scorpion House, Drumhead Road, Chorley North Business Park",
              "address2": "",
              "address3": "Chorley",
              "county": "Lancashire",
              "postcode": "PR2 1RB",
              "country": "GB",
              "timezone": "Europe\/London",
              "primary_phone": "+441234567",
              "fax": "",
              "email": "development@scorpionauto.com",
              "description": "",
              "texts": 13331,
              "new_user_notify": 0,
              "new_driver_notify": 0,
              "show_map_speed": true,
              "gsense": 0,
              "invoiced_monthly": 0
            }
          ]
        },
        "auth": {
          "data": {
            "token": "{JWT_TOKEN}"
          }
        }
      }
      
      
## Get Users [/v1/admin/getUsers/{customerId}]
    
### Get Users [GET]

Gets the users for a customer for the admin brand

+ Parameters
    + customerId: 10000 (integer, required) - Customer ID to find users for

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "users": {
          "data": [
            {
              "id": 4931,
              "customer_id": 1000,
              "dealership_id": 1000,
              "type": "CustomerSuper",
              "first_name": "customersuper",
              "last_name": "scorpion",
              "full_name": "customersuper scorpion",
              "email": "customersuper@test.com",
              "active": 1,
              "timezone": "Europe\/London",
              "mobile_phone": "+441234567",
              "last_login": 1583750661,
              "last_active": 1583751272,
              "distance_units": "kilometers",
              "volume_units": "litres",
              "security_question": "0",
              "cookie_policy": "2020-02-13 14:31:20",
              "privacy_policy": "2020-01-09 08:33:24",
              "terms_policy": "2020-02-06 09:14:17",
              "brand_admin_for": "fleet",
              "links": {
                "self": "http:\/\/localhost:8080\/v1\/users\/4931"
              }
            },
            {
              "id": 5209,
              "customer_id": 1000,
              "dealership_id": 1000,
              "type": "Customer",
              "first_name": "user",
              "last_name": "scorpion",
              "full_name": "user scorpion",
              "email": "user@test.com",
              "active": 1,
              "timezone": "Europe\/London",
              "mobile_phone": "+441111111111",
              "last_login": 1579619797,
              "last_active": 1579623678,
              "distance_units": "miles",
              "volume_units": "gallons",
              "security_question": null,
              "cookie_policy": "2020-01-09 09:28:25",
              "privacy_policy": "2020-01-09 09:28:27",
              "terms_policy": "2020-01-09 09:28:27",
              "brand_admin_for": "fleet",
              "links": {
                "self": "http:\/\/localhost:8080\/v1\/users\/5209"
              }
            }
          ]
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
## Get Vehicles [/v1/admin/getVehicles/{customerId}]
    
### Get Vehicles [GET]

Gets the vehicles of a customer from the admin brand

+ Parameters
    + customerId: 1000 (integer, required) - Customer ID to get vehicles from

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "vehicles": {
          "data": [
            {
              "id": 1,
              "customer_id": 1000,
              "unit_id": 2,
              "dealership_id": 1000,
              "fitter_id": 1000,
              "install_complete": 1,
              "timestamp": 1502829207,
              "installed": 1502722981,
              "alias": "alias for 5688",
              "vin": "5688",
              "registration": "5688",
              "make": "5688",
              "model": "5688",
              "colour": "5688",
              "description": "description for 5688",
              "fuel_type": "Petrol",
              "type": "Car",
              "odometer": 5995.994120334912,
              "aux_0_name": "Blue Lights",
              "aux_0_string_on": "Lights ON",
              "aux_0_string_off": "Lights OFF",
              "aux_0_config_flags": 9,
              "aux_1_name": "Random Switch",
              "aux_1_string_on": "Switch ON",
              "aux_1_string_off": "Switch OFF",
              "aux_1_config_flags": 0,
              "aux_2_name": "Auxiliary 2",
              "aux_2_string_on": "ON",
              "aux_2_string_off": "OFF",
              "aux_2_config_flags": 9,
              "aux_3_name": null,
              "aux_3_string_on": null,
              "aux_3_string_off": null,
              "aux_3_config_flags": 0,
              "g_sense": null,
              "g_sense_number": null,
              "garage_mode_begin": null,
              "garage_mode_end": null,
              "transport_mode_begin": null,
              "transport_mode_end": null,
              "ewm_enabled": false,
              "sms_number": "00000000000",
              "last_odo": 1378597800,
              "privacy_mode_enabled": false,
              "zero_speed_mode_enabled": null,
              "battery_type": "12VLA",
              "avgMpg": 40,
              "co2": 105,
              "links": {
                "self": "http:\/\/localhost:8080\/v1\/customers\/1000\/vehicles\/1",
                "journeys": "http:\/\/localhost:8080\/v1\/vehicles\/1\/journeys"
              }
            }
          ]
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
## Get Vehicle [/v1/admin/getVehicle/{customerId}/{vehicleId}]
    
### Get Vehicle [GET]

Gets a vehicle of a customer from the admin brand using vehicle ID

+ Parameters
    + customerId: 20000 (integer, required) - Customer ID that the vehicle belongs to
    + vehicleId: 20000 (integer, required) - Vehicle ID to retrieve from that customer

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "vehicle": {
          "data": {
            "id": 1,
            "customer_id": 1000,
            "unit_id": 2,
            "dealership_id": 1000,
            "fitter_id": 1000,
            "install_complete": 1,
            "timestamp": 1502829207,
            "installed": 1502722981,
            "alias": "alias for 5688",
            "vin": "5688",
            "registration": "5688",
            "make": "5688",
            "model": "5688",
            "colour": "5688",
            "description": "description for 5688",
            "fuel_type": "Petrol",
            "type": "Car",
            "odometer": 5995.994120334912,
            "aux_0_name": "Blue Lights",
            "aux_0_string_on": "Lights ON",
            "aux_0_string_off": "Lights OFF",
            "aux_0_config_flags": 9,
            "aux_1_name": "Random Switch",
            "aux_1_string_on": "Switch ON",
            "aux_1_string_off": "Switch OFF",
            "aux_1_config_flags": 0,
            "aux_2_name": "Auxiliary 2",
            "aux_2_string_on": "ON",
            "aux_2_string_off": "OFF",
            "aux_2_config_flags": 9,
            "aux_3_name": null,
            "aux_3_string_on": null,
            "aux_3_string_off": null,
            "aux_3_config_flags": 0,
            "g_sense": null,
            "g_sense_number": null,
            "garage_mode_begin": null,
            "garage_mode_end": null,
            "transport_mode_begin": null,
            "transport_mode_end": null,
            "ewm_enabled": false,
            "sms_number": "00000000000",
            "last_odo": 1378597800,
            "privacy_mode_enabled": false,
            "zero_speed_mode_enabled": null,
            "battery_type": "12VLA",
            "links": {
              "self": "http:\/\/localhost:8080\/v1\/customers\/1000\/vehicles\/1",
              "journeys": "http:\/\/localhost:8080\/v1\/vehicles\/1\/journeys"
            }
          }
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
## Create Customer [/v1/admin/createCustomer]
    
### Create Customer [POST]

Creates a new customer on the admin brand

+ Request (application/json)
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Admin Create Customer)
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "customer": {
          "data": {
            "id": 1002,
            "dealership_id": null,
            "company": "Scorpion Automotive",
            "address": "Scorpion Automotive",
            "address2": null,
            "address3": null,
            "county": null,
            "postcode": "M98H+98",
            "country": null,
            "timezone": null,
            "primary_phone": "+441234567891",
            "fax": null,
            "email": "test@examplez.com",
            "description": null,
            "texts": null,
            "new_user_notify": null,
            "new_driver_notify": null,
            "show_map_speed": null,
            "gsense": null,
            "invoiced_monthly": null
          }
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
## Get Customer [/v1/admin/getCustomer/{customerId}]
    
### Get Customer [GET]

Gets the customer by ID with the admin brand

+ Parameters
    + search: customerId (integer, required) - ID of the customer to retrieve

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "customer": {
          "data": {
            "id": 1000,
            "dealership_id": 1000,
            "company": "Scorpion Automotive LTD",
            "address": "Scorpion House, Drumhead Road, Chorley North Business Park",
            "address2": "",
            "address3": "Chorley",
            "county": "Lancashire",
            "postcode": "PR2 1RB",
            "country": "GB",
            "timezone": "Europe\/London",
            "primary_phone": "+441234567",
            "fax": "",
            "email": "development@scorpionauto.com",
            "description": "",
            "texts": 13331,
            "new_user_notify": 0,
            "new_driver_notify": 0,
            "show_map_speed": true,
            "gsense": 0,
            "invoiced_monthly": 0
          }
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
## Update Customer [/v1/admin/updateCustomer/{customerId}]
    
### Update Customer [POST]

Updates a customer details

+ Parameters
    + customerId: 20000 (integer, required) - Customer ID to update

+ Request (application/json)
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Admin Update Customer)
        
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "customer": {
          "data": {
            "id": 1000,
            "dealership_id": 1000,
            "company": "Scorpion Automotive1",
            "address": "Scorpion Automotive1",
            "address2": "Chorley1",
            "address3": "Preston",
            "county": "Lancashire",
            "postcode": "M98H+98",
            "country": "United Kingdom",
            "timezone": "Europe\/London",
            "primary_phone": "+441234567891",
            "fax": "+441234567890",
            "email": "test@examplez.com",
            "description": "Scorpion",
            "texts": 13331,
            "new_user_notify": 0,
            "new_driver_notify": 0,
            "show_map_speed": true,
            "gsense": 0,
            "invoiced_monthly": 0
          }
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
## Create Vehicle [/v1/admin/createVehicle]
    
### Create Vehicle [POST]

Creates a new vehicle on a set customer

+ Request (application/json)
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
    + Attributes (Admin Create Vehicle)
    
+ Response 200 (application/json)

      {
        "status": 200,
        "status_desc": "OK",
        "vehicle": {
          "data": {
            "id": 5295,
            "customer_id": 1000,
            "unit_id": 1,
            "dealership_id": null,
            "fitter_id": null,
            "install_complete": null,
            "timestamp": null,
            "installed": null,
            "alias": null,
            "vin": "AV12333221232",
            "registration": "ABC123",
            "make": "Ford",
            "model": "Focus",
            "colour": "Red",
            "description": null,
            "fuel_type": null,
            "type": null,
            "odometer": null,
            "aux_0_name": null,
            "aux_0_string_on": null,
            "aux_0_string_off": null,
            "aux_0_config_flags": null,
            "aux_1_name": null,
            "aux_1_string_on": null,
            "aux_1_string_off": null,
            "aux_1_config_flags": null,
            "aux_2_name": null,
            "aux_2_string_on": null,
            "aux_2_string_off": null,
            "aux_2_config_flags": null,
            "aux_3_name": null,
            "aux_3_string_on": null,
            "aux_3_string_off": null,
            "aux_3_config_flags": null,
            "g_sense": null,
            "g_sense_number": null,
            "garage_mode_begin": null,
            "garage_mode_end": null,
            "transport_mode_begin": null,
            "transport_mode_end": null,
            "ewm_enabled": false,
            "sms_number": null,
            "last_odo": null,
            "privacy_mode_enabled": null,
            "zero_speed_mode_enabled": null,
            "battery_type": null,
            "links": {
              "self": "http:\/\/localhost:8080\/v1\/customers\/1000\/vehicles\/5295",
              "journeys": "http:\/\/localhost:8080\/v1\/vehicles\/5295\/journeys"
            }
          }
        },
        "auth": {
          "data": {
            "token": "{BASE64_JWT_TOKEN}"
          }
        }
      }
      
      
## Update Vehicle [/v1/admin/updateVehicle/{vehicleId}]
    
### Update Vehicle [POST]

Updates the vehicle with the selected ID

+ Parameters
    + vehicleId: 10000 (integer, required) - Vehicle ID to update

+ Request (application/json)
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Admin Update Vehicle)
        
+ Response 200 (application/json)

      {
          "status": 200,
          "status_desc": "OK",
          "active": true
      }
      
      
## Delete Vehicle [/v1/admin/deleteVehicle/{vehicleId}]
    
### Delete Vehicle [GET]

Gets the customers for the admin brand

+ Parameters
    + vehicleId: 10000 (integer, required) - Vehicle ID to update

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)
      
      
      
      
## Is Active Vehicle [/v1/admin/isActiveVehicle/{vehicleId}]
    
### Is Active Vehicle [GET]

Checks if a vehicle is active (i.e vehicle has a subscription)

+ Parameters
    + vehicleId: 10000 (integer, required) - Vehicle ID to check

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
          "status": 200,
          "status_desc": "OK",
          "active": true
      }

## Activate Vehicle [/v1/admin/activateVehicle/{vehicleId}]
    
### Activate Vehicle [POST]

Activates a vehicle by means of creating a subscription

+ Parameters
    + vehicleId: 10000 (integer, required) - Vehicle ID to activate

+ Request (application/json)
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Admin Activate Vehicle)
        
+ Response 200 (application/json)
    
      {
          "status": 200,
          "status_desc": "OK",
          "subscription": {
            "data": {
              "unit_id": 0,
              "customer_id": 1000,
              "length": 60,
              "sub_start": "2020-03-06",
              "sub_end": "2025-03-06",
              "monitored": false,
              "paypalSubscribedId": null,
              "status": null,
              "invoiced": null
            }
          }
      }

## Deactivate Vehicle [/v1/admin/deactivateVehicle/{vehicleId}]
    
### Deactivate Vehicle [POST]

Deactivates a vehicle by means of removing its subscription

+ Parameters
    + vehicleId: 10000 (integer, required) - Vehicle ID to deactivate

+ Request (application/json)
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)
    
      {
          "status": 200,
          "status_desc": "OK"
      }


## Restock Unit [/v1/admin/restockUnit/{unitId}]
    
### Restock Unit [GET]

Removes vehicle assignment of unit and adds it back into stock

+ Parameters
    + unitId: 1000 (integer, required) - Unit ID to be restocked

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)
    
      {
          "status": 200,
          "status_desc": "OK"
      }
