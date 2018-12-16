<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Package;

class ApiPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */protected $error;
    protected $success;

    public function __construct()
    {
        $this->success = 200;
        $this->error = 403;
    }
    public function index()
    {
        return Package::withCount(['customers'])->orderByDesc('name')->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //form validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'price' => 'required|numeric',
            'type' => 'required|string'
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success );

        //Saving data
        $package = new Package();
        $package->name = $request->name;
        $package->type = ( $request->type ) ? $request->type : 'home';
        $package->bandwidth = ( $request->bandwidth ) ? $request->bandwidth : 0;
        $package->speed = $request->speed;
        $package->price = $request->price;

        $package->save();

        //return success of fail message
        if( $package->id ) :
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Sorry! cannot create.'], $this->success);
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $items = Package::findOrFail( $id );

        //return json response
        return response()->json(['success' => true, 'data' => $items], $this->success );
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
        //form validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'price' => 'required|numeric',
            'type' => 'required|string'
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success);

        //Saving data
        $package = Package::findOrFail( $id );
        $package->name = $request->name;
        $package->bandwidth = $request->bandwidth;
        $package->speed = $request->speed;
        $package->price = $request->price;
        $package->type = ( $request->type ) ? $request->type : $package->type;

        $package->save();

        //return success of fail message
        if( $package->id ) :
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Sorry! cannot update.'], $this->success);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Package::findOrFail( $id );

        if( $item ) :
            $ok = $item->delete();
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Item not found.'], $this->success);
        endif;
    }
}
