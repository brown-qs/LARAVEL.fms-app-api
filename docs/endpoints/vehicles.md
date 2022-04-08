# Group Vehicles

## Show Vehicles [/v1/vehicles{?search,limit}]
    
### Show Vehicles [GET]

Paginates the most recently updated vehicles (does not necessarily mean the ones that have moved most recently).

+ Parameters
    + search: amazon (string, optional) - Vehicle registration or alias to search by
    + limit: 50 (integer, optional) - Pagination limit number, defaults to `25`

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
                      "id": 7,
                      "customer_id": 1000,
                      "unit_id": 1329,
                      "dealership_id": 1000,
                      "fitter_id": 1000,
                      "install_complete": 1,
                      "timestamp": 1464167441,
                      "installed": 1335280889,
                      "alias": "",
                      "vin": "BENCH",
                      "registration": "FMS1329",
                      "make": "BENCH",
                      "model": "BENCH",
                      "colour": "BENCH",
                      "description": "",
                      "fuel_type": "Petrol",
                      "type": "Car",
                      "odometer": 0.5120094033056654,
                      "aux_0_name": "Auxiliary 0",
                      "aux_0_string_on": "ON",
                      "aux_0_string_off": "OFF",
                      "aux_0_config_flags": 1033,
                      "aux_1_name": "Auxiliary 1",
                      "aux_1_string_on": "ON",
                      "aux_1_string_off": "OFF",
                      "aux_1_config_flags": 0,
                      "aux_2_name": "Auxiliary 2",
                      "aux_2_string_on": "ON",
                      "aux_2_string_off": "OFF",
                      "aux_2_config_flags": 1033,
                      "aux_3_name": null,
                      "aux_3_string_on": null,
                      "aux_3_string_off": null,
                      "aux_3_config_flags": 0,
                      "last_odo": 1463065386,
                      "g_sense": null,
                      "g_sense_number": null,
                      "garage_mode_begin": "2017-11-02 11:57:56",
                      "garage_mode_end": "2017-11-02 11:57:56",
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
                          "self": "{{API_URL}}/v1/vehicles/7"
                      },
                      "groups": {
                          "data": [
                              {
                                  "id": 1,
                                  "customer_id": 1000,
                                  "group_name": "More Vehicles",
                                  "group_description": "",
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicle-groups/1"
                                  }
                              },
                              {
                                  "id": 11,
                                  "customer_id": 1000,
                                  "group_name": "BENCH Vehicles",
                                  "group_description": "All BENCHTEST Vehicles",
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicle-groups/11"
                                  }
                              }
                          ]
                      },
                      "latest_position": {
                          "data": {
                              "vehicle_id": 7,
                              "customer_id": 1000,
                              "timestamp": 1463065453,
                              "driver_id": 7,
                              "health_check_id": 0,
                              "state": "SET",
                              "gps_type": 3,
                              "gps_satellites": 7,
                              "lat": 53.665817,
                              "lng": -2.62132,
                              "accuracy": 0,
                              "speed": 0,
                              "ignition": 0,
                              "engine": 0,
                              "cell_data": "",
                              "hdop": 1.2,
                              "bearing": 79,
                              "address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                              "aux_0_value": 1,
                              "aux_1_value": 0,
                              "aux_2_value": 1,
                              "aux_3_value": null,
                              "seats_occupied": 2
                          }
                      }
                  },
                  {
                      "id": 8,
                      "customer_id": 1000,
                      "unit_id": 0,
                      "dealership_id": 1000,
                      "fitter_id": 1000,
                      "install_complete": 1,
                      "timestamp": 1499719993,
                      "installed": 1450283885,
                      "alias": "",
                      "vin": "BENCH",
                      "registration": "BENCHFMS2",
                      "make": "BENCH",
                      "model": "BENCH",
                      "colour": "BENCH",
                      "description": "",
                      "fuel_type": "Petrol",
                      "type": "Car",
                      "odometer": 23753.2669082329,
                      "aux_0_name": "Door Open With",
                      "aux_0_string_on": "ON",
                      "aux_0_string_off": "Ignition On",
                      "aux_0_config_flags": 1033,
                      "aux_1_name": "Auxiliary 1",
                      "aux_1_string_on": "ON",
                      "aux_1_string_off": "OFF",
                      "aux_1_config_flags": 0,
                      "aux_2_name": "Auxiliary 2",
                      "aux_2_string_on": "ON",
                      "aux_2_string_off": "OFF",
                      "aux_2_config_flags": 1033,
                      "aux_3_name": null,
                      "aux_3_string_on": null,
                      "aux_3_string_off": null,
                      "aux_3_config_flags": 0,
                      "last_odo": 1458149468,
                      "g_sense": null,
                      "g_sense_number": null,
                      "garage_mode_begin": "2017-11-02 11:57:56",
                      "garage_mode_end": "2017-11-02 11:57:56",
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
                          "self": "{{API_URL}}/v1/vehicles/8"
                      },
                      "groups": {
                          "data": [
                              {
                                  "id": 1,
                                  "customer_id": 1000,
                                  "group_name": "More Vehicles",
                                  "group_description": "",
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicle-groups/1"
                                  }
                              },
                              {
                                  "id": 11,
                                  "customer_id": 1000,
                                  "group_name": "BENCH Vehicles",
                                  "group_description": "All BENCHTEST Vehicles",
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicle-groups/11"
                                  }
                              }
                          ]
                      },
                      "latest_position": {
                          "data": {
                              "vehicle_id": 8,
                              "customer_id": 1000,
                              "timestamp": 1458149541,
                              "driver_id": 0,
                              "health_check_id": 0,
                              "state": "SLP",
                              "gps_type": 3,
                              "gps_satellites": 10,
                              "lat": 53.66563,
                              "lng": -2.621208,
                              "accuracy": 0,
                              "speed": 0,
                              "ignition": 0,
                              "engine": 0,
                              "cell_data": "",
                              "hdop": 0.8,
                              "bearing": 324,
                              "address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                              "aux_0_value": 1,
                              "aux_1_value": 0,
                              "aux_2_value": 1,
                              "aux_3_value": null,
                              "seats_occupied": 2
                          }
                      }
                  },
              ],
              "meta": {
                  "total_items": 20,
                  "item_count": 20,
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

## Vehicle [/v1/vehicles/{id}]

+ Parameters
    + id: 1 (number) - The Vehicle ID
    
### Show [GET]

Get individual vehicle, with latest journey, latest position and vehicle groups.

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
          "status": 200,
          "status_desc": "OK",
          "vehicle": {
              "data": {
                  "id": 8,
                  "customer_id": 1000,
                  "unit_id": 0,
                  "dealership_id": 1000,
                  "fitter_id": 1000,
                  "install_complete": 1,
                  "timestamp": 1499719993,
                  "installed": 1450283885,
                  "alias": "",
                  "vin": "BENCH",
                  "registration": "BENCHFMS2",
                  "make": "BENCH",
                  "model": "BENCH",
                  "colour": "BENCH",
                  "description": "",
                  "fuel_type": "Petrol",
                  "type": "Car",
                  "odometer": 23753.2669082329,
                  "aux_0_name": "Door Open With",
                  "aux_0_string_on": "ON",
                  "aux_0_string_off": "Ignition On",
                  "aux_0_config_flags": 1033,
                  "aux_1_name": "Auxiliary 1",
                  "aux_1_string_on": "ON",
                  "aux_1_string_off": "OFF",
                  "aux_1_config_flags": 0,
                  "aux_2_name": "Auxiliary 2",
                  "aux_2_string_on": "ON",
                  "aux_2_string_off": "OFF",
                  "aux_2_config_flags": 1033,
                  "aux_3_name": null,
                  "aux_3_string_on": null,
                  "aux_3_string_off": null,
                  "aux_3_config_flags": 0,
                  "last_odo": 1458149468,
                  "g_sense": null,
                  "g_sense_number": null,
                  "garage_mode_begin": "2017-11-02 11:57:56",
                  "garage_mode_end": "2017-11-02 11:57:56",
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
                      "self": "{{API_URL}}/v1/vehicles/8"
                  },
                  "groups": {
                      "data": [
                          {
                              "id": 1,
                              "customer_id": 1000,
                              "group_name": "More Vehicles",
                              "group_description": "",
                              "links": {
                                  "self": "{{API_URL}}/v1/vehicle-groups/1"
                              }
                          },
                          {
                              "id": 11,
                              "customer_id": 1000,
                              "group_name": "BENCH Vehicles",
                              "group_description": "All BENCHTEST Vehicles",
                              "links": {
                                  "self": "{{API_URL}}/v1/vehicle-groups/11"
                              }
                          }
                      ]
                  },
                  "latest_journey": {
                      "data": {
                          "vehicle_id": 8,
                          "customer_id": 1000,
                          "driver_id": 0,
                          "start_time": 1458147538,
                          "end_time": 1458149468,
                          "start_lat": 53.6656,
                          "end_lat": 53.665634,
                          "start_lng": -2.621248,
                          "end_lng": -2.62121,
                          "start_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                          "end_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                          "average_speed": 0.04,
                          "top_speed": 0.68,
                          "total_idle_time": 0,
                          "longest_idle_time": 0,
                          "distance": 0.09475930980304523,
                          "aux_count": null,
                          "fare_data": 3,
                          "links": {
                              "self": "{{API_URL}}/v1/vehicles/8/journeys/1458147538/1458149468",
                              "vehicle": "{{API_URL}}/v1/vehicles/8",
                              "driver": "{{API_URL}}/v1/drivers/0"
                          }
                      }
                  },
                  "latest_position": {
                      "data": {
                          "vehicle_id": 8,
                          "customer_id": 1000,
                          "timestamp": 1458149541,
                          "driver_id": 0,
                          "health_check_id": 0,
                          "state": "SLP",
                          "gps_type": 3,
                          "gps_satellites": 10,
                          "lat": 53.66563,
                          "lng": -2.621208,
                          "accuracy": 0,
                          "speed": 0,
                          "ignition": 0,
                          "engine": 0,
                          "cell_data": "",
                          "hdop": 0.8,
                          "bearing": 324,
                          "address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                          "aux_0_value": 1,
                          "aux_1_value": 0,
                          "aux_2_value": 1,
                          "aux_3_value": null,
                          "seats_occupied": 2
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

### Update [PUT]

Updates the specified vehicle

+ Request Update Vehicle (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Vehicle)
    

+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle": {
                "data": {
                    "id": 8,
                    "customer_id": 1000,
                    "unit_id": 0,
                    "dealership_id": 1000,
                    "fitter_id": 1000,
                    "install_complete": 1,
                    "timestamp": 1499719993,
                    "installed": 1450283885,
                    "alias": "",
                    "vin": "BENCH",
                    "registration": "BENCHFMS2",
                    "make": "BENCH",
                    "model": "BENCH",
                    "colour": "BENCH",
                    "description": "",
                    "fuel_type": "Petrol",
                    "type": "Car",
                    "odometer": 23753.2669082329,
                    "aux_0_name": "Door Open With",
                    "aux_0_string_on": "ON",
                    "aux_0_string_off": "Ignition On",
                    "aux_0_config_flags": 1033,
                    "aux_1_name": "Auxiliary 1",
                    "aux_1_string_on": "ON",
                    "aux_1_string_off": "OFF",
                    "aux_1_config_flags": 0,
                    "aux_2_name": "Auxiliary 2",
                    "aux_2_string_on": "ON",
                    "aux_2_string_off": "OFF",
                    "aux_2_config_flags": 1033,
                    "aux_3_name": null,
                    "aux_3_string_on": null,
                    "aux_3_string_off": null,
                    "aux_3_config_flags": 0,
                    "last_odo": 1458149468,
                    "g_sense": null,
                    "g_sense_number": null,
                    "garage_mode_begin": "2017-11-02 11:57:56",
                    "garage_mode_end": "2017-11-02 11:57:56",
                    "transport_mode_begin": null,
                    "transport_mode_end": null,
                    "zero_speed_mode_enabled": true,
                    "privacy_mode_enabled": true,
                    "ewm_enabled": false,
                    "sms_number": "00000000000",
                    "last_odo": 1378597800,
                    "privacy_mode_enabled": false,
                    "zero_speed_mode_enabled": null,
                    "battery_type": "12VLA",
                    "avgMpg": 40,
                    "co2": 105,
                    "links": {
                        "self": "{{API_URL}}/v1/vehicles/8"
                    },
                    "groups": {
                        "data": [
                            {
                                "id": 1,
                                "customer_id": 1000,
                                "group_name": "More Vehicles",
                                "group_description": "",
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicle-groups/1"
                                }
                            },
                            {
                                "id": 11,
                                "customer_id": 1000,
                                "group_name": "BENCH Vehicles",
                                "group_description": "All BENCHTEST Vehicles",
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicle-groups/11"
                                }
                            }
                        ]
                    },
                    "latest_journey": {
                        "data": {
                            "vehicle_id": 8,
                            "customer_id": 1000,
                            "driver_id": 0,
                            "start_time": 1458147538,
                            "end_time": 1458149468,
                            "start_lat": 53.6656,
                            "end_lat": 53.665634,
                            "start_lng": -2.621248,
                            "end_lng": -2.62121,
                            "start_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                            "end_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                            "average_speed": 0.04,
                            "top_speed": 0.68,
                            "total_idle_time": 0,
                            "longest_idle_time": 0,
                            "distance": 0.09475930980304523,
                            "aux_count": null,
                            "fare_data": 3,
                            "links": {
                                "self": "{{API_URL}}/v1/vehicles/8/journeys/1458147538/1458149468",
                                "vehicle": "{{API_URL}}/v1/vehicles/8",
                                "driver": "{{API_URL}}/v1/drivers/0"
                            }
                        }
                    },
                    "latest_position": {
                        "data": {
                            "vehicle_id": 8,
                            "customer_id": 1000,
                            "timestamp": 1458149541,
                            "driver_id": 0,
                            "health_check_id": 0,
                            "state": "SLP",
                            "gps_type": 3,
                            "gps_satellites": 10,
                            "lat": 53.66563,
                            "lng": -2.621208,
                            "accuracy": 0,
                            "speed": 0,
                            "ignition": 0,
                            "engine": 0,
                            "cell_data": "",
                            "hdop": 0.8,
                            "bearing": 324,
                            "address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                            "aux_0_value": 1,
                            "aux_1_value": 0,
                            "aux_2_value": 1,
                            "aux_3_value": null,
                            "seats_occupied": 2
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
        
## Vehicle Health Checks [/v1/vehicles/{id}/health-checks{?limit}]

+ Parameters
    + id: 1 (number) - The Vehicle ID
    
### Show Vehicle Health Checks [GET]

Lists the Health Checks for a vehicle ordered by when the unit health check report was received.

+ Parameters
    + limit (integer, optional): Pagination limit number, defaults to `25`. Setting this to `0` will bypass pagination 
    and return all results.

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

      {
          "status": 200,
          "status_desc": "OK",
          "health_checks": {
              "data": [
                  {
                      "id": 12367,
                      "vehicle_id": 8394,
                      "unit_id": 28945,
                      "gps_antenna_voltage": 3.04,
                      "gps_antenna_current": 8,
                      "last_gps_timestamp": 1234923478,
                      "vehicle_battery_voltage": 12.65,
                      "backup_battery_voltage": 4.04,
                      "vehicle_system_voltage": 12,
                      "unit_state": "alert",
                      "is_ignition_on": false,
                      "links": null
                  },
                  {
                      "id": 14524,
                      "vehicle_id": 8394,
                      "unit_id": 28945,
                      "gps_antenna_voltage": 3.03,
                      "gps_antenna_current": 9,
                      "last_gps_timestamp": 1645159441,
                      "vehicle_battery_voltage": 12.6,
                      "backup_battery_voltage": 4.08,
                      "vehicle_system_voltage": 12,
                      "unit_state": "unset",
                      "is_ignition_on": false,
                      "links": null
                  },
                  {
                      "id": 14865,
                      "vehicle_id": 8394,
                      "unit_id": 28945,
                      "gps_antenna_voltage": 3.04,
                      "gps_antenna_current": 9,
                      "last_gps_timestamp": 1079583451,
                      "vehicle_battery_voltage": 12.65,
                      "backup_battery_voltage": 4.04,
                      "vehicle_system_voltage": 12,
                      "unit_state": "alert",
                      "is_ignition_on": false,
                      "links": null
                  }
              ]
          },
          "auth": {
             "data": {
                 "token": "{BASE64_JWT_TOKEN}"
             }
          }
      }

## Vehicle Notes [/v1/vehicles/{id}/notes]

+ Parameters
    + id: 1 (number) - The Vehicle ID

### Create Vehicle Note [POST]

Creates a vehicle note

+ Request Create Vehicle Note Request(application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Create Vehicle Note Request)
    
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle_note": {
                "data": {
                    "id": 22560,
                    "user_id": 4883,
                    "vehicle_id": 8382,
                    "noteType": "Customer",
                    "note": "test",
                    "timestamp": 1509618389,
                    "deleted": false,
                    "read": false,
                    "visibility": "Private",
                    "links": null
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## Disable "No Alerts" Mode [/v1/vehicles/{id}/no-alerts/disable]

+ Parameters
    + id: 1 (number) - The Vehicle ID

### Disable No alerts mode [PUT]  

Disables no alert mode

+ Disable no alert mode (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
    
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle": {
                "data": {
                    "id": 8382,
                    "customer_id": 2556,
                    "unit_id": 28940,
                    "dealership_id": 1262,
                    "fitter_id": 4133,
                    "install_complete": 1,
                    "timestamp": 1509118785,
                    "installed": 1500884058,
                    "alias": "W040",
                    "vin": "M2TSF2E12H1G09648",
                    "registration": "M2TSF2E12H1G09648",
                    "make": "Kinetic",
                    "model": "Safar",
                    "colour": "Green",
                    "description": "E-Auto",
                    "fuel_type": "Other",
                    "type": "Other",
                    "odometer": 420.69,
                    "aux_0_name": "Aux 0",
                    "aux_0_string_on": "ON",
                    "aux_0_string_off": "OFF",
                    "aux_0_config_flags": 1033,
                    "aux_1_name": "Aux 1",
                    "aux_1_string_on": "ON",
                    "aux_1_string_off": "OFF",
                    "aux_1_config_flags": 1033,
                    "aux_2_name": null,
                    "aux_2_string_on": null,
                    "aux_2_string_off": null,
                    "aux_2_config_flags": 1033,
                    "aux_3_name": null,
                    "aux_3_string_on": null,
                    "aux_3_string_off": null,
                    "aux_3_config_flags": 0,
                    "g_sense": null,
                    "g_sense_number": null,
                    "garage_mode_begin": "2017-11-02 11:57:56",
                    "garage_mode_end": "2017-11-02 11:57:56",
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
                        "self": "{{API_URL}}/v1/vehicles/8382",
                        "journeys": "{{API_URL}}/v1/vehicles/8382/journeys"
                    },
                    "unit": {
                        "data": {
                            "id": 28940,
                            "hardware_id": 710105,
                            "app_id": 710206,
                            "type": "STX71F",
                            "subscription": {
                                "data": {
                                    "unit_id": 28940,
                                    "customer_id": 2556,
                                    "length": 12,
                                    "sub_start": "2017-07-24",
                                    "sub_end": "2018-07-24",
                                    "monitored": 0,
                                    "paypalSubscribedId": null,
                                    "status": null,
                                    "invoiced": 0
                                }
                            }
                        }
                    },
                    "groups": {
                        "data": [
                            {
                                "id": 398,
                                "customer_id": 2556,
                                "group_name": "Wazirabad Hub",
                                "group_description": "",
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicle-groups/398"
                                }
                            }
                        ]
                    },
                    "latest_journey": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "driver_id": null,
                            "start_time": 1509017194,
                            "end_time": 1509018190,
                            "start_lat": 28.442154,
                            "end_lat": 28.458572,
                            "start_lng": 77.056435,
                            "end_lng": 77.072845,
                            "start_address": "6, Arya Samaj Rd, Block D, Greenwood City, Sector 46, Gurugram, Haryana 122022, India",
                            "end_address": "Netaji Subhash Marg, Sector 44, Gurugram, Haryana 122003, India",
                            "average_speed": 9.81,
                            "top_speed": 25.36,
                            "total_idle_time": 0,
                            "longest_idle_time": 0,
                            "distance": 2.7551410101960347,
                            "auxCount": null,
                            "links": {
                                "self": "{{API_URL}}/v1/vehicles/8382/journeys/1509017194/1509018190",
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    },
                    "latest_position": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "timestamp": 1509118753,
                            "driver_id": null,
                            "health_check_id": 0,
                            "state": "UNSET",
                            "gps_type": 3,
                            "gps_satellites": 9,
                            "lat": 28.44696,
                            "lng": 77.060921,
                            "accuracy": 0,
                            "speed": 4.01,
                            "ignition": 1,
                            "engine": 0,
                            "cell_data": "",
                            "hdop": 0.7,
                            "bearing": 37,
                            "address": null,
                            "aux_0_value": 0,
                            "aux_1_value": 1,
                            "aux_2_value": 1,
                            "aux_3_value": null,
                            "links": {
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    }
                }
            }
        }


## Enable No Alerts Mode [/v1/vehicles/{id}/no-alerts/enable]

+ Parameters
    + id: 1 (number) - The Vehicle ID

### Enable No Alerts Mode [PUT]  

Enables the mode for no alerts appearing

+ Request Set No Alerts Mode (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Alert Mode Request)
    
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle": {
                "data": {
                    "id": 8382,
                    "customer_id": 2556,
                    "unit_id": 28940,
                    "dealership_id": 1262,
                    "fitter_id": 4133,
                    "install_complete": 1,
                    "timestamp": 1509118785,
                    "installed": 1500884058,
                    "alias": "W040",
                    "vin": "M2TSF2E12H1G09648",
                    "registration": "M2TSF2E12H1G09648",
                    "make": "Kinetic",
                    "model": "Safar",
                    "colour": "Green",
                    "description": "E-Auto",
                    "fuel_type": "Other",
                    "type": "Other",
                    "odometer": 420.69,
                    "aux_0_name": "Aux 0",
                    "aux_0_string_on": "ON",
                    "aux_0_string_off": "OFF",
                    "aux_0_config_flags": 1033,
                    "aux_1_name": "Aux 1",
                    "aux_1_string_on": "ON",
                    "aux_1_string_off": "OFF",
                    "aux_1_config_flags": 1033,
                    "aux_2_name": null,
                    "aux_2_string_on": null,
                    "aux_2_string_off": null,
                    "aux_2_config_flags": 1033,
                    "aux_3_name": null,
                    "aux_3_string_on": null,
                    "aux_3_string_off": null,
                    "aux_3_config_flags": 0,
                    "g_sense": null,
                    "g_sense_number": null,
                    "garage_mode_begin": "2017-11-02 11:57:56",
                    "garage_mode_end": "2017-11-02 11:57:56",
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
                        "self": "{{API_URL}}/v1/vehicles/8382",
                        "journeys": "{{API_URL}}/v1/vehicles/8382/journeys"
                    },
                    "unit": {
                        "data": {
                            "id": 28940,
                            "hardware_id": 710105,
                            "app_id": 710206,
                            "type": "STX71F",
                            "subscription": {
                                "data": {
                                    "unit_id": 28940,
                                    "customer_id": 2556,
                                    "length": 12,
                                    "sub_start": "2017-07-24",
                                    "sub_end": "2018-07-24",
                                    "monitored": 0,
                                    "paypalSubscribedId": null,
                                    "status": null,
                                    "invoiced": 0
                                }
                            }
                        }
                    },
                    "groups": {
                        "data": [
                            {
                                "id": 398,
                                "customer_id": 2556,
                                "group_name": "Wazirabad Hub",
                                "group_description": "",
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicle-groups/398"
                                }
                            }
                        ]
                    },
                    "latest_journey": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "driver_id": null,
                            "start_time": 1509017194,
                            "end_time": 1509018190,
                            "start_lat": 28.442154,
                            "end_lat": 28.458572,
                            "start_lng": 77.056435,
                            "end_lng": 77.072845,
                            "start_address": "6, Arya Samaj Rd, Block D, Greenwood City, Sector 46, Gurugram, Haryana 122022, India",
                            "end_address": "Netaji Subhash Marg, Sector 44, Gurugram, Haryana 122003, India",
                            "average_speed": 9.81,
                            "top_speed": 25.36,
                            "total_idle_time": 0,
                            "longest_idle_time": 0,
                            "distance": 2.7551410101960347,
                            "auxCount": null,
                            "links": {
                                "self": "{{API_URL}}/v1/vehicles/8382/journeys/1509017194/1509018190",
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    },
                    "latest_position": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "timestamp": 1509118753,
                            "driver_id": null,
                            "health_check_id": 0,
                            "state": "UNSET",
                            "gps_type": 3,
                            "gps_satellites": 9,
                            "lat": 28.44696,
                            "lng": 77.060921,
                            "accuracy": 0,
                            "speed": 4.01,
                            "ignition": 1,
                            "engine": 0,
                            "cell_data": "",
                            "hdop": 0.7,
                            "bearing": 37,
                            "address": null,
                            "aux_0_value": 0,
                            "aux_1_value": 1,
                            "aux_2_value": 1,
                            "aux_3_value": null,
                            "links": {
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    }
                }
            }
        }


## Vehicle Modes [/v1/vehicles/{id}/modes]

+ Parameters
    + id: 1 (number) - The Vehicle ID

### Update Vehicle Mode [PUT]  

Sets a vehicle mode

+ Request Set Transport Mode (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Vehicle Mode Transport Request)
    
+ Request Set Garage Mode (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Vehicle Mode Garage Request)
    
        
+ Request Clear Transport Mode (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Vehicle Mode Clear Transport Request)
    
        
+ Request Clear Garage Mode (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Vehicle Mode Clear Garage Request)
    
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle": {
                "data": {
                    "id": 8382,
                    "customer_id": 2556,
                    "unit_id": 28940,
                    "dealership_id": 1262,
                    "fitter_id": 4133,
                    "install_complete": 1,
                    "timestamp": 1509118785,
                    "installed": 1500884058,
                    "alias": "W040",
                    "vin": "M2TSF2E12H1G09648",
                    "registration": "M2TSF2E12H1G09648",
                    "make": "Kinetic",
                    "model": "Safar",
                    "colour": "Green",
                    "description": "E-Auto",
                    "fuel_type": "Other",
                    "type": "Other",
                    "odometer": 420.69,
                    "aux_0_name": "Aux 0",
                    "aux_0_string_on": "ON",
                    "aux_0_string_off": "OFF",
                    "aux_0_config_flags": 1033,
                    "aux_1_name": "Aux 1",
                    "aux_1_string_on": "ON",
                    "aux_1_string_off": "OFF",
                    "aux_1_config_flags": 1033,
                    "aux_2_name": null,
                    "aux_2_string_on": null,
                    "aux_2_string_off": null,
                    "aux_2_config_flags": 1033,
                    "aux_3_name": null,
                    "aux_3_string_on": null,
                    "aux_3_string_off": null,
                    "aux_3_config_flags": 0,
                    "g_sense": null,
                    "g_sense_number": null,
                    "garage_mode_begin": "2017-11-02 11:57:56",
                    "garage_mode_end": "2017-11-02 11:57:56",
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
                        "self": "{{API_URL}}/v1/vehicles/8382",
                        "journeys": "{{API_URL}}/v1/vehicles/8382/journeys"
                    },
                    "unit": {
                        "data": {
                            "id": 28940,
                            "hardware_id": 710105,
                            "app_id": 710206,
                            "type": "STX71F",
                            "subscription": {
                                "data": {
                                    "unit_id": 28940,
                                    "customer_id": 2556,
                                    "length": 12,
                                    "sub_start": "2017-07-24",
                                    "sub_end": "2018-07-24",
                                    "monitored": 0,
                                    "paypalSubscribedId": null,
                                    "status": null,
                                    "invoiced": 0
                                }
                            }
                        }
                    },
                    "groups": {
                        "data": [
                            {
                                "id": 398,
                                "customer_id": 2556,
                                "group_name": "Wazirabad Hub",
                                "group_description": "",
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicle-groups/398"
                                }
                            }
                        ]
                    },
                    "latest_journey": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "driver_id": null,
                            "start_time": 1509017194,
                            "end_time": 1509018190,
                            "start_lat": 28.442154,
                            "end_lat": 28.458572,
                            "start_lng": 77.056435,
                            "end_lng": 77.072845,
                            "start_address": "6, Arya Samaj Rd, Block D, Greenwood City, Sector 46, Gurugram, Haryana 122022, India",
                            "end_address": "Netaji Subhash Marg, Sector 44, Gurugram, Haryana 122003, India",
                            "average_speed": 9.81,
                            "top_speed": 25.36,
                            "total_idle_time": 0,
                            "longest_idle_time": 0,
                            "distance": 2.7551410101960347,
                            "auxCount": null,
                            "links": {
                                "self": "{{API_URL}}/v1/vehicles/8382/journeys/1509017194/1509018190",
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    },
                    "latest_position": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "timestamp": 1509118753,
                            "driver_id": null,
                            "health_check_id": 0,
                            "state": "UNSET",
                            "gps_type": 3,
                            "gps_satellites": 9,
                            "lat": 28.44696,
                            "lng": 77.060921,
                            "accuracy": 0,
                            "speed": 4.01,
                            "ignition": 1,
                            "engine": 0,
                            "cell_data": "",
                            "hdop": 0.7,
                            "bearing": 37,
                            "address": null,
                            "aux_0_value": 0,
                            "aux_1_value": 1,
                            "aux_2_value": 1,
                            "aux_3_value": null,
                            "links": {
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    }
                }
            }
        }

## Vehicle Gsense [/v1/vehicles/{id}/gsense]

+ Parameters
    + id: 1 (number) - The Vehicle ID

### Update Vehicle Gsense [PUT]  

Updates a vehicles gsense

+ Request (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Vehicle Gsense Request)
    
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle": {
                "data": {
                    "id": 8382,
                    "customer_id": 2556,
                    "unit_id": 28940,
                    "dealership_id": 1262,
                    "fitter_id": 4133,
                    "install_complete": 1,
                    "timestamp": 1509118785,
                    "installed": 1500884058,
                    "alias": "W040",
                    "vin": "M2TSF2E12H1G09648",
                    "registration": "M2TSF2E12H1G09648",
                    "make": "Kinetic",
                    "model": "Safar",
                    "colour": "Green",
                    "description": "E-Auto",
                    "fuel_type": "Other",
                    "type": "Other",
                    "odometer": 420.69,
                    "aux_0_name": "Aux 0",
                    "aux_0_string_on": "ON",
                    "aux_0_string_off": "OFF",
                    "aux_0_config_flags": 1033,
                    "aux_1_name": "Aux 1",
                    "aux_1_string_on": "ON",
                    "aux_1_string_off": "OFF",
                    "aux_1_config_flags": 1033,
                    "aux_2_name": null,
                    "aux_2_string_on": null,
                    "aux_2_string_off": null,
                    "aux_2_config_flags": 1033,
                    "aux_3_name": null,
                    "aux_3_string_on": null,
                    "aux_3_string_off": null,
                    "aux_3_config_flags": 0,
                    "g_sense": true,
                    "g_sense_number": "53125325",
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
                        "self": "{{API_URL}}/v1/vehicles/8382",
                        "journeys": "{{API_URL}}/v1/vehicles/8382/journeys"
                    },
                    "unit": {
                        "data": {
                            "id": 28940,
                            "hardware_id": 710105,
                            "app_id": 710206,
                            "type": "STX71F",
                            "subscription": {
                                "data": {
                                    "unit_id": 28940,
                                    "customer_id": 2556,
                                    "length": 12,
                                    "sub_start": "2017-07-24",
                                    "sub_end": "2018-07-24",
                                    "monitored": 0,
                                    "paypalSubscribedId": null,
                                    "status": null,
                                    "invoiced": 0
                                }
                            }
                        }
                    },
                    "groups": {
                        "data": [
                            {
                                "id": 398,
                                "customer_id": 2556,
                                "group_name": "Wazirabad Hub",
                                "group_description": "",
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicle-groups/398"
                                }
                            }
                        ]
                    },
                    "latest_journey": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "driver_id": null,
                            "start_time": 1509017194,
                            "end_time": 1509018190,
                            "start_lat": 28.442154,
                            "end_lat": 28.458572,
                            "start_lng": 77.056435,
                            "end_lng": 77.072845,
                            "start_address": "6, Arya Samaj Rd, Block D, Greenwood City, Sector 46, Gurugram, Haryana 122022, India",
                            "end_address": "Netaji Subhash Marg, Sector 44, Gurugram, Haryana 122003, India",
                            "average_speed": 9.81,
                            "top_speed": 25.36,
                            "total_idle_time": 0,
                            "longest_idle_time": 0,
                            "distance": 2.7551410101960347,
                            "auxCount": null,
                            "links": {
                                "self": "{{API_URL}}/v1/vehicles/8382/journeys/1509017194/1509018190",
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    },
                    "latest_position": {
                        "data": {
                            "vehicle_id": 8382,
                            "customer_id": 2556,
                            "timestamp": 1509118753,
                            "driver_id": null,
                            "health_check_id": 0,
                            "state": "UNSET",
                            "gps_type": 3,
                            "gps_satellites": 9,
                            "lat": 28.44696,
                            "lng": 77.060921,
                            "accuracy": 0,
                            "speed": 4.01,
                            "ignition": 1,
                            "engine": 0,
                            "cell_data": "",
                            "hdop": 0.7,
                            "bearing": 37,
                            "address": null,
                            "aux_0_value": 0,
                            "aux_1_value": 1,
                            "aux_2_value": 1,
                            "aux_3_value": null,
                            "links": {
                                "vehicle": "{{API_URL}}/v1/vehicles/8382",
                                "driver": null
                            }
                        }
                    }
                }
            }
        }

## Show Vehicle Journeys [/v1/vehicles/{id}/journeys{?from,to}]

+ Parameters
    + id: 1 (number) - The Vehicle ID
    + from: 1503316914 (integer, optional) - Get vehicle journeys after this date, required if `to` set
    + to: 1503316921 (integer, optional) - Get vehicle journeys before this date, required if `from` set
    
### Show Vehicle Journeys [GET]

Get individual vehicle, with latest journey, latest position and vehicle groups.

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
                        "vehicle_id": 8,
                        "customer_id": 1000,
                        "driver_id": null,
                        "start_time": 1458147538,
                        "end_time": 1458149468,
                        "start_lat": 53.6656,
                        "end_lat": 53.665634,
                        "start_lng": -2.621248,
                        "end_lng": -2.62121,
                        "start_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                        "end_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                        "average_speed": 0.04,
                        "top_speed": 0.68,
                        "total_idle_time": 0,
                        "longest_idle_time": 0,
                        "distance": 0.09475930980304523,
                        "aux_count": null,
                        "fare_data": 3,
                        "links": {
                            "self": "{{API_URL}}/v1/vehicles/8/journeys/1458147538/1458149468",
                            "vehicle": "{{API_URL}}/v1/vehicles/8",
                            "driver": null
                        }
                    },
                    {
                        "vehicle_id": 8,
                        "customer_id": 1000,
                        "driver_id": null,
                        "start_time": 1458131888,
                        "end_time": 1458143491,
                        "start_lat": 53.665588,
                        "end_lat": 53.665604,
                        "start_lng": -2.621193,
                        "end_lng": -2.621247,
                        "start_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                        "end_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                        "average_speed": 0.01,
                        "top_speed": 0.5,
                        "total_idle_time": 0,
                        "longest_idle_time": 0,
                        "distance": 0.19713801473238518,
                        "aux_count": null,
                        "fare_data": 3,
                        "links": {
                            "self": "{{API_URL}}/v1/vehicles/8/journeys/1458131888/1458143491",
                            "vehicle": "{{API_URL}}/v1/vehicles/8",
                            "driver": null
                        }
                    },
                    {
                        "vehicle_id": 8,
                        "customer_id": 1000,
                        "driver_id": null,
                        "start_time": 1458061097,
                        "end_time": 1458061113,
                        "start_lat": 53.665668,
                        "end_lat": 53.665642,
                        "start_lng": -2.621172,
                        "end_lng": -2.621175,
                        "start_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                        "end_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                        "average_speed": 0,
                        "top_speed": 0,
                        "total_idle_time": 0,
                        "longest_idle_time": 0,
                        "distance": 0.002896697682078459,
                        "aux_count": null,
                        "fare_data": 3,
                        "links": {
                            "self": "{{API_URL}}/v1/vehicles/8/journeys/1458061097/1458061113",
                            "vehicle": "{{API_URL}}/v1/vehicles/8",
                            "driver": null
                        }
                    },
                ],
                "meta": {
                    "total_items": 60,
                    "item_count": 25,
                    "total_pages": 3,
                    "current_page": 1
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## Show Vehicle Journey [/v1/vehicles/{id}/journeys/{startTime}/{endTime}]

+ Parameters
    + id: 1 (number) - The Vehicle ID
    + startTime: 1503316914 (integer, required) - Get vehicle journeys with this startTime
    + endTime: 1503316921 (integer, required) - Get vehicle journeys with this endTime
    
### Show Vehicle Journey [GET]

Get individual vehicle journey, with the positions of this journey.

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle_journey": {
                "data": {
                    "vehicle_id": 8,
                    "customer_id": 1000,
                    "driver_id": null,
                    "start_time": 1450706988,
                    "end_time": 1450716089,
                    "start_lat": 53.665558,
                    "end_lat": 53.66555,
                    "start_lng": -2.621138,
                    "end_lng": -2.621152,
                    "start_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                    "end_address": "38 Drumhead Rd, Chorley, Lancashire PR6 7BX, UK",
                    "average_speed": 337.02,
                    "top_speed": 390,
                    "total_idle_time": 1341,
                    "longest_idle_time": 1294,
                    "distance": 2457.520505786903,
                    "auxCount": null,
                    "links": {
                        "self": "{{API_URL}}/v1/vehicles/8/journeys/1450706988/1450716089",
                        "vehicle": "{{API_URL}}/v1/vehicles/8",
                        "driver": null
                    },
                    "positions": {
                        "data": [
                            {
                                "timestamp": 1450706988,
                                "state": "UNSET",
                                "lat": 53.665558,
                                "lng": -2.621138,
                                "speed": 0.18,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450706988"
                                }
                            },
                            {
                                "timestamp": 1450707035,
                                "state": "UNSET",
                                "lat": 53.674412,
                                "lng": -2.621102,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707035"
                                }
                            },
                            {
                                "timestamp": 1450707051,
                                "state": "UNSET",
                                "lat": 53.698112,
                                "lng": -2.621065,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707051"
                                }
                            },
                            {
                                "timestamp": 1450707067,
                                "state": "UNSET",
                                "lat": 53.721809,
                                "lng": -2.621028,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707067"
                                }
                            },
                            {
                                "timestamp": 1450707082,
                                "state": "UNSET",
                                "lat": 53.744026,
                                "lng": -2.621028,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707082"
                                }
                            },
                            {
                                "timestamp": 1450707098,
                                "state": "UNSET",
                                "lat": 53.767723,
                                "lng": -2.621048,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707098"
                                }
                            },
                            {
                                "timestamp": 1450707114,
                                "state": "UNSET",
                                "lat": 53.791424,
                                "lng": -2.6211,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707114"
                                }
                            },
                            {
                                "timestamp": 1450707130,
                                "state": "UNSET",
                                "lat": 53.815121,
                                "lng": -2.621142,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707130"
                                }
                            },
                            {
                                "timestamp": 1450707146,
                                "state": "UNSET",
                                "lat": 53.838818,
                                "lng": -2.621185,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707146"
                                }
                            },
                            {
                                "timestamp": 1450707162,
                                "state": "UNSET",
                                "lat": 53.862514,
                                "lng": -2.621208,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707162"
                                }
                            },
                            {
                                "timestamp": 1450707178,
                                "state": "UNSET",
                                "lat": 53.886211,
                                "lng": -2.621205,
                                "speed": 390,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 334,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450707178"
                                }
                            },
                            {
                                "timestamp": 1450715980,
                                "state": "UNSET",
                                "lat": 53.665649,
                                "lng": -2.621302,
                                "speed": 0.02,
                                "ignition": 1,
                                "engine": 1,
                                "bearing": 0,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450715980"
                                }
                            },
                            {
                                "timestamp": 1450716089,
                                "state": "SET",
                                "lat": 53.66555,
                                "lng": -2.621152,
                                "speed": 0.1,
                                "ignition": 0,
                                "engine": 1,
                                "bearing": 0,
                                "links": {
                                    "self": "{{API_URL}}/v1/vehicles/8/positions/1450716089"
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

## Vehicle [/v1/vehicles/{id}/coupons]

+ Parameters
    + id: 1 (number) - The Vehicle ID
    
### Coupon [GET]

Get coupons linked to the vehicle

+ Request
    + Headers
    
            Authorization: Basic {BASE64-JWT-TOKEN}
        
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "coupon": [
                {
                    "couponId": "12345",
                    "couponName": "FREE-1YR-1234",
                    "redeemed": null|"2020-01-01 12:12:12",
                    "couponCode": "abc12345",
                    "message": "This is information about the coupon, this can be shown to the customer"
                }
            ],
            "auth": {
                "data": {
                "token": "sesgsgggggggggggggbeh542h452wh2w45h2w4hw4"
                }
             }
        }