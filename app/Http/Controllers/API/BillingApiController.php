<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class BillingApiController extends Controller
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
        $month = ( ! $request->month ) ? date('F-Y') : $request->month;

        $items = \App\Billing::with('customer')->where('bill_month', $month)->get();

        return response()->json(['success' => true, 'date' => $items], $this->success);
    }

    public function dueCustomerList( Request $request )
    {
        $customers = \App\Customer::with(['package'])->get();

        return response(['success' => true, 'data' => $customers], $this->success );
    }

    public function paymentList( Request $request )
    {
        $month = ( $request->month ) ? $request->month : date('F-Y');
        $payments = \App\Payment::with('customer.package')->where('bill_month', $month)->get();

        return response()->json(['success' => true, 'data' => $payments], $this->success);
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
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric',
            'reciept_no' => 'required|string',
            'discount' => 'required|numeric',
            'remarks' => 'required',
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success );

        $info = \App\Customer::find( $request->customer_id );

        //creating user account first
        $payment = new \App\Payment;
        $payment->bill_month = date('F-Y');
        $payment->customer_id = $request->customer_id;
        $payment->payable_bill = $info->dues;
        $payment->paid_amount = $request->amount;
        $payment->instant_discount = $request->discount;
        $payment->reciept_number = $request->reciept_no;
        $payment->method = 'cash';
        $payment->remarks = $request->remarks;

        $payment->save();

        if( $payment->id ) :
            //Saving data
            $info->dues = $info->dues - ( $payment->amount + $payment->discount );

            $info->save();

            //return success of fail message
            if( $info->id ) :

                return response()->json(['success' => true], $this->success );
            else :
                $payment->delete();
                return response()->json(['success' => false, 'msg' => 'Sorry! payment not success.'], $this->success);
            endif;
        else :
            return response()->json(['success' => false, 'msg' => 'Sorry! payment failed.'], $this->success);
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
