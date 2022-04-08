# Group Config

## Config [/config]

### Config [GET]

Getting the config for the API
    
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "config": {
                "api_url": "{{API_URL}}",
                "api_version": "1",
                "api_debug": false,
                "api_env": "production",
                "api_locale": "en",
                "vehicle_states": {
                    "UNSET": 1,
                    "SET": 2,
                    "ALT": 3,
                    "ALM": 4,
                    "UNSUB": 5,
                    "INST": 6,
                    "SLP": 7
                },
                "geofence_types": {
                    "fixed": 1,
                    "plot": 2,
                    "polygon": 3
                },
                "min_app_version": 1,
                "countries": {
                    "AE": "United Arab Emirates",
                    "DE": "Germany",
                    "FR": "France",
                    "GB": "United Kingdom",
                    "IE": "Ireland",
                    "IN": "India",
                    "MY": "Malaysia",
                    "SG": "Singapore",
                    "TH": "Thailand",
                    "TT": "Trinidad and Tobago",
                    "ZA": "South Africa"
                }
            }
        }

