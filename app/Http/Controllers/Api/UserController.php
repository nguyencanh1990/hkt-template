<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * UserController constructor.
     *
     * @param  UserService  $service
     *
     * @param  Request  $request
     */
    public function __construct(UserService $service, Request $request)
    {
        parent::__construct($service, $request);
    }

    /**
     * Get FormRequest validation
     *
     * @return string
     */
    public function getRules(): string
    {
        return UserRequest::class;
    }
}
