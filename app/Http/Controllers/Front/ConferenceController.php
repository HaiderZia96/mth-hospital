<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Conference;
use App\Models\Manager\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conferences = Conference::select('conferences.*', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'conferences.department_id')
            ->orderBy('priority', 'desc')->
             paginate(8);
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
        $data = [
            'page_title' => 'Conferences',
            'p_title' => 'Conferences',
            'conferences' => $conferences,
            'footerDepartments' => $footerDepartments
        ];
//        dd($data);
        return view('front.conference')->with($data);
    }
    public function getImage(string $filename)
    {
//        dd($filename);
        $record = Conference::where('image_url_name','=',$filename )->first();
//        dd($record);
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }
        $path = Storage::disk('private')->path('Conference/Image/'. date('Y'). '/' . date('m') . '/' . $record->image_url_name);
        //dd($path);
        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
        else{
            abort(404, 'NOT FOUND');
        }
    }
    public function category($category)
    {
//        dd('123');
//        dd($category);
        $conferences = Conference::select('conferences.*',  'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'conferences.department_id')
            ->where('departments.title', $category)->paginate(3);
//dd($record);
        $data = [
            'page_title' => 'Conference Detail',
            'p_title' => 'Conference Detail',
            'conferences' => $conferences,
        ];

//        echo "laravel";
        return view('front.conference')->with($data);
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
