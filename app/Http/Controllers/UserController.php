<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\PendingUser;
use App\Aggregates\WalletAggregateRoot;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $pendingUser = PendingUser::createWithAttributes($request->validated());


        $user = User::create($validated);

        $newUuid = Str::uuid()->toString();
        WalletAggregateRoot::retrieve($newUuid)
            ->persist();

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $user;
    }
}
