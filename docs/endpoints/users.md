# Group Users

## Users [/v1/users{?search,limit}]

### Show Users [GET]

Paginates the users.

+ Parameters
    + search: Mark (string, optional) - Search by user name, mobile phone, or email
    + limit: 50 (integer, optional) - Pagination limit number, defaults to `25`

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
                        "id": 4554,
                        "customer_id": 1000,
                        "dealership_id": 0,
                        "type": "CustomerSuper",
                        "first_name": "Kieran",
                        "last_name": "Williams",
                        "email": "kieran.williams@scorpionauto.com",
                        "active": 1,
                        "timezone": "Europe/London",
                        "mobile_phone": "+447436267668",
                        "last_login": 1503330477,
                        "last_active": 1503578177,
                        "distance_units": "miles",
                        "volume_units": "gallons",
                        "security_question": null,
                        "links": {
                            "self": "{API_URL}/v1/users/4554"
                        }
                    },
                    {
                        "id": 1001,
                        "customer_id": 1000,
                        "dealership_id": 0,
                        "type": "CustomerSuper",
                        "first_name": "Mark",
                        "last_name": "Downing",
                        "email": "mark1@scorpiontrack.com",
                        "active": 1,
                        "timezone": "Europe/London",
                        "mobile_phone": "+447710510126",
                        "last_login": 1457630594,
                        "last_active": 1503570381,
                        "distance_units": "miles",
                        "volume_units": "gallons",
                        "security_question": "test",
                        "links": {
                            "self": "{API_URL}/v1/users/1001"
                        }
                    }
                ],
                "meta": {
                    "total_items": 35,
                    "item_count": 2,
                    "total_pages": 18,
                    "current_page": 1
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

## User [/v1/users/{id}]

+ Parameters
    + id: 1 (number) - The User ID

### Show [GET]

Shows an individual user

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "user": {z
                "data": {
                    "id": 4554,
                    "customer_id": 1000,
                    "dealership_id": 0,
                    "type": "CustomerSuper",
                    "first_name": "Kieran",
                    "last_name": "Williams",
                    "email": "kieran.williams@scorpionauto.com",
                    "active": 1,
                    "timezone": "Europe/London",
                    "mobile_phone": "+447436267668",
                    "last_login": 1503330477,
                    "last_active": 1503578177,
                    "distance_units": "miles",
                    "volume_units": "gallons",
                    "security_question": null,
                    "links": {
                        "self": "{API_URL}/v1/users/4554"
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

Updates an individual user

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (User Update Request)
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "user": {
                "data": {
                    "id": 4554,
                    "customer_id": 1000,
                    "dealership_id": 0,
                    "type": "CustomerSuper",
                    "first_name": "Kieran",
                    "last_name": "Williams",
                    "email": "kieran.williams@scorpionauto.com",
                    "active": 1,
                    "timezone": "Europe/London",
                    "mobile_phone": "+447436267668",
                    "last_login": 1503330477,
                    "last_active": 1503578177,
                    "distance_units": "miles",
                    "volume_units": "gallons",
                    "security_question": null,
                    "links": {
                        "self": "{API_URL}/v1/users/4554"
                    }
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }
