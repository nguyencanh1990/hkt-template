<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Parameters\Criteria;
use App\Services\Impl\BaseServiceImpl;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class BaseController extends Controller
{

    protected Request $request;
    protected BaseServiceImpl $service;

    /**
     * BaseController constructor.
     *
     * @param  BaseService  $service
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $model = Str::studly(Str::singular($this->request->model));
        $this->service = new BaseServiceImpl($model);
    }

    /**
     * Get list model items
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(
            $this->service->list(Criteria::createFromRequest($this->request)),
            Response::HTTP_OK
        );
    }

    /**
     * Get detail of model
     *
     * @param string $id
     *
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function show($id): JsonResponse
    {
        return $this->success($this->service->find($id), Response::HTTP_OK);
    }

    /**
     * Create new model
     *
     * @return JsonResponse
     *
     * @throws ValidationException|Exception
     */
    public function store(): JsonResponse
    {
        return $this->success(
            $this->service->create(
                $this->request->all()
            ),
            Response::HTTP_OK
        );
    }

    /**
     * Update model item data by hash id
     *
     * @param string $hashId
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function update($id): JsonResponse
    {
        return $this->success(
            $this->service->update(
                $id,
                $this->request->all()
            ),
            Response::HTTP_OK
        );
    }

    /**
     * Destroy model by hash id
     *
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function destroy($id): JsonResponse
    {
        $this->service->delete($id);

        return $this->success(null, Response::HTTP_OK);
    }
}
