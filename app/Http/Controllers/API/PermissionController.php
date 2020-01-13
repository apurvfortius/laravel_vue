<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth('api')->user()->hasRole('superadmin')){
            $permisions = Permission::paginate(10);
            foreach($permisions as $key => $value){
                $permisions[$key]['role'] = $value->getRoleNames();
                $permisions[$key]['count'] = count($value->getRoleNames());
            }
            return $permisions;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        //$permission->assignRole($role);

        $permission = Permission::create(['name' => $request->name]);
        
        foreach($request->role as $role){
            $permission->assignRole($role);
        }
        return $permission;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrfail($id);
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        foreach($permission->getRoleNames() as $role){
            $permission->removeRole($role);
        }       

        $permission->update(['name' => $request->name]);

        foreach($request->role as $role){
            $permission->assignRole($role);
        }

        return ['message' => 'Permission updated Successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth('api')->user()->hasRole('superadmin')){
            $permission = Permission::findOrfail($id);
            $permission->delete();
            return ['message' => 'Permission Deleted Successfully'];
        }
        else{
            return ['message' => 'You are not Authorized'];
        }
    }
    
    public function getPermissions()
    {
        return Permission::all();
    }
}
