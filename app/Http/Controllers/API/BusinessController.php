<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Business;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth('api')->user()->hasRole('superadmin')){
            $result = Business::paginate(10);
            return $result;
        }
    }

    public function findBusiness(){
        if( $search = \Request::get('key') ){
            $user = Business::where(function($query) use ($search){
                $query->where('business_name', 'LIKE', "%$search%");
            })->paginate(10);
        }
        else{
            $user = Business::paginate(10);
        }
        return $user;
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
            'business_name' => 'required|string|max:255',
            'business_description' => 'required|string|max:255',
        ]);

        $result = Business::create([
            'business_name' => $request->business_name, 
            'business_description' => $request->business_description
        ]);
        
        return $result;
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
        $business = Business::findOrfail($id);

        $this->validate($request, [
            'business_name' => 'required|string|max:255',
            'business_description' => 'required|string|max:255',
        ]);

        $business->update($request->all());
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
            $business = Business::findOrfail($id);
            $business->delete();
            return ['message' => 'Business Deleted Successfully'];
        }
    }
}
