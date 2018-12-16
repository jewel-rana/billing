<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use Validator;
use Hash;

class CustomerApiController extends Controller
{
    protected $success = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get customer type
        $type = ( isset( $_GET['type'] ) && $_GET['type'] != null ) ? $_GET['type'] : 'Active';
        switch ( $type ) {
            case 'Suspended':
                $status = 2;
                break;
            case 'Pending':
                $status = 0;
                break;
            default:
                $status = 1;
                break;
        }
        $query = Customer::with(['package', 'area']);
        if( $type != 'All')
            $query->where('status', $status);

        $query = $query->paginate(25);

        $customers = [];
        if( !empty( $query ) ) :
            foreach( $query as $customer ) :
                $row['id'] = $customer->id;
                $row['name'] = $customer->name;
                $row['type'] = ucfirst( $customer->type );
                $row['location'] = $customer->area['name'];
                $row['package'] = $customer->package['name'] . ' (' . $customer->package['price'] . ')';
                $row['due'] = 0;
                $row['status'] = $this->_customerStatus( $customer->status );
                array_push( $customers, $row );
            endforeach;
        endif;

        $packages = \App\Package::select('id', 'name', 'price')->get();

        $areas = \App\Area::select('id', 'name')->where('parent', 0)->get();

        return response()->json(['success' => true, 'customers' => $customers, 'packages' => $packages, 'areas' => $areas ], $this->success);
    }

    protected function _customerStatus( $status )
    {
        switch ( $status ) {
            case '2':
                return 'Inactive';
                break;
            case '1':
                return 'Active';
                break;
            default:
                return 'Pending';
                break;
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
        //form validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'username' => 'required|unique:customers,username',
            'email' => 'required|unique:users,email',
            'type' => 'required|string',
            'zone_id' => 'required|numeric',
            'package_id' => 'required|numeric',
            'cell_1' => 'required|string',
            'address' => 'required|string',
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success );

        //creating user account first
        $user = new \App\User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make( getOption('default_password') );

        $user->save();

        if( $user->id ) :
            //Saving data
            $customer = new customer();
            $customer->name = $request->name;
            $customer->user_id = $user->id;
            $customer->fathers_name = $request->fathers_name;
            $customer->mothers_name = $request->mothers_name;
            $customer->username = $request->username;
            $customer->email = $request->email;
            $customer->zone_id = $request->zone_id;
            $customer->area_id = $request->area_id;
            $customer->type = ( $request->type ) ? $request->type : 'home';
            $customer->home_phone = $request->cell_1;
            $customer->office_phone = $request->cell_2;
            $customer->mikrotik_id = $request->mikrotik_id;
            $customer->remote_ip = $request->remote_ip;
            $customer->remote_mac = $request->remote_mac;
            $customer->package_id = $request->package_id;
            // $customer->monthly_discount = $request->monthly_discount;
            // $customer->cable_cost = $request->cable_cost;

            $customer->save();

            //return success of fail message
            if( $customer->id ) :
                return response()->json(['success' => true], $this->success );
            else :
                return response()->json(['success' => false, 'msg' => 'Sorry! cannot save customer.'], $this->success);
            endif;
        else :
            return response()->json(['success' => false, 'msg' => 'Sorry! cannot create user account.'], $this->success);
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
            'username' => 'required|unique:customers,username,' . $request->username,
            'email' => 'required|unique:users,email,' . $request->email,
            'type' => 'required|string',
            'zone_id' => 'required|numeric|exists:areas,id',
            'package_id' => 'required|numeric|exists:packages,id',
            'cell_1' => 'required|string',
            'address' => 'required|string',
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success );

        //Saving data
        $customer = new customer();
        $customer->name = $request->name;
        $customer->fathers_name = $request->fathers_name;
        $customer->mothers_name = $request->mothers_name;
        $customer->zone_id = $request->zone_id;
        $customer->area_id = $request->area_id;
        $customer->type = ( $request->type ) ? $request->type : 'home';
        $customer->home_phone = $request->cell_1;
        $customer->office_phone = $request->cell_2;
        $customer->mikrotik_id = $request->mikrotik_id;
        $customer->remote_ip = $request->remote_ip;
        $customer->remote_mac = $request->remote_mac;
        $customer->package_id = $request->package_id;
        // $customer->monthly_discount = $request->monthly_discount;
        // $customer->cable_cost = $request->cable_cost;

        $customer->save();

        //return success of fail message
        if( $customer->id ) :
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Sorry! cannot save customer.'], $this->success);
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
        $item = Customer::findOrFail( $id );

        if( $item ) :
            $ok = $item->delete();
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Item not found.'], $this->success);
        endif;
    }
}
