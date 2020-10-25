<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return User
     */
    public function store(CreateUserRequest $request)
    {
        return User::createWithAttributes($request->validated())->save();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function show(int $id, UserRepository $repository)
    {
        $user = $repository->find($id);
        $this->authorize('view', $user);

        return $user;
    }
}
