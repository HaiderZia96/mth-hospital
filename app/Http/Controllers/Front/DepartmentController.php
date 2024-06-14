<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::orderBy('priority', 'desc')->
        paginate(12);
        $footerDepartments = Department::orderBy('priority', 'desc')->paginate(5);
//        dd($departments);
        $data = [
            'page_title' => 'Department',
            'p_title' => 'Department',
            'departments' => $departments,
            'footerDepartments' => $footerDepartments
        ];
//        dd($departments);
        return view('front.department')->with($data);
    }

    public function departmentDetail($slug)
    {
//        dd('123');
        $departmentDetails = Department::where('slug', $slug)->get();
        $departments = Department::paginate(10);
        $footerDepartments = Department::orderBy('priority', 'desc')->paginate(5);
        $data = [
            'page_title' => 'Department Details',
            'p_title' => 'Department Details',
            'departmentDetails' => $departmentDetails,
            'departments' => $departments,
            'footerDepartments' => $footerDepartments
        ];
//        dd($departmentDetails);
//        echo "laravel";
        return view('front.department_details')->with($data);
    }

    public function showDepartmentImages($dept_id, $filename)
    {
        $record = Department::where('id', $dept_id)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = '';
        if ($filename == 'icon_url') {
            $path = Storage::disk('private')->path('Department/Icons/' . date('Y') . '/' . date('m') . '/' . $record->icon_url_name);
        } else if ($filename == 'thumbnail_url') {
            $path = Storage::disk('private')->path('Department/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
        } else if ($filename == 'department_banner_url') {
            $path = Storage::disk('private')->path('Department/DeptBanner/' . date('Y') . '/' . date('m') . '/' . $record->department_banner_url_name);
        } else {
            if ($filename == 'cover_image_url') {
                $path = Storage::disk('private')->path('Department/CoverImage/' . date('Y') . '/' . date('m') . '/' . $record->cover_image_url_name);
            }
        }

        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
//        else {
//            abort(404, 'NOT FOUND');
//        }
    }

//    //    Thumbnail Banner
//    public function getImage($id, $filename)
//    {
//        //Modification Done in query add where id check
//        $record = Department::where('id', $id)->where('thumbnail_url_name', '=', $filename)->first();
////        dd($record);
//        if (empty($record)) {
//            abort(404, 'NOT FOUND');
//        }
//        $path = Storage::disk('private')->path('Department/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
//        //dd($path);
//        if (File::exists($path)) {
//            $file = File::get($path);
//            $type = File::mimeType($path);
//            $response = Response::make($file, 200);
//            $response->header("Content-Type", $type);
//            return $response;
//        } else {
//            abort(404, 'NOT FOUND');
//        }
//    }
//
//    public function getImageBanner($id, $filename)
//
//    {
//        $record = Department::where('id', $id)->where('department_banner_url_name', '=', $filename)->first();
////        dd($record);
//        if (empty($record)) {
//            abort(404, 'NOT FOUND');
//        }
//        $path = Storage::disk('private')->path('Department/DeptBanner/' . date('Y') . '/' . date('m') . '/' . $record->department_banner_url_name);
////        dd($path);
//        if (File::exists($path)) {
//            $file = File::get($path);
//            $type = File::mimeType($path);
//            $response = Response::make($file, 200);
//            $response->header("Content-Type", $type);
//            return $response;
//        } else {
//            abort(404, 'NOT FOUND');
//        }
//    }
//
//    public function getImageHod($id, $filename)
//
//    {
//        $record = Department::where('id', $id)->where('hod_image_url_name', '=', $filename)->first();
////        dd($record);
//        if (empty($record)) {
//            abort(404, 'NOT FOUND');
//        }
//        $path = Storage::disk('private')->path('Department/HOD/' . date('Y') . '/' . date('m') . '/' . $record->hod_image_url_name);
////        dd($path);
//        if (File::exists($path)) {
//            $file = File::get($path);
//            $type = File::mimeType($path);
//            $response = Response::make($file, 200);
//            $response->header("Content-Type", $type);
//            return $response;
//        } else {
//            abort(404, 'NOT FOUND');
//        }
//    }
//
//    public function getImageIcon($id, $filename)
//
//    {
//        $record = Department::where('id', $id)->where('icon_url_name', '=', $filename)->first();
////        dd($record);
//        if (empty($record)) {
//            abort(404, 'NOT FOUND');
//        }
//        $path = Storage::disk('private')->path('Department/Icons/' . date('Y') . '/' . date('m') . '/' . $record->icon_url_name);
////        dd($path);
//        if (File::exists($path)) {
//            $file = File::get($path);
//            $type = File::mimeType($path);
//            $response = Response::make($file, 200);
//            $response->header("Content-Type", $type);
//            return $response;
//        } else {
//            abort(404, 'NOT FOUND');
//        }
//    }

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
