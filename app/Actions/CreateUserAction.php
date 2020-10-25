<?php

namespace App\Actions;

use App\Models\User;
use App\Support\PendingUser;
use App\Repositories\Contracts\UserRepository;

class CreateUserAction
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(PendingUser $pendingUser): User
    {
        return $this->repository->create($pendingUser->getAttributes());
    }
}
