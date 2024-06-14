<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Conference;
use App\Models\Manager\Department;
use Illuminate\Http\Request;

class ConferenceDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $conference_details = Conference::select('conferences.*', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'conferences.department_id')
            ->where('conferences.slug', $slug)->get();
//        $depts = Department::select('*')
//            ->leftJoin('conferences', 'conferences.department_id', '=', 'departments.id')
//            ->groupBy('departments.id','departments.title')
//            ->get();
        $conferences = Conference::select('conferences.*', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'conferences.department_id')
            ->orderBy('priority', 'desc')->paginate(2);
//        dd($conferences);
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
        // For Next Prev Pagination
        $currentCon = Conference::where('slug', $slug)->first();
        $previousCon = Conference::where('id', '<', $currentCon->id)->orderBy('id', 'desc')->first();
        $nextCon = Conference::where('id', '>', $currentCon->id)->orderBy('id')->first();

        $data = [
            'page_title' => 'Conference Detail',
            'p_title' => 'Conference Detail',
            'conference_details' => $conference_details,
            'conferences' => $conferences,
            'currentCon' => $currentCon,
            'previousCon' => $previousCon,
            'nextCon' => $nextCon,
            'footerDepartments' => $footerDepartments
        ];
//        dd($data);
        return view('front.conference_detail')->with($data);
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
