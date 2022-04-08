# Group Alert Events

## Alerts [/v1/alert-events]

### Show Alert Events [GET]

Shows the alerts events

+ Parameters
    + limit: 50 (integer, optional) - Pagination limit number, defaults to `25`
    + include_read: true (boolean, optional) - Includes the already read alerts

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "alert_events": {
                "data": [
                    {
                        "alert_event_id": 3392745,
                        "alert_id": 3635,
                        "customer_id": 1000,
                        "vehicle_id": 7011,
                        "timestamp": 1503558849,
                        "mark_read": true,
                        "idle": 0,
                        "engine_hours": null,
                        "aux_id": 0,
                        "road_speed": null,
                        "driver_speed": null,
                        "links": {
                            "self": "{{API_URL}}/v1/alert-events/3392745"
                        }
                    }
                ],
                "meta": {
                    "total_items": 641,
                    "item_count": 1,
                    "total_pages": 641,
                    "current_page": 1
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## Alert [/v1/alert-events/{id}]

+ Parameters
    + id: 1 (number) - The Alert ID

### Show Alert Event [GET]

Show a single alert event

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}

+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "alert_event": {
                "data": {
                    "alert_event_id": 3392745,
                    "alert_id": 3635,
                    "customer_id": 1000,
                    "vehicle_id": 7011,
                    "timestamp": 1503558849,
                    "mark_read": true,
                    "idle": 0,
                    "engine_hours": null,
                    "aux_id": 0,
                    "road_speed": null,
                    "driver_speed": null,
                    "links": {
                        "self": "{{API_URL}}/v1/alert-events/3392745"
                    },
                    "position": {
                        "data": {
                            "vehicle_id": 7011,
                            "customer_id": 1000,
                            "timestamp": 1503558735,
                            "driver_id": null,
                            "health_check_id": 0,
                            "state": "UNSET",
                            "gps_type": 3,
                            "gps_satellites": 9,
                            "lat": 53.676258,
                            "lng": -2.627625,
                            "accuracy": 0,
                            "speed": 113.13,
                            "ignition": 1,
                            "engine": 1,
                            "cell_data": "",
                            "hdop": 0.7,
                            "bearing": 175,
                            "address": "M61, Chorley PR6, UK",
                            "aux_0_value": 0,
                            "aux_1_value": 0,
                            "aux_2_value": 0,
                            "aux_3_value": null,
                            "links": {
                                "vehicle": "{{API_URL}}/v1/vehicles/7011",
                                "driver": null
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

## Mark Read [/v1/alert-events/mark-read]

### Mark Read [POST]

Mark the alert events as read

+ Request Mark Read Alert Event Request (application/json)
    + Headers
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Mark Read Alert Event Request)


+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "alert_event": {
                "data": {
                    "alert_event_id": 3392745,
                    "alert_id": 3635,
                    "customer_id": 1000,
                    "vehicle_id": 7011,
                    "timestamp": 1503558849,
                    "mark_read": true,
                    "idle": 0,
                    "engine_hours": null,
                    "aux_id": 0,
                    "road_speed": null,
                    "driver_speed": null,
                    "links": {
                        "self": "{{API_URL}}/v1/alert-events/3392745"
                    },
                    "position": {
                        "data": {
                            "vehicle_id": 7011,
                            "customer_id": 1000,
                            "timestamp": 1503558735,
                            "driver_id": null,
                            "health_check_id": 0,
                            "state": "UNSET",
                            "gps_type": 3,
                            "gps_satellites": 9,
                            "lat": 53.676258,
                            "lng": -2.627625,
                            "accuracy": 0,
                            "speed": 113.13,
                            "ignition": 1,
                            "engine": 1,
                            "cell_data": "",
                            "hdop": 0.7,
                            "bearing": 175,
                            "address": "M61, Chorley PR6, UK",
                            "aux_0_value": 0,
                            "aux_1_value": 0,
                            "aux_2_value": 0,
                            "aux_3_value": null,
                            "links": {
                                "vehicle": "{{API_URL}}/v1/vehicles/7011",
                                "driver": null
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
