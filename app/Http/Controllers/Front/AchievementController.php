<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Achievement;
use App\Models\Manager\Department;
use App\Models\Manager\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $achievements = Achievement::select('achievements.*', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'achievements.department_id')
            ->orderBy('achievements.id', 'desc')->
            paginate(8);
        $footerDepartments = Department::orderBy('priority', 'desc')->paginate(5);
        $data = [
            'page_title' => 'Achievements & Awards',
            'p_title' => 'Achievements & Awards',
            'achievements' => $achievements,
            'footerDepartments' => $footerDepartments
        ];
//        dd($data);
        return view('front.achievement')->with($data);
    }

    public function getImage($id, $filename)

    {
        $record = Achievement::where('id', $id)->where('image_url_name', '=', $filename)->first();
//        dd($record);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $path = Storage::disk('private')->path('Achievements/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);
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
