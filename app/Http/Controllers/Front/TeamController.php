<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Department;
use App\Models\Manager\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = TeamMember::paginate(12);
        $footerDepartments = Department::orderBy('priority', 'desc')->paginate(5);
        $data = [
            'page_title' => 'Our Team',
            'p_title' => 'Our Team',
            'teams' => $teams,
            'footerDepartments' => $footerDepartments
        ];
        return view('front.team')->with($data);
    }

    public function getImage($id, string $filename)
    {
        $record = TeamMember::where('id', $id)->where('image_url_name', '=', $filename)->first();
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = Storage::disk('private')->path('Team/Members/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);

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

    public function detail($slug)
    {
        $footerDepartments = Department::orderBy('priority', 'desc')->paginate(5);
        $memberDetails = TeamMember::where('slug', $slug)->get();
        $members = TeamMember::get();
        $data = [
            'page_title' => 'Team Member Details',
            'p_title' => 'Team Member Details',
            'memberDetails' => $memberDetails,
            'members' => $members,
            'footerDepartments' => $footerDepartments
        ];

        return view('front.team_detail')->with($data);
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
