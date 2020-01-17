<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product_type;
use App\Model\Business;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth('api')->user()->hasRole('superadmin')){
            $result = Product_type::select('product_types.*', 'businesses.business_name')
                                ->join('businesses', 'businesses.id', 'product_types.business_id')
                                ->paginate(10);
            return $result;
        }
    }
    
    public function getBusiness()
    {
        $result = Business::all();
        return $result;
    }

    public function findProductType(){
        if( $search = \Request::get('key') ){
            $user = Product_type::select('product_types.*', 'businesses.business_name')
                    ->join('businesses', 'businesses.id', 'product_types.business_id')
                    ->where(function($query) use ($search){
                        $query->where('product_type', 'LIKE', "%$search%");
                    })->paginate(10);
        }
        else{
            $user = Product_type::select('product_types.*', 'businesses.business_name')
                    ->join('businesses', 'businesses.id', 'product_types.business_id')
                    ->paginate(10);
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
            'business_id' => 'required',
            'product_type' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $result = Product_type::create([
            'business_id' => $request->business_id, 
            'product_type' => $request->product_type,
            'description' => $request->description,
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
        $type = Product_type::findOrfail($id);

        $this->validate($request, [
            'business_id' => 'required',
            'product_type' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $type->update($request->all());
        return ['message' => 'Product Type updated Successfully'];
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
            $type = Product_type::findOrfail($id);
            $type->delete();
            return ['message' => 'Product Type Deleted Successfully'];
        }
    }
}
