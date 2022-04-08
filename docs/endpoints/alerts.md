# Group Alerts

## Alerts [/v1/alerts]

### Show Alerts [GET]

Paginates the alerts.

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
            "alerts": {
                "data": [
                    {
                        "alert_id": 88,
                        "customer_id": 1000,
                        "vehicle_id": 0,
                        "group_id": 0,
                        "name": "GFV Test",
                        "description": "",
                        "type": "GFV",
                        "level": "Alarm",
                        "email": "",
                        "txt": "+447948276860",
                        "aux_id": 0,
                        "speed_limit": 4.828032,
                        "days": null,
                        "idle_limit": 3,
                        "engine_limit": 5,
                        "geofence_id": 0,
                        "sunday_start": "00:00:00",
                        "sunday_end": "23:59:59",
                        "monday_start": "00:00:00",
                        "monday_end": "23:59:59",
                        "tuesday_start": "00:00:00",
                        "tuesday_end": "23:59:59",
                        "wednesday_start": "00:00:00",
                        "wednesday_end": "23:59:59",
                        "thursday_start": "00:00:00",
                        "thursday_end": "23:59:59",
                        "friday_start": "00:00:00",
                        "friday_end": "23:59:59",
                        "saturday_start": "00:00:00",
                        "saturday_end": "23:59:59",
                        "timezone": "Europe/London",
                        "speedLimitMargin": 20,
                        "links": {
                            "self": "{{API_URL}}/v1/alerts/88"
                        }
                    },
                    {
                        "alert_id": 90,
                        "customer_id": 1000,
                        "vehicle_id": 0,
                        "group_id": 0,
                        "name": "deviation from assigned route",
                        "description": "",
                        "type": "GF Exit",
                        "level": "Alarm",
                        "email": "",
                        "txt": "+447545949775",
                        "aux_id": 0,
                        "speed_limit": 4.828032,
                        "days": null,
                        "idle_limit": 3,
                        "engine_limit": 5,
                        "geofence_id": 270,
                        "sunday_start": "00:00:00",
                        "sunday_end": "23:59:59",
                        "monday_start": "00:00:00",
                        "monday_end": "23:59:59",
                        "tuesday_start": "00:00:00",
                        "tuesday_end": "23:59:59",
                        "wednesday_start": "00:00:00",
                        "wednesday_end": "23:59:59",
                        "thursday_start": "00:00:00",
                        "thursday_end": "23:59:59",
                        "friday_start": "00:00:00",
                        "friday_end": "23:59:59",
                        "saturday_start": "00:00:00",
                        "saturday_end": "23:59:59",
                        "timezone": "Europe/London",
                        "speedLimitMargin": 20,
                        "links": {
                            "self": "{{API_URL}}/v1/alerts/90"
                        }
                    },
                ]
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

### Create Alert [POST]

Creates an alert

+ Request Alert Request (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Alert Request)

+ Response 200 (application/json)
        
        {
            "status": 200,
            "status_desc": "OK",
            "alerts": {
                "data": [
                    {
                        "alert_id": 88,
                        "customer_id": 1000,
                        "vehicle_id": 0,
                        "group_id": 0,
                        "name": "GFV Test",
                        "description": "",
                        "type": "GFV",
                        "level": "Alarm",
                        "email": "",
                        "txt": "+447948276860",
                        "aux_id": 0,
                        "speed_limit": 4.828032,
                        "days": null,
                        "idle_limit": 3,
                        "engine_limit": 5,
                        "geofence_id": 0,
                        "sunday_start": "00:00:00",
                        "sunday_end": "23:59:59",
                        "monday_start": "00:00:00",
                        "monday_end": "23:59:59",
                        "tuesday_start": "00:00:00",
                        "tuesday_end": "23:59:59",
                        "wednesday_start": "00:00:00",
                        "wednesday_end": "23:59:59",
                        "thursday_start": "00:00:00",
                        "thursday_end": "23:59:59",
                        "friday_start": "00:00:00",
                        "friday_end": "23:59:59",
                        "saturday_start": "00:00:00",
                        "saturday_end": "23:59:59",
                        "timezone": "Europe/London",
                        "speedLimitMargin": 20,
                        "links": {
                            "self": "{{API_URL}}/v1/alerts/88"
                        }
                    },
                    {
                        "alert_id": 90,
                        "customer_id": 1000,
                        "vehicle_id": 0,
                        "group_id": 0,
                        "name": "deviation from assigned route",
                        "description": "",
                        "type": "GF Exit",
                        "level": "Alarm",
                        "email": "",
                        "txt": "+447545949775",
                        "aux_id": 0,
                        "speed_limit": 4.828032,
                        "days": null,
                        "idle_limit": 3,
                        "engine_limit": 5,
                        "geofence_id": 270,
                        "sunday_start": "00:00:00",
                        "sunday_end": "23:59:59",
                        "monday_start": "00:00:00",
                        "monday_end": "23:59:59",
                        "tuesday_start": "00:00:00",
                        "tuesday_end": "23:59:59",
                        "wednesday_start": "00:00:00",
                        "wednesday_end": "23:59:59",
                        "thursday_start": "00:00:00",
                        "thursday_end": "23:59:59",
                        "friday_start": "00:00:00",
                        "friday_end": "23:59:59",
                        "saturday_start": "00:00:00",
                        "saturday_end": "23:59:59",
                        "timezone": "Europe/London",
                        "speedLimitMargin": 20,
                        "links": {
                            "self": "{{API_URL}}/v1/alerts/90"
                        }
                    },
                ]
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## Alert [/v1/alerts/{id}]

+ Parameters
    + id: 1 (number) - The Alert ID

### Show [GET]

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}

+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "alert": {
                "data": {
                    "alert_id": 88,
                    "customer_id": 1000,
                    "vehicle_id": 0,
                    "group_id": 0,
                    "name": "GFV Test",
                    "description": "",
                    "type": "GFV",
                    "level": "Alarm",
                    "email": "",
                    "txt": "+447948276860",
                    "aux_id": 0,
                    "speed_limit": 4.828032,
                    "days": null,
                    "idle_limit": 3,
                    "engine_limit": 5,
                    "geofence_id": 0,
                    "sunday_start": "00:00:00",
                    "sunday_end": "23:59:59",
                    "monday_start": "00:00:00",
                    "monday_end": "23:59:59",
                    "tuesday_start": "00:00:00",
                    "tuesday_end": "23:59:59",
                    "wednesday_start": "00:00:00",
                    "wednesday_end": "23:59:59",
                    "thursday_start": "00:00:00",
                    "thursday_end": "23:59:59",
                    "friday_start": "00:00:00",
                    "friday_end": "23:59:59",
                    "saturday_start": "00:00:00",
                    "saturday_end": "23:59:59",
                    "timezone": "Europe/London",
                    "speedLimitMargin": 20,
                    "links": {
                        "self": "{{API_URL}}/v1/alerts/88"
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

+ Request Alert Request (application/json)
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Alert Request)

+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "alert": {
                "data": {
                    "alert_id": 88,
                    "customer_id": 1000,
                    "vehicle_id": 0,
                    "group_id": 0,
                    "name": "GFV Test",
                    "description": "",
                    "type": "GFV",
                    "level": "Alarm",
                    "email": "",
                    "txt": "+447948276860",
                    "aux_id": 0,
                    "speed_limit": 4.828032,
                    "days": null,
                    "idle_limit": 3,
                    "engine_limit": 5,
                    "geofence_id": 0,
                    "sunday_start": "00:00:00",
                    "sunday_end": "23:59:59",
                    "monday_start": "00:00:00",
                    "monday_end": "23:59:59",
                    "tuesday_start": "00:00:00",
                    "tuesday_end": "23:59:59",
                    "wednesday_start": "00:00:00",
                    "wednesday_end": "23:59:59",
                    "thursday_start": "00:00:00",
                    "thursday_end": "23:59:59",
                    "friday_start": "00:00:00",
                    "friday_end": "23:59:59",
                    "saturday_start": "00:00:00",
                    "saturday_end": "23:59:59",
                    "timezone": "Europe/London",
                    "speedLimitMargin": 20,
                    "links": {
                        "self": "{{API_URL}}/v1/alerts/88"
                    }
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }
        
### Delete [DELETE]

+ Request (application/json)
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
