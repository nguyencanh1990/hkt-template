<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Enum\UserStatusEnum;
use Exception;
use Helper\Common;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class UserRepository
 *
 * @package App\Repositories\Impl
 *
 * @property User $model
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
