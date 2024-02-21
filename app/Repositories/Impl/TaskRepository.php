<?php

namespace App\Repositories\Impl;

use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;

/**
 * Class UserRepository
 *
 * @package App\Repositories\Impl
 *
 * @property User $model
 */
class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }
}
