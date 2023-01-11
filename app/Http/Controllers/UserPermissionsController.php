<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignPermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermissionsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\PermissionCollection
     */
    public function store(User $user, AssignPermissionRequest $request)
    {
        $validatedData = $request->validated();

        $permissions = Permission::whereIn('name', $validatedData['permissions'])->get();

        $user->givePermissionTo($permissions);

        return new PermissionCollection($user->permissions);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Http\Requests\AssignPermissionRequest  $request
     *
     * @return \App\Http\Resources\PermissionCollection
     */
    public function destroy(User $user, AssignPermissionRequest $request)
    {
        $validatedData = $request->validated();

        $permissions = Permission::whereIn('name', $validatedData['permissions'])->get();

        foreach ($permissions as $permission) {
            $user->revokePermissionTo($permission);
        }

        return new PermissionCollection($user->permissions);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Http\Requests\AssignPermissionRequest  $request
     *
     * @return \App\Http\Resources\PermissionCollection
     */
    public function update(User $user, AssignPermissionRequest $request)
    {
        $validatedData = $request->validated();

        $permissions = Permission::whereIn('name', $validatedData['permissions'])->get();

        $user->syncPermissions($permissions);

        return new PermissionCollection($user->permissions);
    }
}
