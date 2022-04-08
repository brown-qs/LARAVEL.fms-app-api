# Group Geocoding

## Reverse Geocode [/v1/geocode{?lat,lng}]

### Reverse Geocode [GET]

+ Parameters
    + lat: 50.236523 (float, required) - The latitude
    + lng: 0.252523 (float, required) - The longitude
    
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "address": {
                "data": {
                    "lat": 51.5327729,
                    "lng": -0.2635502,
                    "house": "212",
                    "street": "Acton Lane",
                    "locality": "London",
                    "postCode": "NW10 7NH",
                    "country": "United Kingdom",
                    "address": "212, Acton Lane, London, NW10 7NH"
                }
            },
            "auth": {
                "data": {
                    "token": "{BASE64_JWT_TOKEN}"
                }
            }
        }

+ Response 404 (application/json)

        {
          "status": 404,
          "status_desc": "Not Found",
          "errors": [
            "No Address Found for Lat/Lng"
          ]
        }
