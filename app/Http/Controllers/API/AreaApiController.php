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
        $parent = ( int ) ( isset( $_GET['id'] ) && $_GET['id'] != null ) ? $_GET['id'] : 0;
        return Area::withCount(['zoneCustomers', 'areaCustomers', 'childs'])->where('parent', $parent)->orderByDesc('name')->paginate(15);
    }

    public function childs( $parent )
    {
        $childs = Area::select('id', 'name')->where('parent', $parent)->orderBy('name', 'ASC')->get();

        return response()->json(['success' => true, 'childs' => $childs ], $this->success);
    }

    public function customers( Request $request, $id )
    {
        $areaId = ( int ) ( $request->get('area') ) ? $request->get('area') : $id;

        if( !$areaId )
            return response()->json(['success' => false], $this->success);

        //get area info
        $area = \App\Area::find( $areaId );

        //fetch all customers withing this area
        $items = \App\Customer::with(['dues', 'package'])->where('area_id', $areaId)->orWhere('zone_id', $areaId )->get();

        $arr = array();
        if( $items->count() > 0 ) {
            foreach( $items as $item ) {
                $row['id'] = $item->id;
                $row['name'] = $item->name;
                $row['package'] = $item->package['name'] . ' (' . $item->package['price'] . ')';
                $row['dues'] = $this->_dues( $item->due );

                array_push( $arr, $row );
            }
        }

        return response()->json(['success' => true, 'info' => $area, 'customers' => $arr], $this->success);
    }

    protected function _dues( $arr )
    {
        return 0;
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
        $parents = Area::select('name', 'id')->where('parent', 0)->get();

        //return json response
        return response()->json(['success' => true, 'data' => $item, 'parents' => $parents], $this->success );
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
        $area->parent = $request->parent;

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