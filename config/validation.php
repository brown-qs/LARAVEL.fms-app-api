<?php

/**
 * This file is part of the Scorpion API
 * (c) Hare Digital
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c) Hare Digital
 * @license     LICENSE
 * @link        README.MD Documentation
 */

use App\Models\Alert;
use Illuminate\Validation\Rule;
use Jekk0\laravel\Iso3166\Validation\Rules\Iso3166Alpha2;

/*
 * Example of how to lay out the validation arrays,
 * controller.action.parameter = validation
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
return [
    /*
     * Example:
     * 'controller' => [
     *     'action' => [
     *         'key' => 'required|numeric',
     *     ],
     * ]
     */
    'auth'       => [
        'login' => [
            'email'        => 'required|email',
            'password'     => 'required',
            'fcm_token'    => 'string',
            'fcm_platform' => 'string',
        ],
    ],
    'alert'      => [
        'create' => [
            'name'            => "required|string|min:3",
            'description'     => "string",
            'type'            => [
                'required',
                Rule::in(Alert::VALID_ALERT_TYPES),
            ],
            'level'           => [
                'required',
                Rule::in(Alert::VALID_ALERT_LEVELS),
            ],
            'email'           => "email",
            'txt'             => "alpha_num",
            'aux_id'          => "digits:1|required_if:type,Aux High,Aux Low",
            'speed_limit'     => "numeric|required_if:type,Speed",
            'idle_limit'      => "numeric|required_if:type,Idle",
            'engine_limit'    => "numeric|required_if:type,Engine",
            'geofence_id'     => "required_if:type,GF Entry,GF Exit,GF Speed,GF Plot|exists:Geofence,geofenceId",
            'sunday_start'    => "required|date_format:H:i:s",
            'sunday_end'      => "required|date_format:H:i:s",
            'monday_start'    => "required|date_format:H:i:s",
            'monday_end'      => "required|date_format:H:i:s",
            'tuesday_start'   => "required|date_format:H:i:s",
            'tuesday_end'     => "required|date_format:H:i:s",
            'wednesday_start' => "required|date_format:H:i:s",
            'wednesday_end'   => "required|date_format:H:i:s",
            'thursday_start'  => "required|date_format:H:i:s",
            'thursday_end'    => "required|date_format:H:i:s",
            'friday_start'    => "required|date_format:H:i:s",
            'friday_end'      => "required|date_format:H:i:s",
            'saturday_start'  => "required|date_format:H:i:s",
            'saturday_end'    => "required|date_format:H:i:s",
            'timezone'        => "required|timezone",
        ],
        'edit'   => [
            'name'            => "string|min:3",
            'description'     => "string",
            'type'            => [
                Rule::in(Alert::VALID_ALERT_TYPES),
            ],
            'level'           => [
                Rule::in(Alert::VALID_ALERT_LEVELS),
            ],
            'email'           => "email",
            'txt'             => "alpha_num",
            'aux_id'          => "digits:1|required_if:type,Aux High,Aux Low",
            'speed_limit'     => "numeric|required_if:type,Speed",
            'idle_limit'      => "numeric|required_if:type,Idle",
            'engine_limit'    => "numeric|required_if:type,Engine",
            'geofence_id'     => "exists:Geofence,geofenceId|required_if:type,GF Entry,GF Exit,GF Speed,GF Plot",
            'sunday_start'    => "date_format:H:i:s",
            'sunday_end'      => "date_format:H:i:s",
            'monday_start'    => "date_format:H:i:s",
            'monday_end'      => "date_format:H:i:s",
            'tuesday_start'   => "date_format:H:i:s",
            'tuesday_end'     => "date_format:H:i:s",
            'wednesday_start' => "date_format:H:i:s",
            'wednesday_end'   => "date_format:H:i:s",
            'thursday_start'  => "date_format:H:i:s",
            'thursday_end'    => "date_format:H:i:s",
            'friday_start'    => "date_format:H:i:s",
            'friday_end'      => "date_format:H:i:s",
            'saturday_start'  => "date_format:H:i:s",
            'saturday_end'    => "date_format:H:i:s",
            'timezone'        => "timezone",
        ],
    ],
    //TODO: Test if validation rework on 09-11-2020 breaks this route
    'alertEvent' => [
        'markAsRead' => [
            'alert_event_ids'   => "required|array|min:1",
            'alert_event_ids.*' => "exists:AlertEvent,alertEventId",
        ],
    ],
    'customer'   => [
        'update' => [
            'show_map_speed' => 'required|boolean',
        ],
    ],
    'user'       => [
        'update' => [
            'first_name'        => "string",
            'last_name'         => "string",
            'email'             => "email",
            'timezone'          => "timezone",
            'mobile_phone'      => "alpha_num",
            'distance_units'    => Rule::in(['miles', 'kilometers']),
            'volume_units'      => Rule::in(['gallons', 'litres']),
            'security_question' => "string",
            'security_answer'   => "required_with:security_question",
        ],
    ],
    'vehicle'    => [
        'update'     => [
            'odometer'                => 'numeric',
            'privacy_mode_enabled'    => 'boolean',
            'zero_speed_mode_enabled' => 'boolean',
        ]
    ],
    'geofence'   => [
        'create' => [
            'name'         => 'required',
            'type'         => ['required', Rule::in(['fixed', 'polygon'])],
            'radius'       => 'numeric',
            'position'     => 'array',
            'position.lat' => 'numeric',
            'position.lng' => 'numeric',
            'positions'    => 'array',
        ],
    ],
    'installer' => [
        'getCustomers' => [
            'email' => 'string|min:3',
        ],
        'getUnit' => [
            'unitId' => 'required'
        ],
        'createCustomer' => [
            'name' => 'required|string|min:3', //
            'email' => 'required|email|min:3', //
            'brand' => 'required|string|min:3', //
            'phone' => 'required|string', //
            'fax' => 'alpha_num', // (optional)
            'address' => 'required|string', //
            'address_line_2' => 'string', // (optional)
            'address_line_3' => 'string', // (optional)
            'county' => 'string', //
            'postcode' => 'string', //
            'country' => 'required|string|min:2|max:2', // (ISO code)
            'notification_user_created' => 'boolean', // (bool)
            'notification_driver_created' => 'boolean', // (bool)
            'fleet_manager_first_name' => 'string', //
            'fleet_manager_last_name' => 'string', //
            'fleet_manager_phone' => 'alpha_num', //
            'fleet_manager_email' => 'email', //
            'fleet_manager_address' => 'string', // (optional)
            'fleet_manager_address_line_2' => 'string', // (optional)
            'fleet_manager_address_line' => 'string', // 3 (optional)
            'fleet_manager_county' => 'string', // (optional)
            'fleet_manager_postcode' => 'string', // (optional)
            'fleet_manager_country' => 'string|min:2|max:2', // (ISO code)
        ],
        'createCustomerUser' => [
            'customer_id' => 'required|numeric',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'password' => 'string|min:6',
        ],
        'createVehicle' => [
            'customer_id' => 'required|numeric',
            'registration' => 'required|string|max:100',
            'alias' => 'string|max:100',
            'vin' => 'required|string|max:100',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'colour' => 'required|string|max:100',
            'type' => 'required|string|max:100|in:Car,Bike,Van,Motorhome,HGV,Other',
            'description' => 'string|max:254',
            'avg_co2' => 'numeric',
            'avg_mpg' => 'numeric',
            'fuel_type' => 'required|string|in:Petrol,Diesel',
            'unit_id' => 'required|numeric',
            'fitter_id' => 'required|numeric',
            'driver_module' => 'numeric',
            'driver_options' => 'numeric',
            'side_location' => 'string|max:100',
            'top_location' => 'string|max:100',
            'mounting_location' => 'string|max:100',
            'aux_0_type' => 'string|in:Input,Output,Disabled',
            'aux_0_name' => 'string',
            'aux_0_status_text_high' => 'string',
            'aux_0_status_text_low' => 'string',
            'aux_0_config_1' => 'string|in:No Trigger,Rising Edge,Falling Edge,Rising/Falling Edge,Off Permanently,On Permanently',
            'aux_0_config_2' => 'string|in:Pull Up,Pull Down,None,Active Low Output,Active High Output',
            'aux_0_port_data' => 'string|in:On,Off',
            'aux_1_type' => 'string|in:Input,Output,Disabled',
            'aux_1_name' => 'string',
            'aux_1_status_text_high' => 'string',
            'aux_1_status_text_low' => 'string',
            'aux_1_config_1' => 'string|in:No Trigger,Rising Edge,Falling Edge,Rising/Falling Edge,Off Permanently,On Permanently',
            'aux_1_config_2' => 'string|in:Pull Up,Pull Down,None,Active Low Output,Active High Output',
            'aux_1_port_data' => 'string|in:On,Off',
        ]
    ],
];
