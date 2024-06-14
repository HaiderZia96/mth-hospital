<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Achievement;
use App\Models\Manager\Department;
use Illuminate\Http\Request;

class AchievementDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $achievements_details = Achievement::select('achievements.*', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'achievements.department_id')
            ->where('achievements.slug', $slug)->get();
        $achievements = Achievement::select('achievements.*', 'departments.id as department_id', 'departments.title as department_name')
            ->leftJoin('departments', 'departments.id', '=', 'achievements.department_id')
            ->paginate(8);
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
        $data = [
            'page_title' => 'Conference Detail',
            'p_title' => 'Conference Detail',
            'achievements_details' => $achievements_details,
            'achievements' => $achievements,
            'footerDepartments' => $footerDepartments
        ];
//        dd($data);
        return view('front.achievement_detail')->with($data);
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
