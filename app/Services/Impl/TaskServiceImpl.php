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

    public function notices($userId)
    {
        $notices = $this->repository->notices($userId);
        $results = [];
        foreach ($notices as $notice) {
            $remainTime = (strtotime($notice['start_date']) - strtotime(date('Y-m-d H:i:00'))) / 60;
            if ($remainTime % 10 == 0) {
                $notice['remain_time'] = $remainTime;
                $results[] = $notice;
            }
        }
        return $results;
    }

    public function overtime($criteria)
    {
        return $this->repository->overtime($criteria);;
    }

    public function done($taskId)
    {
        return $this->repository->done($taskId);
    }
}
