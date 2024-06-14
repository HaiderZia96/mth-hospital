<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Department;
use App\Models\Manager\EventCategory;
use App\Models\Manager\NewsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class NewsAndEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $record = NewsEvent::select('news_events.*', 'event_category.id as event_id', 'event_category.name as event_name', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('event_category', 'event_category.id', '=', 'news_events.e_cate')
            ->leftJoin('departments', 'departments.id', '=', 'news_events.dpt_id')
            ->orderBy('priority', 'desc')
            ->paginate(8);
        $footerDepartments = Department::orderBy('priority', 'desc')->paginate(5);
//dd($record);
        $data = [
            'page_title' => 'News & Events',
            'p_title' => 'News & Events',
            'record' => $record,
            'footerDepartments' => $footerDepartments
        ];
//        dd($data);
        return view('front.news_and_events')->with($data);
    }

    public function getImageThumbnail($id, $filename)
    {
        $record = NewsEvent::where('id', $id)->where('thumbnail_url_name', '=', $filename)->first();
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = Storage::disk('private')->path('NewsAndEvents/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
//       dd($path);
        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        } else {
            abort(404, 'NOT FOUND');
        }
    }

    public function getImageBanner($id, $filename)
    {
        $record = NewsEvent::where('id', $id)->where('banner_url_name', '=', $filename)->first();
//        dd($record);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $path = Storage::disk('private')->path('NewsAndEvents/Banner/' . date('Y') . '/' . date('m') . '/' . $record->banner_url_name);
//        dd($path);
        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        } else {
            abort(404, 'NOT FOUND');
        }
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

    public function category($category)
    {
//        dd($category);
//        dd('123');
        $record = NewsEvent::select('news_events.*', 'event_category.id as event_id', 'event_category.name as event_name', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('event_category', 'event_category.id', '=', 'news_events.e_cate')
            ->leftJoin('departments', 'departments.id', '=', 'news_events.dpt_id')
            ->where('event_category.name', $category)->paginate(3);

        $data = [
            'page_title' => 'News & Events Details',
            'p_title' => 'News & Events Details',
            'record' => $record,
        ];

//        echo "laravel";
        return view('front.news_and_events')->with($data);
    }
}
