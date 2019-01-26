<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class RequestController extends Controller
{
    protected $success;

    public function __construct()
    {
        $this->success = 200;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $type = ( $request->type ) ? strtolower( $request->type ) : 'pending';

        $query = \App\Request::with(['customer', 'customer.package']);
        if( $type == 'pending'){
            $query->where('status', 0);
        } elseif( $type == 'accepted'){
            $query->where('status', 1);
        } elseif( $type == 'suspended'){
            $query->where('status', 2);
        }
        $items = $query->paginate(20);

        $arr = array();

        if( $items ) {
            foreach( $items as $item ) {
                $row['id'] = $item->id;
                $row['customer_id'] = $item->customer['id'];
                $row['customer_name'] = $item->customer['name'];
                $row['type'] = ucwords( str_replace( '_', ' ', $item->type ) );
                $row['customer_package'] = $item->customer['package']['name'];
                $row['package_price'] = $item->customer['package']['price'];
                $row['status'] = $item->status;
                $row['status_label'] = ucfirst( $type );


                array_push( $arr, $row );
            }
        }

        return response()->json(['success' => true, 'requests' => $arr], 200);
    }

    public function takeAction( Request $request )
    {
        $type = ( string ) $request->type;
        $requestID = ( int ) $request->id;

        //get ticket Info
        $info = \App\Request::find( $requestID );

        if( $info && $info->status != 2) :
            if( $type == 'accept' ) {
                $method_name = $info->type;
                $ok = $this->{$method_name}( $info );

                if( $ok ) {
                    return response()->json( ['success' => true], $this->success );
                } else {
                    return response()->json(['success' => false, 'msg' => 'Request cannot be accepted.'], $this->success );
                }
            } else {
                $ok = $this->_cancel( $info );

                if( $ok ) :
                    return response()->json( ['success' => true], $this->success );
                else :
                    return response()->json(['success' => false, 'msg' => 'Request cannot be canceled.'], $this->success );
                endif;
            }
        else :
            return response()->json(['success' => false, 'msg' => 'Request not found.'], $this->success );
        endif;
    }

    protected function active_line_suspend( $info )
    {
        return true;
    }

    protected function old_line_activation( $info )
    {
        return true;
    }

    protected function new_line_activation( $info )
    {
        //get customer details
        $customer = \App\Customer::find( $info->customer_id );

        if( !$customer )
            return false;
        
        //if new activation request found, then set bill with cable cost + setup charge and others charge as well service is (if necessary)

        //service charge if month is equal
        $service_month = strtolower( date('F') );
        // $service_charge = floatval( getOption( $service_month ) );

        //if bill is not exist then set bills for this customer
        if( !$this->bill_exist( $info->customer_id ) ) :

            //calculate monthly bills by remain days
            $d = new \DateTime(date('Y-m-d'));
            $lastDay = $d->format('Y-m-t'); //last day of this month

            //remain days of this month calculated from active date
            $remain_days = date('d', strtotime($lastDay)) - date('d', $info->action_date);
            $remain_days = $remain_days + 1;

            // devide packege amount by default 30 monthly days
            //total days in this month  date("t");
            $total_days =  date("t");

            //current month bill of this user
            $current_bill = ($customer->package['price'] - $customer->monthly_discount);
            $oneDaysbill = $current_bill / $total_days;

            //packege bill
            $packege_bill =  $oneDaysbill * $remain_days;
            //check activation / Proccecing date is next month or later
            if( date( 'Y-m-d', $info->process_time ) > $lastDay ) :

                //total bill if activation date is next month
                $total_bill = ($customer->initial_due + $customer->cable_cost + $customer->setup_charge);

                //monthly discount
                $monthly_discount = 0;
                $packege_bill = 0;

                //set remarks
                $remarks = 'Initial_dues : '.$info->initial_due.'<br />Setup Charge : '.$customer->setup_charge.'<br /> Cable Cost : '.$customer->cable_cost.'<br />Service Charge : ';
            else :

                //total bill if activation date is current month
              $total_bill = ($packege_bill + $customer->initial_due + $customer->cable_cost + $customer->setup_charge);

              //monthly discount
              $monthly_discount = $customer->monthly_discount;

              //set remarks
              $remarks = 'Monthly Bill ('.$remain_days.' Days) : '.$packege_bill.'<br />Monthly Discount : '.$customer->monthly_discount.'<br />Initial_dues : '.$customer->initial_due.'<br />Setup Charge : '.$customer->setup_charge.'<br /> Cable Cost : '.$customer->cable_cost.'<br />Service Charge : ';
            endif;

           //set data for billings
            $bill = new \App\Billing;
            $bill->bills =  floor( $total_bill );
            $bill->package_id = $customer->package_id;
            $bill->customer_id = $info->customer_id;
            $bill->dues = floor( $total_bill );
            $bill->bill_month = date('F-Y');
            $bill->package_bill = floor( $packege_bill );
            $bill->monthly_discount =  $monthly_discount;
            $bill->remarks =  $remarks;

            //set bills
            $ok = $bill->save();

        endif;

        //activate customer and reset the additional bills
        $customer->status = 1;
        $customer->setup_charge = 0;
        $customer->cable_cost = 0; 
        $customer->dues = 0;
        $ok = $customer->save();
        if( $ok ) :
            if( $this->_accept( $info ) ) :
                return true;
            endif;
        else :
            return false;
        endif;
    }

    protected function bill_exist( $customerId, $month = null )
    {
        //set default month
        if($month == NULL){
            $month = date('F-Y');
        }

        //check bill exist or not
        $query = \App\Billing::where(['customer_id' => $customerId, 'bill_month'=>$month])->get();

        return ( count( $query ) ) ? true : false;
    }

    protected function _accept( $info )
    {
        $info->status = 1;
        $info->save();

        return ( $info->status == 1 ) ? true : false;
    }

    protected function _cancel( $info )
    {
        $info->status = 2;
        $info->save();

        return ( $info->status == 2 ) ? true : false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
