<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    /**
     * UserController constructor.
     *
     * @param  TaskService  $service
     *
     * @param  Request  $request
     */
    public function __construct(TaskService $service, Request $request)
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
        return TaskRequest::class;
    }
}
