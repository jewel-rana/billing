<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;
use DB;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['categories']=Category::where('parent',null)->pluck('name', 'id');
        $data['childs']=Category::where('parent','!=',null)->pluck('name', 'id');
        return view('dashboard.option.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.option.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $label = '';
        $msg = '';
        if( isset( $_POST['tab'] ) ) :
            // dd( $_POST );
            foreach( $_POST as $key => $value ) 
            {
                // if key == submit the skip
                if( $key != 'submit' && $key != 'tab' && $key != 'id' && $key != '_token')
                {
                    if( $this->_option_exist( $key ) ) {
                        $option = DB::table('options')
                            ->where( 'label', $key )
                            ->update( ['content' => $value ] );
                        // $option->content = $value;
                        // $option->save();
                            $msg = 'Option has been updated successfully.';
                            $label = 'success';
                    }else{
                        $option = new Option;
                        $option->label = $key;
                        $option->content = $value;

                        $option->save();
                        if( $option ) :
                            $msg = 'Option has been updated successfully.';
                            $label = 'success';
                        else :
                            $label = 'error';
                            $msg = 'Sorry! Option cannot be updated.';
                        endif;
                    }
                }
            }

        else :
            $label = 'error';
            $msg = 'Sorry! Option cannot be updated.';
        endif;

        return redirect()->route('dashboard.option.index', ['tab' => $request->tab ])->with(['message.label' => $label, 'message.content' => $msg]);
    }

    public function _option_exist( $key )
    {
        $option = Option::where( 'label', $key )->first();

        return ( $option ) ? true : false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('dashboard.option.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('dashboard.option.edit');
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
