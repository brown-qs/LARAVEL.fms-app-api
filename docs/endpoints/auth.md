# Group Auth

## Login [/v1/auth/login{?vehicles_limit,vehicle_groups_limit}]

### Login [POST]

Login to the API, using email and password. Will return a JWT token for use on the next request and the user, customer, 
vehicle group, and the most recently updated vehicles (does not necessarily mean the ones that have moved most recently).

+ Parameters
    + vehicles_limit: 50 (integer, optional) - Pagination vehicle limit number, defaults to `25`
    + vehicle_groups_limit: 50 (integer, optional) - Pagination vehicle group limit number, defaults to `25`

+ Request Auth Request (application/json)
    + Attributes (Auth Request)
        
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
                    "id": 1258,
                    "customer_id": 1000,
                    "dealership_id": 0,
                    "type": "CustomerSuper",
                    "first_name": "Chris",
                    "last_name": "O'Hare",
                    "email": "test@haredigital.com",
                    "active": 1,
                    "timezone": "Europe/London",
                    "mobile_phone": "+447777777777",
                    "last_login": 1502791452,
                    "last_active": 1502896188,
                    "distance_units": "miles",
                    "volume_units": "gallons",
                    "security_question": "0",
                    "customer": {
                        "data": {
                            "id": 1000,
                            "dealership_id": 1000,
                            "company": "Scorpion Automotive Ltd",
                            "address": "Scorpion Automotive",
                            "address2": "Drumhead Road",
                            "address3": "Chorley North Business Park",
                            "county": "Lancashire",
                            "postcode": "PR6 7DE",
                            "country": "GB",
                            "timezone": "Europe/London",
                            "primary_phone": "+441257249928",
                            "fax": "",
                            "email": "tracking@scorpionauto.com",
                            "description": "",
                            "texts": 13540,
                            "new_user_notify": 0,
                            "new_driver_notify": 0,
                            "show_map_speed": 0,
                            "gsense": 0,
                            "invoiced_monthly": 0,
                            "vehicle_groups": {
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
                                ]
                            }
                        }
                    }
                }
            }
        }
      
+ Response 403 (application/json)

        {
            "status": 403,
            "status_desc": "Forbidden",
            "errors": [
                "This app is for customer user only."
            ]
        }
        
+ Response 403 (application/json)

        {
            "status": 403,
            "status_desc": "Forbidden",
            "errors": [
                "Incorrect Password"
            ]
        }

+ Response 404 (application/json)

        {
            "status": 404,
            "status_desc": "Not Found",
            "errors": [
                "User Not Found"
            ]
        }
