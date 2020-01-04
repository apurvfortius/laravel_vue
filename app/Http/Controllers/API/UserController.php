<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Validate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth('api')->user()->hasRole('superadmin')){
            return User::latest()->paginate(10);
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
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|max:10|unique:users',
            'type' => 'required',
            'password' => 'required|min:4',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->type,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->type);

        return $user;
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

    public function findUser(){
        if( $search = \Request::get('key') ){
            $user = User::where(function($query) use ($search){
                $query->where('name', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%");
            })->paginate(10);
        }
        else{
            $user = User::latest()->paginate(10);
        }
        return $user;
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function updateprofile(Request $request){
        $user = auth('api')->user();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|max:10|unique:users,phone,'.$user->id,
            'password' => 'sometimes|min:4',
        ]);

        $currentPhoto = $user->photo;
        if($request->photo !== $currentPhoto){
            
            $name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            
            $request->merge([ 'photo' => "/img/profile/".$name ]);
            
            $userPhoto = public_path().$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            $requestp>merge([ 'password' => Hash::make($request->password) ]);
        }

        $user->update($request->all());
        return ['message' => 'Update'];
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
        $user = User::findOrfail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|max:10|unique:users,phone,'.$user->id,
            'password' => 'sometimes|min:4',
        ]);

        $user->update($request->all());
        return ['message' => 'User updated Successfully'];
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
            $user = User::findOrfail($id);
            $user->delete();
            return ['message' => 'User Deleted Successfully'];
        }
    }
    
    public function getRoles()
    {
        return Role::all();
    }
}
