<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
