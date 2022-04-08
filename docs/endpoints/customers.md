# Group Customers

## Customer [/v1/customers]

### Show [GET]

Shows your parent customer

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "customer": {
                "data": {
                    "id": 2556,
                    "dealership_id": 1262,
                    "company": "Smart-E",
                    "address": "Mansa Ram Park",
                    "address2": "Uttam Nagar",
                    "address3": "",
                    "county": "New Delhi",
                    "postcode": "110059",
                    "country": "IN",
                    "timezone": "Asia/Kolkata",
                    "primary_phone": "+918800441179",
                    "fax": "",
                    "email": "goldiegold@gmail.com",
                    "description": "",
                    "texts": 355,
                    "new_user_notify": 1,
                    "new_driver_notify": 1,
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

### Update [PUT]

Updates your parent customer

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Customer Request)
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "customer": {
                "data": {
                    "id": 2556,
                    "dealership_id": 1262,
                    "company": "Smart-E",
                    "address": "Mansa Ram Park",
                    "address2": "Uttam Nagar",
                    "address3": "",
                    "county": "New Delhi",
                    "postcode": "110059",
                    "country": "IN",
                    "timezone": "Asia/Kolkata",
                    "primary_phone": "+918800441179",
                    "fax": "",
                    "email": "goldiegold@gmail.com",
                    "description": "",
                    "texts": 355,
                    "new_user_notify": 1,
                    "new_driver_notify": 1,
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
