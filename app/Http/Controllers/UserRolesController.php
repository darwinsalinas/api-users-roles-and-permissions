<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRolesRequest;
use App\Http\Resources\RoleCollection;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRolesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\RoleCollection
     */
    public function store(User $user, AssignRolesRequest $request)
    {
        $validatedData = $request->validated();

        $roles = Role::whereIn('name', $validatedData['roles'])->get();

        $user->assignRole($roles);

        return new RoleCollection($user->roles);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Http\Resources\RoleCollection
     */
    public function destroy(User $user, AssignRolesRequest $request)
    {
        $validatedData = $request->validated();

        $roles = Role::whereIn('name', $validatedData['roles'])->get();

        foreach ($roles as $role) {
            $user->removeRole($role);
        }

        return new RoleCollection($user->roles);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Http\Resources\RoleCollection
     */
    public function update(User $user, AssignRolesRequest $request)
    {
        $validatedData = $request->validated();

        $roles = Role::whereIn('name', $validatedData['roles'])->get();

        $user->syncRoles($roles);

        return new RoleCollection($user->roles);
    }
}
