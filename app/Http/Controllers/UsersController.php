<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\UserCollection
     */
    public function index()
    {
        $users = User::query()
            ->with('roles.permissions', 'permissions')
            ->paginate();

        return new UserCollection($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        if (isset($validatedData['roles'])) {
            $roles = Role::whereIn('name', $validatedData['roles'])->get();
            $user->assignRole($roles);
        }

        if (isset($validatedData['permissions'])) {
            $permissions = Permission::whereIn('name', $validatedData['permissions'])->get();
            $user->givePermissionTo($permissions);
        }

        return response()->json(
            new UserResource($user),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        $user->load('roles.permissions', 'permissions');

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $validatedData = $request->validated();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        if (isset($validatedData['roles'])) {
            $roles = Role::whereIn('name', $validatedData['roles'])->get();
            $user->syncRoles($roles);
        }

        if (isset($validatedData['permissions'])) {
            $permissions = Permission::whereIn('name', $validatedData['permissions'])->get();
            $user->syncPermissions($permissions);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->roles()->delete();
        $user->permissions()->delete();
        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
