# Data Structures

## Auth Request (object)
+ email: test@test.com (string, required)
+ password: password (string, required)

## Create Vehicle Group Request (object)
+ group_name: Test Group name (string, required)
+ group_description: Test Group Description (string, required)
+ vehicles: (array[Vehicle], required)

## Update Vehicle Group Request (object)
+ group_name: Test Group name (string, optional)
+ group_description: Test Group Description (string, optional)
+ vehicles: (array[Vehicle], optional)

## Vehicle (array)
+ vehicle_id: 25 (number)

## Update Vehicle (object)
+ odometer: 255.5555 (number, optional)
+ alias: My Vehicle (number, optional)
+ zero_speed_mode_enabled: true (boolean, optional)
+ privacy_mode_enabled: true (boolean, optional)

## Create Vehicle Note Request (object)
+ note: Test vehicle note (string, required)

## Update Vehicle Mode Garage Request (object)
+ garage_mode_begin: 1509623876 (number)
+ garage_mode_end: 1509623876 (number)

## Update Vehicle Mode Transport Request (object)
+ transport_mode_begin: 1509623876 (number)
+ transport_mode_end: 1509623876 (number)

## Update Alert Mode Request (object)
+ start: 1509623876 (number)
+ end: 1509623876 (number)

## Update Vehicle Mode Clear Garage Request (object)
+ clear_garage_mode: true (boolean

## Update Vehicle Mode Clear Transport Request (object)
+ clear_transport_mode: true (boolean)

## Update Vehicle Gsense Request (object)
+ g_sense: true (boolean, required)
+ g_sense_number: +441234567890 (string, required)

## Update Customer Request (object)
+ show_map_speed: true (boolean, required)

## Alert Request (object)
+ name: Geofence test (string, required)
+ type: GF Entry (string, required)
+ level: Alarm (string, required)
+ timezone: Europe/London (string, required)
+ sunday_start: 00:00:00 (string, required)
+ sunday_end: 00:00:00 (string, required)
+ monday_start: 00:00:00 (string, required)
+ monday_end: 00:00:00 (string, required)
+ tuesday_start: 00:00:00 (string, required)
+ tuesday_end: 00:00:00 (string, required)
+ wednesday_start: 00:00:00 (string, required)
+ wednesday_end: 00:00:00 (string, required)
+ thursday_start: 00:00:00 (string, required)
+ thursday_end: 00:00:00 (string, required)
+ friday_start: 00:00:00 (string, required)
+ friday_end: 00:00:00 (string, required)
+ saturday_start: 00:00:00 (string, required)
+ saturday_end: 00:00:00 (string, required)
+ vehicle_id: 1 (number)
+ group_id: 1 (number)
+ description: description (string)
+ email: hello@example.com (string)
+ txt: text example (string)
+ aux_id: 1 (number)
+ speed_limit: 50 (number)
+ idle_limit: 50 (number)
+ engine_limit: 50 (number)
+ geofence_id: 1 (number)

## Alert Put Request (object)
+ name: Geofence test (string, required)
+ type: GF Entry (string, required)
+ level: Alarm (string, required)
+ timezone: Europe/London (string, required)
+ sunday_start: 00:00:00 (string, required)
+ sunday_end: 00:00:00 (string, required)
+ monday_start: 00:00:00 (string, required)
+ monday_end: 00:00:00 (string, required)
+ tuesday_start: 00:00:00 (string, required)
+ tuesday_end: 00:00:00 (string, required)
+ wednesday_start: 00:00:00 (string, required)
+ wednesday_end: 00:00:00 (string, required)
+ thursday_start: 00:00:00 (string, required)
+ thursday_end: 00:00:00 (string, required)
+ friday_start: 00:00:00 (string, required)
+ friday_end: 00:00:00 (string, required)
+ saturday_start: 00:00:00 (string, required)
+ saturday_end: 00:00:00 (string, required)
+ vehicle_id: 1 (number)
+ group_id: 1 (number)
+ description: description (string)
+ email: hello@example.com (string)
+ txt: text example (string)
+ aux_id: 1 (number)
+ speed_limit: 50 (number)
+ idle_limit: 50 (number)
+ engine_limit: 50 (number)
+ geofence_id: 1 (number)

## Mark Read Alert Event Request (object)
+ alert_event_ids: (array[number], required)

## User Update Request (object)
+ first_name: Mark (string)
+ last_name: Downing (string)
+ email: test@email.com (string)
+ timezone: Europe/London (string)
+ mobile_phone: 01234 567890 (string)
+ distance_units: (enum[string])
  - kilometers
  - miles
+ volume_units: (enum[string])
  - gallons
  - litres
+ security_question: Name of dog (string)
+ security_answer: Rex (string)

## Create Fixed Geofence Request (object)
+ type: fixed (string, required)
+ name: fixed geofence (string, required)
+ position: (Position, required)
+ radius: 500 (number, required) - Radius in metres

## Create Polygon Geofence Request (object)
+ type: polygon (string, required)
+ name: polygon geofence (string, required)
+ positions: (array[Position], required) - Min length: 2

## Update Fixed Geofence Request (object)
+ type: fixed (string)
+ name: fixed geofence (string)
+ position: (Position)
+ radius: 500 (number) - Radius in metres

## Update Polygon Geofence Request (object)
+ type: polygon (string)
+ name: polygon geofence (string)
+ positions: (array[Position]) - Min length: 2

## Position (object)
+ lat: 52.04124 (number)
+ lng: 0.251235 (number)


## Admin Create Customer
+ company: Scorpion Automotive (string, required) - Company/Customer name 
+ address: Scorpion Automotive (string, required) - Line 1 of the address 
+ postcode: M98H+98 (string, required) - Postal code
+ primary_phone: +441234567891 (string, required) - Primary telephone number 
+ email: test@example.com (string, required) - Main email
+ address2: Chorley (string, optional) - Line 2 of address
+ address3: Preston (string, optional) - Line 3 of address
+ county: Lancashire (string, optional) - County of the company/customer
+ country: United Kingdom (string, optional) - Country of the company/customer
+ fax: +441234567890 (string, optional) - Fax number
+ description: Scorpion (string, optional) - Description of the customer

## Admin Update Customer
+ company: Scorpion Automotive (string, optional) - Company/customer name 
+ address: Scorpion Automotive (string, optional) - Line 1 of the address 
+ postcode: M98H+98 (string, optional) - Postal code
+ primary_phone: +441234567891 (string, optional) - Primary telephone number 
+ email: test@example.com (string, optional) - Main email
+ address2: Chorley (string, optional) - Line 2 of address
+ address3: Preston (string, optional) - Line 3 of address
+ county: Lancashire (string, optional) - County of the company/customer
+ country: United Kingdom (string, optional) - Country of the company/customer
+ fax: +441234567890 (string, optional) - Fax number
+ description: Scorpion (string, optional) - Description of the customer

## Admin Activate Vehicle
+ length: 36 (number, optional) - Length of subscription to be created in months
+ monitored: true (boolean, optional) - If not specified will default to false

## Admin Create Vehicle
+ customerId: 1000 (number, required) - Customer ID to add the vehicle to
+ unitId: 1 (number, required) - The unit ID to link to the vehicle
+ registration: ABC123 (string, required) - Registration of the vehicle
+ make: Ford (string, required) - The make of the vehicle
+ model: Focus (string, required) -  The type model of for the make of vehicle 
+ color: Red (string, required) - Colour of the vehicle
+ type: S7 (string, required) - Category of the tracker types are: S7, S5, S5+
+ vin: AV12333221232 (string, required) - Vehicle identification number for the car
+ description: A test vehicle (string, optional) - The long description for the vehicle
+ dealershipId: 1000 (number, optional) - The ID of the dealership that the tracker is from
+ fitterId: 1001 (number, optional) - The ID of the fitter who fit the tracker
+ alias: Demo Focus (string, optional) - The display name that is shown for the vehicle

## Admin Update Vehicle
+ customerId: 1000 (number, optional) - Customer ID to add the vehicle to
+ registration: ABC123 (string, optional) - Registration of the vehicle
+ make: Ford (string, optional) - The make of the vehicle
+ model: Focus (string, optional) -  The type model of for the make of vehicle 
+ color: Red (string, optional) - Colour of the vehicle
+ type: S7 (string, optional) - Category of the tracker types are: S7, S5, S5+
+ vin: AV12333221232 (string, optional) - Vehicle identification number for the car

## Admin Find Customers
+ company: Scorpion Automotive (string, optional) - Company/customer name 
+ address: Scorpion Automotive (string, optional) - Line 1 of the address 
+ postcode: M98H+98 (string, optional) - Postal code
+ primary_phone: +441234567891 (string, optional) - Primary telephone number 
+ email: test@example.com (string, required) - Main email
