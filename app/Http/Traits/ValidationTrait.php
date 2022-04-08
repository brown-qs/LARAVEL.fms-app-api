<?php

/**
 * This file is part of the Scorpion API
 * (c) Hare Digital
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c) Hare Digital
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Http\Traits;

use App\Support\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * The trait to make generic API responses
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
trait ValidationTrait
{
    /**
     * The validation messages
     *
     * @var array
     */
    protected $validationMessages = [
        'required' => ':attribute is required',
        'numeric'  => ':attribute must be numeric',
        'boolean'  => ':attribute must be a boolean',
        'exists'   => ':attribute does not exist',
        'array'    => ':attribute must be an array',
        'email'    => 'Your supplied email is not valid',
        'token'    => 'Your token does not exist or has expired',
        'string'   => ':attribute must be a string',
        'in'       => 'The :attribute must be one of the following types: :values',
    ];

    /**
     * The validator
     *
     * @var \Illuminate\Validation\Validator;
     */
    protected $validator;

    /**
     * The status codes for validation errors
     *
     * @var array
     */
    private $statusCodeValidation = [
        'validation.token'  => Response::HTTP_FORBIDDEN,
        'validation.exists' => Response::HTTP_NOT_FOUND,
    ];

    /**
     * @param string $parent
     * @param string $child
     *
     * @return bool
     */
    public function validates(string $parent, string $child): bool
    {
        $rules = Config::get('validation');

        if (!isset($rules[$parent]) &&
            !isset($rules[$parent][$child]) &&
            !isset($rules[$parent]['*'])) {
            return true;
        }

        $mergedRules = isset($rules[$parent]['*']) ? $rules[$parent]['*'] : [];
        $mergedRules = array_merge($mergedRules, isset($rules[$parent][$child]) ?
            $rules[$parent][$child] : []);

        $request         = array_merge($this->request->all(), $this->request->attributes->all(), $this->request->route()[2]);


        $this->validator = Validator::make($request, $mergedRules, $this->validationMessages);

        return !$this->validator->fails();
    }

    /**
     * @return array
     */
    protected function getValidationBody()
    {
        $errors = json_decode(json_encode($this->validator->errors()), true);

        $errorTypes = [];
        $body       = [
            'status' => HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => [],
        ];

        foreach ($errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $fieldError) {
                $errorTypes[$fieldError][] = $field;
                $body['errors'][]          = $fieldError;
            }
        }

        foreach ($this->statusCodeValidation as $validatorKey => $validatorStatus) {
            if (count($errorTypes) == 1 && array_key_exists($validatorKey, $errorTypes)) {
                $body['status'] = $validatorStatus;
            }
        }

        return $body;
    }
}
