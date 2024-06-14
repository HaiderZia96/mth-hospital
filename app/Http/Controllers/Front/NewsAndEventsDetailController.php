<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Department;
use App\Models\Manager\EventCategory;
use App\Models\Manager\NewsEvent;
use Illuminate\Http\Request;

class NewsAndEventsDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $eventDetails = NewsEvent::select('news_events.*','event_category.id as event_id', 'event_category.name as event_name', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('event_category', 'event_category.id', '=', 'news_events.e_cate')
            ->leftJoin('departments', 'departments.id', '=', 'news_events.dpt_id')
            ->where('news_events.slug', $slug)->get();
        $recentEvents = NewsEvent::select('news_events.*','event_category.id as event_id', 'event_category.name as event_name', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('event_category', 'event_category.id', '=', 'news_events.e_cate')
            ->leftJoin('departments', 'departments.id', '=', 'news_events.dpt_id')
            ->orderBy('news_events.id', 'desc')->get();
        $events = EventCategory::get();
        // For Next Prev Pagination
        $currentEvent = NewsEvent::where('slug', $slug)->first();
        $previousEvent = NewsEvent::where('id', '<', $currentEvent->id)->orderBy('id', 'desc')->first();
        $nextEvent = NewsEvent::where('id', '>', $currentEvent->id)->orderBy('id')->first();
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
        $data = [
            'page_title' => 'News & Events Details',
            'p_title' => 'News & Events Details',
            'eventDetails' => $eventDetails,
            'events' => $events,
            'recentEvents' => $recentEvents,
            'currentEvent' => $currentEvent,
            'previousEvent' => $previousEvent,
            'nextEvent' => $nextEvent,
            'footerDepartments' => $footerDepartments
        ];

//dd($events);
//        echo "laravel";
        return view('front.news_and_events_detail')->with($data);
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
