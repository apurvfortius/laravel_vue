<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth('api')->user()->hasRole('superadmin')){
            $roles = Role::paginate(10);
            foreach($roles as $key => $value){
                $roles[$key]['permission'] = $value->getAllPermissions()->pluck('name');
                $roles[$key]['count'] = count($value->getAllPermissions());
            }
            return $roles;
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

        $id = Role::create([
            'name' => $request->name,
        ]);

        foreach($request->permission as $permisin){
            $id->givePermissionTo($permisin);
        }        
        return $id;
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

    public function findRole(){
        if( $search = \Request::get('key') ){
            $user = Role::where(function($query) use ($search){
                $query->where('name', 'LIKE', "%$search%");
            })->paginate(10);
        }
        else{
            $user = Role::paginate(10);
        }
        return $user;
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
        $role = Role::findOrfail($id);
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        foreach($role->getAllPermissions() as $permission){
            $role->revokePermissionTo($permission);
        } 

        $role->update(['name' => $request->name]);

        foreach($request->permission as $permisin){
            $role->givePermissionTo($permisin);
        } 
        return ['message' => 'Role updated Successfully'];
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
            $user = Role::findOrfail($id);
            $user->delete();
            return ['message' => 'Role Deleted Successfully'];
        }
        else{
            return ['message' => 'You are not Authorized'];
        }
    }
}
