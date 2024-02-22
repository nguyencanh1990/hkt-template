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

    public function notices($userId)
    {
        $date = date("Y-m-d H:i:00", strtotime('+ 10 minute'));
        return $this->newQuery()
            ->where('start_date', '<=', $date)
            ->where('assignee_id', $userId)
            ->where('status', Task::INCOMPLETE_STATUS)
            ->get();
    }

    public function overtime($userId)
    {
        return $this->newQuery()
            ->where('start_date', '<=', date("Y-m-d H:i:00"))
            ->where('assignee_id', $userId)
            ->where('status', Task::INCOMPLETE_STATUS)
            ->get();
    }
}
