<?php

namespace App\Actions;

use App\Aggregates\WalletAggregateRoot;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use App\Support\PendingUser;
use Illuminate\Support\Str;

class CreateUserAction
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(PendingUser $pendingUser): User
    {
        $user = $this->repository->create($pendingUser->getAttributes());
        $this->openAccountFor($user);

        return $user;
    }

    private function openAccountFor(User $user): void
    {
        $newUuid = Str::uuid()->toString();
        WalletAggregateRoot::retrieve($newUuid)
            ->createWalletFor($user)
            ->persist();
    }
}
