<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
 * (c) Hare Digital
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c) Hare Digital
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

/**
 * The base API Controller
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
abstract class AbstractApiController extends Controller
{
    use ApiResponseTrait;

    /**
     * The request object
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The ApiController constructor
     *
     * @param \Illuminate\Http\Request $request The request data
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * The ApiController isAdmin function
     *
     *
     */
    public function isAdmin()
    {
        $type = $this->request->get('user')->type;
        return $type === User::USER_TYPE_SUPER || $type === User::USER_TYPE_ADMIN;
    }


    /**
     * The ApiController isFitter function
     *
     *
     */
    public function isFitter()
    {
        $type = $this->request->get('user')->type;
        return $type === User::USER_TYPE_DEALER || $type === User::USER_TYPE_FITTER;
    }


    /**
     * The ApiController isBrandAdmin function
     *
     *
     */
    public function isBrandAdmin()
    {
        $type = $this->request->get('user')->type;
        return $type === User::USER_TYPE_BRAND_ADMIN;
    }


    /**
     * The ApiController getBrand function
     *
     *
     */
    public function getBrand()
    {
        return $this->request->get('user')->brandAdminFor;
    }

    /**
     * The ApiController getBrand function
     *
     *
     */
    public function getBrandAdmin()
    {
        $brandAdmin = $this->request->get('user')->brandAdminFor;
        if (empty($brandAdmin) || $brandAdmin == "" || is_null($brandAdmin) || !$brandAdmin) {
            return null;
        }
        return $brandAdmin;
    }
}
