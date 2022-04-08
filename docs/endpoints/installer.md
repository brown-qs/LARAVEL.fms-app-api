# Group Installer

## Customer [/v1/installer/customers{?email}]

### Find [GET]

Finds customers from your brand, based on an email 

+ Parameters
    + query: Mark (string, optional) - Partial search of customer email/company name    

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "customers": [
                {
                    "customerId": 1000,
                    "company": "Example Company Name",
                    "email": "test@mail.com"
                }
            ],
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }


## Unit [/v1/installer/unit/{unitId}]

### Find [GET]

Finds customers from your brand, based on an email 

+ Parameters
    + unitId: 1326 (string, required) - The ID of the unit that you want to check    

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "unit": {
                "unitId": 1326,
                "type": "ST70",
                "inUse": 0
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

