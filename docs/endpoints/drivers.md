# Group Drivers

## Drivers [/v1/drivers{?search,limit}]

### Show Drivers [GET]

Paginates the drivers.

+ Parameters
    + search: Mark (string, optional) - Search by driver name, mobile phone, or email
    + limit: 50 (integer, optional) - Pagination limit number, defaults to `25`

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "drivers": {
                "data": [
                    {
                        "id": 2421,
                        "customer_id": 1000,
                        "first_name": "Hare Test",
                        "last_name": "Driver",
                        "email": "test-driver@hare.dev",
                        "active": true,
                        "timezone": "Europe/London",
                        "mobile_phone": "",
                        "last_login": 1493737589,
                        "last_active": 1493737917,
                        "volume_units": "gallons",
                        "distance_units": "miles",
                        "links": {
                            "self": "{{API_URL}}/v1/drivers/2421"
                        },
                        "latest_journey": {
                            "data": null
                        }
                    },
                    {
                        "id": 11,
                        "customer_id": 1000,
                        "first_name": "Mark",
                        "last_name": "Downing",
                        "email": "mark@scorpionauto.com",
                        "active": true,
                        "timezone": "Europe/London",
                        "mobile_phone": "+447710510126",
                        "last_login": 1493400333,
                        "last_active": 1493400386,
                        "volume_units": "gallons",
                        "distance_units": "miles",
                        "links": {
                            "self": "{{API_URL}}/v1/drivers/11"
                        },
                        "latest_journey": {
                            "data": null
                        }
                    },
                    {
                        "id": 2439,
                        "customer_id": 1000,
                        "first_name": "Test",
                        "last_name": "Test",
                        "email": "dp@datatool.co.uk",
                        "active": true,
                        "timezone": "Europe/London",
                        "mobile_phone": "+447770303676",
                        "last_login": 1489497209,
                        "last_active": 1489497322,
                        "volume_units": "gallons",
                        "distance_units": "miles",
                        "links": {
                            "self": "{{API_URL}}/v1/drivers/2439"
                        },
                        "latest_journey": {
                            "data": null
                        }
                    },
                    {
                        "id": 503,
                        "customer_id": 1000,
                        "first_name": "Test Driver",
                        "last_name": "2 P",
                        "email": "accounts@haredigital.com",
                        "active": true,
                        "timezone": "Europe/London",
                        "mobile_phone": "",
                        "last_login": 1487688387,
                        "last_active": 1487688470,
                        "volume_units": "gallons",
                        "distance_units": "miles",
                        "links": {
                            "self": "{{API_URL}}/v1/drivers/503"
                        },
                        "latest_journey": {
                            "data": null
                        }
                    },
                ],
                "meta": {
                    "total_items": 14,
                    "item_count": 14,
                    "total_pages": 1,
                    "current_page": 1
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## Driver [/v1/drivers/{id}]

+ Parameters
    + id: 1 (number) - The User ID

### Show Driver [GET]

Shows an individual driver along with their latest journey.

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "driver": {
                "data": {
                    "id": 2421,
                    "customer_id": 1000,
                    "first_name": "Hare Test",
                    "last_name": "Driver",
                    "email": "test-driver@hare.dev",
                    "active": true,
                    "timezone": "Europe/London",
                    "mobile_phone": "",
                    "last_login": 1493737589,
                    "last_active": 1493737917,
                    "volume_units": "gallons",
                    "distance_units": "miles",
                    "links": {
                        "self": "{{API_URL}}/v1/drivers/2421",
                        "journeys": "{{API_URL}}/v1/drivers/2421/journeys"
                    },
                    "latest_journey": {
                        "data": {
                            "vehicle_id": 5274,
                            "customer_id": 1000,
                            "driver_id": 2421,
                            "start_time": 1499944450,
                            "end_time": 1499944484,
                            "start_lat": 53.66568,
                            "end_lat": 53.665257,
                            "start_lng": -2.621205,
                            "end_lng": -2.621075,
                            "start_address": "38 Drumhead Rd, Chorley PR6 7BX, UK",
                            "end_address": "23 Northgate Dr, Chorley PR6 0JH, UK",
                            "average_speed": 0,
                            "top_speed": 0,
                            "total_idle_time": 15,
                            "longest_idle_time": 15,
                            "distance": 0.047808827562102196,
                            "auxCount": null,
                            "links": {
                                "self": "{{API_URL}}/v1/vehicles/5274/journeys/1499944450/1499944484",
                                "vehicle": "{{API_URL}}/v1/vehicles/5274",
                                "driver": "{{API_URL}}/v1/drivers/2421"
                            }
                        }
                    }
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## Driver Journeys [/v1/drivers/{id}/journeys]

+ Parameters
    + id: 1 (number) - The Vehicle ID

### Show Driver Journeys [GET]

Shows a drivers journeys.

+ Parameters
    + from: 1503316914 (integer, optional) - Get vehicle journeys after this date, required if `to` set
    + to: 1503316921 (integer, optional) - Get vehicle journeys before this date, required if `from` set
    + limit: 50 (integer, optional) - Pagination limit number, defaults to `25`

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle_journeys": {
                "data": [
                    {
                        "vehicle_id": 5274,
                        "customer_id": 1000,
                        "driver_id": 2421,
                        "start_time": 1499944450,
                        "end_time": 1499944484,
                        "start_lat": 53.66568,
                        "end_lat": 53.665257,
                        "start_lng": -2.621205,
                        "end_lng": -2.621075,
                        "start_address": "38 Drumhead Rd, Chorley PR6 7BX, UK",
                        "end_address": "23 Northgate Dr, Chorley PR6 0JH, UK",
                        "average_speed": 0,
                        "top_speed": 0,
                        "total_idle_time": 15,
                        "longest_idle_time": 15,
                        "distance": 0.047808827562102196,
                        "auxCount": null,
                        "links": {
                            "self": "{{API_URL}}/v1/vehicles/5274/journeys/1499944450/1499944484",
                            "vehicle": "{{API_URL}}/v1/vehicles/5274",
                            "driver": "{{API_URL}}/v1/drivers/2421"
                        },
                        "driver": {
                            "data": {
                                "id": 2421,
                                "customer_id": 1000,
                                "first_name": "Hare Test",
                                "last_name": "Driver",
                                "email": "test-driver@hare.dev",
                                "active": true,
                                "timezone": "Europe/London",
                                "mobile_phone": "",
                                "last_login": 1493737589,
                                "last_active": 1493737917,
                                "volume_units": "gallons",
                                "distance_units": "miles",
                                "links": {
                                    "self": "{{API_URL}}/v1/drivers/2421",
                                    "journeys": "{{API_URL}}/v1/drivers/2421/journeys"
                                }
                            }
                        },
                        "vehicle": {
                            "data": {
                                "id": 5274,
                                "customer_id": 0,
                                "unit_id": 0,
                                "dealership_id": 1000,
                                "fitter_id": 1226,
                                "install_complete": 1,
                                "timestamp": 1499944852,
                                "installed": 1458320331,
                                "alias": "Mark Downing - 07710510126",
                                "vin": "-----",
                                "registration": "PJ10JZA-STX70",
                                "make": "Volvo",
                                "model": "XC60",
                                "colour": "Silver",
                                "description": "",
                                "fuel_type": "Diesel",
                                "type": "Car",
                                "odometer": 16617.770310100463,
                                "aux_0_name": "Aux 0",
                                "aux_0_string_on": "ON",
                                "aux_0_string_off": "OFF",
                                "aux_0_config_flags": 65535,
                                "aux_1_name": "Aux 1",
                                "aux_1_string_on": "ON",
                                "aux_1_string_off": "OFF",
                                "aux_1_config_flags": 65535,
                                "aux_2_name": null,
                                "aux_2_string_on": null,
                                "aux_2_string_off": null,
                                "aux_2_config_flags": 65535,
                                "aux_3_name": null,
                                "aux_3_string_on": null,
                                "aux_3_string_off": null,
                                "aux_3_config_flags": 0,
                                "last_odo": 1499700541,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/5274",
                                    "journeys": "{{API_URL}}/v1/vehicles/5274/journeys"
                                }
                            }
                        }
                    }
                ],
                "meta": {
                    "total_items": 440,
                    "item_count": 1,
                    "total_pages": 440,
                    "current_page": 1
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## Driver Journey [/v1/drivers/{id}/journeys/{startTime}/{endTime}]

+ Parameters
    + id: 1 (number) - The Vehicle ID
    + startTime: 1503316914 (integer, required) - Get driver journey with this startTime
    + endTime: 1503316921 (integer, required) - Get driver journey with this endTime

### Show Driver Journey [GET]

Shows a drivers single journey along with positions on this journey.

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle_journey": {
                "data": {
                    "vehicle_id": 5274,
                    "customer_id": 1000,
                    "driver_id": 2421,
                    "start_time": 1499944450,
                    "end_time": 1499944484,
                    "start_lat": 53.66568,
                    "end_lat": 53.665257,
                    "start_lng": -2.621205,
                    "end_lng": -2.621075,
                    "start_address": "38 Drumhead Rd, Chorley PR6 7BX, UK",
                    "end_address": "23 Northgate Dr, Chorley PR6 0JH, UK",
                    "average_speed": 0,
                    "top_speed": 0,
                    "total_idle_time": 15,
                    "longest_idle_time": 15,
                    "distance": 0.047808827562102196,
                    "auxCount": null,
                    "links": {
                        "self": "{{API_URL}}/v1/vehicles/5274/journeys/1499944450/1499944484",
                        "vehicle": "{{API_URL}}/v1/vehicles/5274",
                        "driver": "{{API_URL}}/v1/drivers/2421"
                    },
                    "positions": {
                        "data": [
                            {
                                "timestamp": 1499944450,
                                "state": "UNSET",
                                "lat": 53.66568,
                                "lng": -2.621205,
                                "speed": 0,
                                "ignition": 1,
                                "engine": 0,
                                "bearing": 0,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/5274/positions/1499944450"
                                }
                            },
                            {
                                "timestamp": 1499944484,
                                "state": "SET",
                                "lat": 53.665257,
                                "lng": -2.621075,
                                "speed": 0,
                                "ignition": 0,
                                "engine": 0,
                                "bearing": 0,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/5274/positions/1499944484"
                                }
                            }
                        ]
                    }
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }
