<?php

namespace App\Services\Impl;

use App\Repositories\Impl\UserRepository;
use App\Services\UserService;

/**
 * Class FavoriteServiceImpl
 *
 * @package App\Services\Impl
 *
 * @property UserRepository $repository
 */
class UserServiceImpl extends BaseServiceImpl implements UserService
{
    /**
     * FavoriteServiceImpl constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

}
