<?php

namespace App\Repositories\Impl;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepositoryInterface;
use App\Http\Parameters\Criteria;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;


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

    /**
     * Get list model items with pagination
     *
     * @param  Criteria  $criteria
     *
     */
    public function list(Criteria $criteria)
    {
        $query = $this->newQuery()->scopes($this->loadScopes($criteria->getFilters()));

        if (!empty($criteria->getSelect())) {
            $query->select($criteria->getSelect());
        }

        $tasks = $this->applyOrderBy($query, $criteria->getSorts())
            ->with($this->getRelations($criteria))
            ->withCount($this->getCountRelations($criteria))
            ->get();
        $results = [];
        foreach ($tasks as $task) {
            $startDate = date('Y-m-d', strtotime($task->start_date));
            if (!empty($results[$startDate])) {
                $results[$startDate][] = $task;
            } else {
                $results[$startDate] = [$task];
            }
        }
        return collect($results);
    }

    /**
     * Create new model item
     *
     * @param  array  $data
     *
     * @return Model
     *
     * @throws Exception
     */
    public function create(array $data): Model
    {
        $results = [];
        
        foreach ($data['email'] as $key => $email) {
            $user = User::where('email', $email)->first();
            $results[$key]['owner_id'] = $data['owner_id'];
            $results[$key]['assignee_id'] = $user->id;
            $results[$key]['title'] = $data['title'];
            $results[$key]['description'] = $data['description'];
            $results[$key]['start_date'] = $data['start_date'];
            $results[$key]['end_date'] = $data['end_date'];
            $results[$key]['status'] = $data['status'];
        }
        Task::insert($results);

        $instance = Task::where('assignee_id', $data['owner_id'])->where('start_date', $data['start_date'])->first();
        $instance->load(static::$relations);
        
        return $instance;
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

    public function done($taskId)
    {
        $task = $this->newQuery()->where('id', $taskId)->first();
        $task->status = Task::COMPLETE_STATUS;
        $task->save();
        return $task;
    }
}
