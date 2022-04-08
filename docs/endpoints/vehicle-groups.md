# Group Vehicle Groups

## Vehicle Groups [/v1/vehicle-groups{?search,limit}]

### Show [GET]

Paginates the vehicle groups.

+ Parameters
    + search: amazon (string, optional) - Vehicle group name or description to search by
    + limit: 50 (integer, optional) - Pagination limit number, defaults to `25`

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "vehicle_groups": {
                "data": [
                    {
                        "id": 1,
                        "customer_id": 1000,
                        "group_name": "More Vehicles",
                        "group_description": "",
                        "links": {
                            "self": "#{{API_URL}}/v1/vehicle-groups/1"
                        }
                    },
                    {
                        "id": 8,
                        "customer_id": 1000,
                        "group_name": "Scorpion Employees",
                        "group_description": "Group for Scorpion Automotive employees.",
                        "links": {
                            "self": "{{API_URL}}/v1/vehicle-groups/8"
                        }
                    },
                    {
                        "id": 9,
                        "customer_id": 1000,
                        "group_name": "Scorpion MD",
                        "group_description": "Group for Mark's Car",
                        "links": {
                            "self": "{{API_URL}}/v1/vehicle-groups/9"
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
                    },
                    {
                        "id": 46,
                        "customer_id": 1000,
                        "group_name": "heating",
                        "group_description": "",
                        "links": {
                            "self": "{{API_URL}}/v1/vehicle-groups/46"
                        }
                    },
                    {
                        "id": 184,
                        "customer_id": 1000,
                        "group_name": "On the road",
                        "group_description": "",
                        "links": {
                            "self": "{{API_URL}}/v1/vehicle-groups/184"
                        }
                    },
                    {
                        "id": 334,
                        "customer_id": 1000,
                        "group_name": "Rob's Vehicles",
                        "group_description": "",
                        "links": {
                            "self": "{{API_URL}}/v1/vehicle-groups/334"
                        }
                    },
                    {
                        "id": 360,
                        "customer_id": 1000,
                        "group_name": "DP Test Group",
                        "group_description": "DP Car",
                        "links": {
                            "self": "{{API_URL}}/v1/vehicle-groups/360"
                        }
                    }
                ],
                "meta": {
                    "total_items": 8,
                    "item_count": 8,
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

### Create [POST]

Create a new vehicle group, with name, description and a list of vehicle ids.

+ Request Create Vehicle Group Request (application/json)
    + Headers
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Create Vehicle Group Request)
        
+ Response 200 (application/json)
  
  {
      "status": 200,
      "status_desc": "OK",
      "vehicle_group": {
          "data": {
              "id": 393,
              "customer_id": 1000,
              "group_name": "test group name",
              "group_description": "test group description",
              "links": {
                  "self": "{{API_URL}}/v1/vehicle-groups/393"
              },
              "vehicles": {
                  "data": [
                      {
                          "id": 1835,
                          "customer_id": 1000,
                          "unit_id": 16766,
                          "dealership_id": 1000,
                          "fitter_id": 2361,
                          "install_complete": 1,
                          "timestamp": 1496946181,
                          "installed": 1449226481,
                          "alias": "PF14OPD - Adam Bradbury - 07879334953",
                          "vin": "VF37D9HF0EJ709005",
                          "registration": "PF14OPD",
                          "make": "Peugeot",
                          "model": "Partner",
                          "colour": "Silver",
                          "description": "L1 1.6 HDi 92 850 Professional",
                          "fuel_type": "Diesel",
                          "type": "Van",
                          "odometer": 249306.24190402994,
                          "aux_0_name": "Door Status",
                          "aux_0_string_on": "---",
                          "aux_0_string_off": "Open - with ignition ON",
                          "aux_0_config_flags": 9,
                          "aux_1_name": "Aux 1",
                          "aux_1_string_on": "+",
                          "aux_1_string_off": "-",
                          "aux_1_config_flags": 0,
                          "aux_2_name": null,
                          "aux_2_string_on": null,
                          "aux_2_string_off": null,
                          "aux_2_config_flags": 9,
                          "aux_3_name": null,
                          "aux_3_string_on": null,
                          "aux_3_string_off": null,
                          "aux_3_config_flags": 0,
                          "last_odo": 1496944323,
                          "links": {
                              "self": "{{API_URL}}/v1/vehicles/1835",
                              "journeys": "{{API_URL}}/v1/vehicles/1835/journeys"
                          },
                          "latest_position": {
                              "data": {
                                  "vehicle_id": 1835,
                                  "customer_id": 1000,
                                  "timestamp": 1496944372,
                                  "driver_id": 0,
                                  "health_check_id": 0,
                                  "state": "SLP",
                                  "gps_type": 3,
                                  "gps_satellites": 11,
                                  "lat": 52.651913,
                                  "lng": -2.01609,
                                  "accuracy": 0,
                                  "speed": 0,
                                  "ignition": 0,
                                  "engine": 0,
                                  "cell_data": "",
                                  "hdop": 0.8,
                                  "bearing": 277,
                                  "address": "4 Bluebell Ln, Great Wyrley, Walsall WS6, UK",
                                  "aux_0_value": 1,
                                  "aux_1_value": 0,
                                  "aux_2_value": 1,
                                  "aux_3_value": 0
                              }
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

## Vehicle Group [/v1/vehicle-groups/{id}]

+ Parameters
    + id: 1 (number) - The Vehicle ID
    
### Show Vehicle Group [GET]

Gets a single vehicle group

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
            
+ Response 200 (application/json)

          {
              "status": 200,
              "status_desc": "OK",
              "vehicle_group": {
                  "data": {
                      "id": 1,
                      "customer_id": 1000,
                      "group_name": "More Vehicles",
                      "group_description": "",
                      "links": {
                          "self": "{{API_URL}}/v1/vehicle-groups/1"
                      },
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
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicles/7"
                                  },
                                  "latest_position": {
                                      "data": {
                                          "vehicle_id": 7,
                                          "customer_id": 1000,
                                          "timestamp": 1463065453,
                                          "driver_id": 7,
                                          "health_check_id": 0,
                                          "state": "ALT",
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
                                          "aux_3_value": null
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
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicles/8"
                                  },
                                  "latest_position": {
                                      "data": {
                                          "vehicle_id": 8,
                                          "customer_id": 1000,
                                          "timestamp": 1458149541,
                                          "driver_id": 0,
                                          "health_check_id": 0,
                                          "state": "UNSET",
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
                                          "aux_3_value": null
                                      }
                                  }
                              },
                              {
                                  "id": 806,
                                  "customer_id": 1000,
                                  "unit_id": 0,
                                  "dealership_id": 1000,
                                  "fitter_id": 1336,
                                  "install_complete": 1,
                                  "timestamp": 1499719993,
                                  "installed": 1460983119,
                                  "alias": "",
                                  "vin": "BENCH",
                                  "registration": "FMS1328",
                                  "make": "BENCH",
                                  "model": "BENCH",
                                  "colour": "Black",
                                  "description": "Test unit",
                                  "fuel_type": "Petrol",
                                  "type": "Car",
                                  "odometer": 46.158465891937,
                                  "aux_0_name": "Aux 0",
                                  "aux_0_string_on": "High",
                                  "aux_0_string_off": "Low",
                                  "aux_0_config_flags": 137,
                                  "aux_1_name": "Aux 1",
                                  "aux_1_string_on": "High",
                                  "aux_1_string_off": "Low",
                                  "aux_1_config_flags": 0,
                                  "aux_2_name": null,
                                  "aux_2_string_on": null,
                                  "aux_2_string_off": null,
                                  "aux_2_config_flags": 9,
                                  "aux_3_name": null,
                                  "aux_3_string_on": null,
                                  "aux_3_string_off": null,
                                  "aux_3_config_flags": 0,
                                  "last_odo": 1461003145,
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicles/806"
                                  },
                                  "latest_position": {
                                      "data": {
                                          "vehicle_id": 806,
                                          "customer_id": 1000,
                                          "timestamp": 1461048083,
                                          "driver_id": 0,
                                          "health_check_id": 0,
                                          "state": "UNSET",
                                          "gps_type": 3,
                                          "gps_satellites": 9,
                                          "lat": 53.494003,
                                          "lng": -2.649393,
                                          "accuracy": 0,
                                          "speed": 23.04,
                                          "ignition": 1,
                                          "engine": 1,
                                          "cell_data": "",
                                          "hdop": 0.8,
                                          "bearing": 345,
                                          "address": "16 Wentworth Rd, Ashton-in-Makerfield, Wigan WN4 9TU, UK",
                                          "aux_0_value": 1,
                                          "aux_1_value": 0,
                                          "aux_2_value": 1,
                                          "aux_3_value": null
                                      }
                                  }
                              },
                              {
                                  "id": 2809,
                                  "customer_id": 2082,
                                  "unit_id": 10935,
                                  "dealership_id": 1000,
                                  "fitter_id": 2361,
                                  "install_complete": 1,
                                  "timestamp": 1503257448,
                                  "installed": 1427284246,
                                  "alias": "RSM - South",
                                  "vin": "WDD1760082V040771",
                                  "registration": "VE64YRN",
                                  "make": "Mercedes-Benz",
                                  "model": "A-Class",
                                  "colour": "White",
                                  "description": "A200 CDI",
                                  "fuel_type": "Diesel",
                                  "type": "Car",
                                  "odometer": 117480.53245713077,
                                  "aux_0_name": "Aux 0",
                                  "aux_0_string_on": "High",
                                  "aux_0_string_off": "Low",
                                  "aux_0_config_flags": 207,
                                  "aux_1_name": "Aux 1",
                                  "aux_1_string_on": "High",
                                  "aux_1_string_off": "Low",
                                  "aux_1_config_flags": 0,
                                  "aux_2_name": null,
                                  "aux_2_string_on": null,
                                  "aux_2_string_off": null,
                                  "aux_2_config_flags": 9,
                                  "aux_3_name": null,
                                  "aux_3_string_on": null,
                                  "aux_3_string_off": null,
                                  "aux_3_config_flags": 0,
                                  "last_odo": 1503255585,
                                  "links": {
                                      "self": "{{API_URL}}/v1/vehicles/2809"
                                  },
                                  "latest_position": {
                                      "data": {
                                          "vehicle_id": 2809,
                                          "customer_id": 2082,
                                          "timestamp": 1503255640,
                                          "driver_id": 0,
                                          "health_check_id": 0,
                                          "state": "SLP",
                                          "gps_type": 3,
                                          "gps_satellites": 10,
                                          "lat": 51.606369,
                                          "lng": -1.799627,
                                          "accuracy": 0,
                                          "speed": 0.1,
                                          "ignition": 0,
                                          "engine": 0,
                                          "cell_data": "",
                                          "hdop": 0.8,
                                          "bearing": 327,
                                          "address": "95 Thornhill Dr, Blunsdon St Andrew, Swindon SN25 4GG, UK",
                                          "aux_0_value": 1,
                                          "aux_1_value": 0,
                                          "aux_2_value": 1,
                                          "aux_3_value": null
                                      }
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
    
### Delete Vehicle Group [DELETE]

Deletes the given vehicle group

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
              }
          }

    
### Update Vehicle Group [PUT]

Updates the given vehicle group

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}

    + Attributes (Update Vehicle Group Request)
                      
+ Response 200 (application/json)

    {
      "status": 200,
      "status_desc": "OK",
      "vehicle_group": {
          "data": {
              "id": 393,
              "customer_id": 1000,
              "group_name": "test group name",
              "group_description": "test group description",
              "links": {
                  "self": "{{API_URL}}/v1/vehicle-groups/393"
              },
              "vehicles": {
                  "data": [
                      {
                          "id": 1835,
                          "customer_id": 1000,
                          "unit_id": 16766,
                          "dealership_id": 1000,
                          "fitter_id": 2361,
                          "install_complete": 1,
                          "timestamp": 1496946181,
                          "installed": 1449226481,
                          "alias": "PF14OPD - Adam Bradbury - 07879334953",
                          "vin": "VF37D9HF0EJ709005",
                          "registration": "PF14OPD",
                          "make": "Peugeot",
                          "model": "Partner",
                          "colour": "Silver",
                          "description": "L1 1.6 HDi 92 850 Professional",
                          "fuel_type": "Diesel",
                          "type": "Van",
                          "odometer": 249306.24190402994,
                          "aux_0_name": "Door Status",
                          "aux_0_string_on": "---",
                          "aux_0_string_off": "Open - with ignition ON",
                          "aux_0_config_flags": 9,
                          "aux_1_name": "Aux 1",
                          "aux_1_string_on": "+",
                          "aux_1_string_off": "-",
                          "aux_1_config_flags": 0,
                          "aux_2_name": null,
                          "aux_2_string_on": null,
                          "aux_2_string_off": null,
                          "aux_2_config_flags": 9,
                          "aux_3_name": null,
                          "aux_3_string_on": null,
                          "aux_3_string_off": null,
                          "aux_3_config_flags": 0,
                          "last_odo": 1496944323,
                          "links": {
                              "self": "{{API_URL}}/v1/vehicles/1835",
                              "journeys": "{{API_URL}}/v1/vehicles/1835/journeys"
                          },
                          "latest_position": {
                              "data": {
                                  "vehicle_id": 1835,
                                  "customer_id": 1000,
                                  "timestamp": 1496944372,
                                  "driver_id": 0,
                                  "health_check_id": 0,
                                  "state": "SLP",
                                  "gps_type": 3,
                                  "gps_satellites": 11,
                                  "lat": 52.651913,
                                  "lng": -2.01609,
                                  "accuracy": 0,
                                  "speed": 0,
                                  "ignition": 0,
                                  "engine": 0,
                                  "cell_data": "",
                                  "hdop": 0.8,
                                  "bearing": 277,
                                  "address": "4 Bluebell Ln, Great Wyrley, Walsall WS6, UK",
                                  "aux_0_value": 1,
                                  "aux_1_value": 0,
                                  "aux_2_value": 1,
                                  "aux_3_value": 0
                              }
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