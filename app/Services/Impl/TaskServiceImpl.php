<?php

namespace App\Services\Impl;

use App\Repositories\Impl\TaskRepository;
use App\Services\TaskService;

/**
 * Class FavoriteServiceImpl
 *
 * @package App\Services\Impl
 *
 * @property UserRepository $repository
 */
class TaskServiceImpl extends BaseServiceImpl implements TaskService
{
    /**
     * FavoriteServiceImpl constructor.
     * @param TaskRepository $repository
     */
    public function __construct(TaskRepository $repository)
    {
        parent::__construct($repository);
    }

}
