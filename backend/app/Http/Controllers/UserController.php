<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordSetupRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserIndexRequest $request)
    {

        $query = User::with('roles');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('name_kana', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('userCode')) {
            $userCode = $request->userCode;

            $query->where('user_code', $userCode);
        }

        if ($request->filled('email')) {
            $email = $request->email;

            $query->where('email', $email);
        }

        if ($request->boolean('activeOnly')) {
            $activeOnly = 'active';

            $query->where('status', $activeOnly);
        }

        if ($request->filled('roleId')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->roleId);
            });
        }

        return UserResource::collection($query->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->service->create($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UserUpdateRequest $request)
    {
        $user->update($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(null, 204);
    }

    public function setupPassword(PasswordSetupRequest $request)
    {
        $this->service->setupPassword($request->validated());

        return response(null, 204);
    }
}
