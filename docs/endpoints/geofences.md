# Group Geofences

## Show Geofences [/v1/geofences{?limit}]

### Show [GET]

Paginates the geofences

+ Parameters
    + limit: 50 (integer, optional) - Pagination limit number, defaults to `25`

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "geofences": {
                "data": [
                    {
                        "id": 181417,
                        "name": "Test OTL",
                        "type": "polygon",
                        "lat": 51.532925,
                        "lng": -0.263683,
                        "radius": 500,
                        "description": "Test OTL",
                        "colour": "FF0000",
                        "geofence_data": {
                            "data": {
                                "points": [
                                    {
                                        "lat": 51.53292627844152,
                                        "lng": -0.26368260383605957
                                    },
                                    {
                                        "lat": 51.53400064894889,
                                        "lng": -0.2620142698287964
                                    },
                                    {
                                        "lat": 51.5329558572084,
                                        "lng": -0.26090383529663086
                                    },
                                    {
                                        "lat": 51.53196809052194,
                                        "lng": -0.26328563690185547
                                    },
                                    {
                                        "lat": 51.53292627844152,
                                        "lng": -0.26368260383605957
                                    }
                                ],
                                "radius": 500
                            }
                        }
                    },
                    {
                        "id": 166828,
                        "name": "Sue Home",
                        "type": "polygon",
                        "lat": 51.606434,
                        "lng": -1.799841,
                        "radius": 500,
                        "description": "",
                        "colour": "FF0000",
                        "geofence_data": {
                            "data": {
                                "points": [
                                    {
                                        "lat": 51.606434,
                                        "lng": -1.799841
                                    },
                                    {
                                        "lat": 51.606281,
                                        "lng": -1.7999
                                    },
                                    {
                                        "lat": 51.606117,
                                        "lng": -1.799725
                                    },
                                    {
                                        "lat": 51.606449,
                                        "lng": -1.799226
                                    },
                                    {
                                        "lat": 51.606529,
                                        "lng": -1.799591
                                    },
                                    {
                                        "lat": 51.606522,
                                        "lng": -1.79977
                                    },
                                    {
                                        "lat": 51.606434,
                                        "lng": -1.799841
                                    }
                                ],
                                "radius": 1
                            }
                        }
                    },
                    {
                        "id": 125988,
                        "name": "Adam's home",
                        "type": "fixed",
                        "lat": 52.651943,
                        "lng": -2.016184,
                        "radius": 46,
                        "description": "",
                        "colour": "FF0000",
                        "geofence_data": {
                            "data": {
                                "points": [
                                    {
                                        "lat": 52.651943,
                                        "lng": -2.016184
                                    }
                                ],
                                "radius": 46
                            }
                        }
                    },
                ],
                "meta": {
                    "total_items": 59,
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

### Create [POST]

Create a new geofence

+ Request Create Fixed/Circle Geofence (application/json)
    + Headers
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Create Fixed Geofence Request)
    
+ Request Create Polygon Geofence (application/json)
    + Headers
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Create Polygon Geofence Request)

+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "geofence": {
                "data": {
                    "id": 191484,
                    "name": "testtt",
                    "type": "polygon",
                    "lat": 52,
                    "lng": 0,
                    "radius": 0,
                    "description": null,
                    "colour": "FF0000",
                    "geofence_data": {
                        "data": {
                            "points": [
                                {
                                    "lat": 52,
                                    "lng": 0
                                },
                                {
                                    "lat": 53,
                                    "lng": 0
                                },
                                {
                                    "lat": 54,
                                    "lng": 0
                                },
                                {
                                    "lat": 52,
                                    "lng": 0
                                }
                            ],
                            "radius": 0
                        }
                    }
                }
            }
        }

## Geofence [/v1/geofences/{id}]

+ Parameters
    + id: 1 (number) - The Geofence ID

### Show [GET]

Returns a single geofence

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "geofence": {
                "data": {
                    "id": 181417,
                    "name": "Test OTL",
                    "type": "polygon",
                    "lat": 51.532925,
                    "lng": -0.263683,
                    "radius": 500,
                    "description": "Test OTL",
                    "colour": "FF0000",
                    "geofence_data": {
                        "data": {
                            "points": [
                                {
                                    "lat": 51.53292627844152,
                                    "lng": -0.26368260383605957
                                },
                                {
                                    "lat": 51.53400064894889,
                                    "lng": -0.2620142698287964
                                },
                                {
                                    "lat": 51.5329558572084,
                                    "lng": -0.26090383529663086
                                },
                                {
                                    "lat": 51.53196809052194,
                                    "lng": -0.26328563690185547
                                },
                                {
                                    "lat": 51.53292627844152,
                                    "lng": -0.26368260383605957
                                }
                            ],
                            "radius": 500
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

Update a geofence

+ Request Update Fixed/Circle Geofence (application/json)
    + Headers
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Fixed Geofence Request)
    
+ Request Update Polygon Geofence (application/json)
    + Headers
            Authorization: Basic {BASE64-JWT-TOKEN}
            
    + Attributes (Update Polygon Geofence Request)

+ Response 200 (application/json)

        {
            "status": 200,
            "status_desc": "OK",
            "geofence": {
                "data": {
                    "id": 191484,
                    "name": "testtt",
                    "type": "polygon",
                    "lat": 52,
                    "lng": 0,
                    "radius": 0,
                    "description": null,
                    "colour": "FF0000",
                    "geofence_data": {
                        "data": {
                            "points": [
                                {
                                    "lat": 52,
                                    "lng": 0
                                },
                                {
                                    "lat": 53,
                                    "lng": 0
                                },
                                {
                                    "lat": 54,
                                    "lng": 0
                                },
                                {
                                    "lat": 52,
                                    "lng": 0
                                }
                            ],
                            "radius": 0
                        }
                    }
                }
            }
        }
## Lookup Geofence [/v1/geofences/lookup]

### Lookup Geofence [GET]

Lookups up supplied lat, lng to check and return if they are in geofence(s).

+ Parameters
    + lat: 52.651913 (float, optional) - The latitude
    + lng: `-2.01609` (float, optional) - The longitude

+ Request
    + Headers
        
            Authorization: Basic {BASE64-JWT-TOKEN}
            
+ Response 200 (application/json)

       {
           "status": 200,
           "status_desc": "OK",
           "geofences": {
               "data": [
                   {
                       "id": 125988,
                       "name": "Adam's home",
                       "type": "fixed",
                       "lat": 52.651943,
                       "lng": -2.016184,
                       "radius": 46,
                       "description": "",
                       "colour": "FF0000",
                       "geofence_data": {
                           "data": {
                               "points": [
                                   {
                                       "lat": 52.651943,
                                       "lng": -2.016184
                                   }
                               ],
                               "radius": 46
                           }
                       }
                   }
               ]
           },
           "auth": {
               "data": {
                   "token": "{BASE64_JWT_TOKEN}"
               }
           }
       }
