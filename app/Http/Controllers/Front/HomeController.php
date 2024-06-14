<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\Manager\Department;
use App\Models\Manager\NewsEvent;

use App\Models\Manager\TeamMember;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::orderBy('id','desc')->paginate(3);
        $newsEvents = NewsEvent::select('news_events.*','event_category.id as event_id', 'event_category.name as event_name', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('event_category', 'event_category.id', '=', 'news_events.e_cate')
            ->leftJoin('departments', 'departments.id', '=', 'news_events.dpt_id')
            ->orderBy('news_events.id','desc')
            ->paginate(3);
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
//        dd($newsEvents);
         $teams = TeamMember::orderBy('id','desc')->paginate(3);

        $data = [
            'page_title' => 'Home',
            'p_title' => 'Home',
            'departments' => $departments,
            'newsEvents' => $newsEvents,
            'teams' => $teams,
            'footerDepartments' => $footerDepartments,

        ];
//        dd($data);
        return view('front.index')->with($data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
