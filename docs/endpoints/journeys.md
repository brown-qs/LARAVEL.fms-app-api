# Group Journeys

## Show Journeys [/v1/journeys]

### Show Journeys [GET]

Paginates the latest journeys. Ordered by descending end time.

+ Parameters
    + search: amazon (string, optional) - Vehicle group name or description to search by
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
                        "vehicle_id": 6015,
                        "customer_id": 1000,
                        "driver_id": null,
                        "start_time": 1503301223,
                        "end_time": 1503302031,
                        "start_lat": 53.621593,
                        "end_lat": 53.667793,
                        "start_lng": -2.607725,
                        "end_lng": -2.625603,
                        "start_address": "8 Chatsworth Ct, Heath Charnock, Chorley PR6 9SA, UK",
                        "end_address": "9 Drumhead Rd, Chorley PR6 7BX, UK",
                        "average_speed": 31.23,
                        "top_speed": 60.04,
                        "total_idle_time": 98,
                        "longest_idle_time": 52,
                        "distance": 6.049848881259922,
                        "aux_count": null,
                        "fare_data": 3,
                        "links": {
                            "self": "{API_URL}/v1/vehicles/6015/journeys/1503301223/1503302031",
                            "vehicle": "{API_URL}/v1/vehicles/6015",
                            "driver": null
                        },
                        "driver": {
                            "data": null
                        },
                        "vehicle": {
                            "data": {
                                "id": 6015,
                                "customer_id": 1000,
                                "unit_id": 20301,
                                "dealership_id": 1000,
                                "fitter_id": 2361,
                                "install_complete": 1,
                                "timestamp": 1503303884,
                                "installed": 1464257375,
                                "alias": "",
                                "vin": "--------",
                                "registration": "LL16GYU",
                                "make": "BMW",
                                "model": "3 Series",
                                "colour": "Grey",
                                "description": "318D Se Auto",
                                "fuel_type": "Other",
                                "type": "Car",
                                "odometer": 27747.936718417684,
                                "aux_0_name": "Aux 0",
                                "aux_0_string_on": "ON",
                                "aux_0_string_off": "OFF",
                                "aux_0_config_flags": 207,
                                "aux_1_name": "Aux 1",
                                "aux_1_string_on": "ON",
                                "aux_1_string_off": "OFF",
                                "aux_1_config_flags": 0,
                                "aux_2_name": null,
                                "aux_2_string_on": null,
                                "aux_2_string_off": null,
                                "aux_2_config_flags": 9,
                                "aux_3_name": null,
                                "aux_3_string_on": null,
                                "aux_3_string_off": null,
                                "aux_3_config_flags": 0,
                                "last_odo": 1503302031,
                                "links": {
                                    "self": "{API_URL}/v1/vehicles/6015",
                                    "journeys": "{API_URL}/v1/vehicles/6015/journeys"
                                }
                            }
                        }
                    },
                    {
                        "vehicle_id": 8052,
                        "customer_id": 1000,
                        "driver_id": null,
                        "start_time": 1503301598,
                        "end_time": 1503301604,
                        "start_lat": 53.666042,
                        "end_lat": 53.665707,
                        "start_lng": -2.621143,
                        "end_lng": -2.621282,
                        "start_address": "M61, Chorley PR6, UK",
                        "end_address": "38 Drumhead Rd, Chorley PR6 7BX, UK",
                        "average_speed": 0,
                        "top_speed": 0,
                        "total_idle_time": 0,
                        "longest_idle_time": 0,
                        "distance": 0.05847865928646945,
                        "aux_count": null,
                        "fare_data": 3,
                        "links": {
                            "self": "{API_URL}/v1/vehicles/8052/journeys/1503301598/1503301604",
                            "vehicle": "{API_URL}/v1/vehicles/8052",
                            "driver": null
                        },
                        "driver": {
                            "data": null
                        },
                        "vehicle": {
                            "data": {
                                "id": 8052,
                                "customer_id": 1000,
                                "unit_id": 18065,
                                "dealership_id": 1000,
                                "fitter_id": 2720,
                                "install_complete": 1,
                                "timestamp": 1503320020,
                                "installed": 1493733462,
                                "alias": "",
                                "vin": "------",
                                "registration": "UNIT18065",
                                "make": "Citroen",
                                "model": "DS3",
                                "colour": "Black",
                                "description": "",
                                "fuel_type": "Other",
                                "type": "Car",
                                "odometer": 43393.827130893216,
                                "aux_0_name": "Door Status",
                                "aux_0_string_on": "CLOSING",
                                "aux_0_string_off": "OPENING",
                                "aux_0_config_flags": 9,
                                "aux_1_name": "Aux 1",
                                "aux_1_string_on": "ON",
                                "aux_1_string_off": "OFF",
                                "aux_1_config_flags": 0,
                                "aux_2_name": null,
                                "aux_2_string_on": null,
                                "aux_2_string_off": null,
                                "aux_2_config_flags": 9,
                                "aux_3_name": null,
                                "aux_3_string_on": null,
                                "aux_3_string_off": null,
                                "aux_3_config_flags": 0,
                                "last_odo": 1503301604,
                                "links": {
                                    "self": "{API_URL}/v1/vehicles/8052",
                                    "journeys": "{API_URL}/v1/vehicles/8052/journeys"
                                }
                            }
                        }
                    }
                ],
                "meta": {
                    "total_items": 22750,
                    "item_count": 2,
                    "total_pages": 11375,
                    "current_page": 1
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }
        
## Show Journey Calendar [/v1/journeys/calendar/{year}/{month}]

### Show Journey Calendar [GET]

Paginates the latest journeys. Ordered by descending end time.

+ Parameters
    + year: 2016 (number) - The year
    + month: 06 (number) - The month

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}

+ Response 200 (application/json)
        
        {
            "status": 200,
            "status_desc": "OK",
            "vehicle_journey_calendar": {
                "data": [
                    {
                        "date": "2017-06-01",
                        "count": 1
                    },
                    {
                        "date": "2017-06-02",
                        "count": 10
                    },
                    {
                        "date": "2017-06-03",
                        "count": 4
                    },
                    {
                        "date": "2017-06-04",
                        "count": 11
                    },
                    {
                        "date": "2017-06-06",
                        "count": 4
                    },
                    {
                        "date": "2017-06-08",
                        "count": 10
                    },
                    {
                        "date": "2017-06-09",
                        "count": 10
                    },
                    {
                        "date": "2017-06-10",
                        "count": 4
                    },
                    {
                        "date": "2017-06-12",
                        "count": 10
                    },
                    {
                        "date": "2017-06-13",
                        "count": 12
                    },
                    {
                        "date": "2017-06-14",
                        "count": 18
                    },
                    {
                        "date": "2017-06-15",
                        "count": 11
                    },
                    {
                        "date": "2017-06-16",
                        "count": 8
                    },
                    {
                        "date": "2017-06-17",
                        "count": 5
                    },
                    {
                        "date": "2017-06-20",
                        "count": 5
                    },
                    {
                        "date": "2017-06-21",
                        "count": 16
                    },
                    {
                        "date": "2017-06-22",
                        "count": 15
                    },
                    {
                        "date": "2017-06-23",
                        "count": 16
                    },
                    {
                        "date": "2017-06-24",
                        "count": 15
                    },
                    {
                        "date": "2017-06-26",
                        "count": 10
                    },
                    {
                        "date": "2017-06-27",
                        "count": 16
                    },
                    {
                        "date": "2017-06-28",
                        "count": 13
                    },
                    {
                        "date": "2017-06-30",
                        "count": 6
                    }
                ]
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }
