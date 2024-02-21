<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Parameters\Criteria;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

abstract class BaseController extends Controller
{
    /** @var BaseService $service */
    protected BaseService $service;

    /** @var Request $request */
    protected Request $request;

    /**
     * BaseController constructor.
     *
     * @param  BaseService  $service
     * @param  Request  $request
     */
    public function __construct(BaseService $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
    }

    /**
     * Get FormRequest validation (BaseRequest::class)
     *
     * @return string
     */
    abstract public function getRules(): string;

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
                $this->validated()
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
                $this->validated()
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

    /**
     * Get validated data
     *
     * @return array
     */
    public function validated(): array
    {
        return app($this->getRules())->validationData();
    }
}
