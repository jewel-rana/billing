<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::paginate(15);
        return view('dashboard.page.index', compact( 'pages' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate( $request, [
            'title' => 'required',
            'content' => 'required',
            'template' => 'required'
        ]);

        $page = new Page;
        $page->title = $request->title;
        $page->content = $request->content;
        $page->template = $request->template;
        $page->slug = ( $request->slug != null ) ? niceSlug( $request->slug ) : niceSlug( $request->title );
        $page->save();

        if( $page->id ) :
            $msg = 'Page has been created successfully.';
            $label = 'success';
        else :
            $msg = 'Sorry! page cannot be sent.';
            $label = 'error';
        endif;

        return redirect()->route('dashboard.page.index')->with([
            'message.label' => $label,
            'message.content' => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Page $page )
    {
        return view('dashboard.page.show', compact('page') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('dashboard.page.edit', compact( 'page' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page )
    {
        
        $this->validate( $request, [
            'title' => 'required',
            'content' => 'required',
            'template' => 'required'
        ]);

        $page->title = $request->title;
        $page->content = $request->content;
        $page->template = $request->template;
        $page->slug = ( $request->slug != null ) ? niceSlug( $request->slug ) : niceSlug( $request->title );
        $page->save();

        if( $page->id ) :
            $msg = 'Page has been updated successfully.';
            $label = 'success';
        else :
            $msg = 'Sorry! Page cannot be updated.';
            $label = 'error';
        endif;

        return redirect()->route('dashboard.page.index')->with([
            'message.label' => $label,
            'message.content' => $msg
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Page $page )
    {
        
        $delete = $page->delete();

        if( $delete ) :
            $msg = 'Page has been deleted successfully.';
            $label = 'success';
        else :
            $msg = 'Sorry! Page cannot be deleted.';
            $label = 'error';
        endif;

        return redirect()->route('dashboard.page.index')->with([
            'message.label' => $label,
            'message.message' => $msg
        ]);
    }
}
