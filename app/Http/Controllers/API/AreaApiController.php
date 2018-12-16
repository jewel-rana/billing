<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Area;
class AreaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $error;
    protected $success;

    public function __construct()
    {
        $this->success = 200;
        $this->error = 403;
    }

    public function index()
    {
        return Area::withCount(['customers', 'childs'])->orderByDesc('name')->paginate(15);
    }

    public function childs( $parent )
    {
        $childs = Area::select('id', 'name')->where('parent', $parent)->orderByAsc('name')->get();

        return response()->json(['success' => true, 'childs' => $childs ], $this->success);
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
            'code' => 'required|unique:areas|max:191'
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success );

        //Saving data
        $area = new Area();
        $area->name = $request->name;
        $area->code = $request->code;
        $area->parent = ( $request->parent ) ? $request->parent : 0;

        $area->save();

        //return success of fail message
        if( $area->id ) :
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
        $item = Area::findOrFail( $id );

        //return json response
        return response()->json(['success' => true, 'data' => $item], $this->success );
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
            'code' => 'required|max:191',
            'parent' => 'integer'
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success);

        //Saving data
        $area = Area::findOrFail( $id );
        $area->name = $request->name;
        $area->code = $request->code;
        $area->parent = ( $request->parent ) ? $request->parent : $area->parent;

        $area->save();

        //return success of fail message
        if( $area->id ) :
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
        $item = Area::findOrFail( $id );

        if( $item ) :
            $ok = $item->delete();
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Item not found.'], $this->success);
        endif;
    }
}