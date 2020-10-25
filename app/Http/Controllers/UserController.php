<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\PendingUser;
use App\Aggregates\WalletAggregateRoot;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Str;
use App\Repositories\Contracts\UserRepository;

class UserController extends Controller
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $pendingUser = PendingUser::createWithAttributes($request->validated());
        $user = $this->repository->create($pendingUser->getAttributes());

        $newUuid = Str::uuid()->toString();
        WalletAggregateRoot::retrieve($newUuid)
            ->createWalletFor($user)
            ->persist();

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function show(int $id)
    {
        $user = $this->repository->find($id);
        $this->authorize('view', $user);

        return $user;
    }
}
