<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\PermissionCollection
     */
    public function index()
    {
        $permissions = Permission::query()->paginate();

        return new PermissionCollection($permissions);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\PermissionResource
     */
    public function store(CreatePermissionRequest $request)
    {
        $validatedData = $request->validated();
        $permission = Permission::create($validatedData);

        return new PermissionResource($permission);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \App\Http\Resources\PermissionResource
     */
    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \App\Http\Resources\PermissionResource
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $validatedData = $request->validated();

        $permission->update($validatedData);

        return new PermissionResource($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Spatie\Permission\Models\Role  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
