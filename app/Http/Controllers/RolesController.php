<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\RoleCollection
     */
    public function index()
    {
        $roles = Role::query()
            ->with('permissions')
            ->paginate();

        return new RoleCollection($roles);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\RoleResource
     */
    public function store(CreateRoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = Role::create($validatedData);

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\RoleResource
     */
    public function show(Role $role)
    {
        $role->load('permissions');

        return new RoleResource($role);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \App\Http\Resources\RoleResource
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();

        $role->update($validatedData);

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
