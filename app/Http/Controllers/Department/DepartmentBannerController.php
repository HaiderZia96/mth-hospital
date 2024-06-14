<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department\DepartmentBanner;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class DepartmentBannerController extends Controller
{
//    function __construct()
//    {
//        $this->middleware('auth');
//        $this->middleware('permission:deptBanner-list');
//        $this->middleware('permission:deptBanner-create', ['only' => ['create','store']]);
//        $this->middleware('permission:deptBanner-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:deptBanner-softdelete', ['only' => ['destroy']]);
//        $this->middleware('permission:deptBanner-restore', ['only' => ['restore']]);
//        $this->middleware('permission:deptBanner-delete', ['only' => ['delete']]);
//    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'page_title' => 'Department Banners',
            'p_title'=>'Department Banners',
            's_title' => 'Department Banners',
            'p_summary' => 'List of Department Banner',
            'p_description' => null,
            'url' => route('department.dept-banner.create'),
            'url_text' => 'Add New',
            'trash' => route('department.get.dept-banner-activity-trash'),
            'trash_text' => 'View Trash',
        );
        return view('department.deptBanner.index')->with($data);
    }
    public function getIndex(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $data_arr = array();

        // Total records
        $totalRecords = DepartmentBanner::select('count(*) as allcount')
            ->where('department_banners.title', 'like', '%' .$searchValue . '%')
            ->orWhere('department_banners.description', 'like', '%' .$searchValue . '%')
            ->orWhere('department_banners.status', 'like', '%' .$searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();


        $totalRecordswithFilter = DepartmentBanner::select('count(*) as allcount')
            ->where('department_banners.title', 'like', '%' .$searchValue . '%')
            ->orWhere('department_banners.description', 'like', '%' .$searchValue . '%')
            ->orWhere('department_banners.status', 'like', '%' .$searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();
        // Fetch records
        $records = DepartmentBanner::orderBy($columnName,$columnSortOrder)

            ->where('department_banners.title', 'like', '%' .$searchValue . '%')
            ->orWhere('department_banners.description', 'like', '%' .$searchValue . '%')
            ->orWhere('department_banners.status', 'like', '%' .$searchValue . '%')
            ->select('department_banners.*')
            ->orderBy('id', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        // dd($sid);

        foreach ($records as $record) {
            $id = $record->id;
            $title = $record->title;
            $description = $record->description;
            $image = $record->image;
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "title" => $title,
                "description" => $description,
                "image" => $image,
                "status" => $status,
            );
//            dd($data_arr);

        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'page_title' => 'Department Banner',
            'p_title' => 'Department Banner',
            'p_summary' => 'Add Department Banner',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('department.dept-banner.store'),
            'url' => route('department.dept-banner.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );
        return view('department.deptBanner.create')->with($data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        // Crop Image.
        $filename = null;
        if ($request->hasFile('image')) {
            //Get requested image
            $image = $request['image'];
//            dd($image);
            $imageOriginalName = $image->getClientOriginalName();

            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);

            $imageExtension = $image->getClientOriginalExtension();
            $imageSize = $image->getSize();
//            dd($request->base64image );
            if ($request->base64image || $request->base64image != '0') {
                $folderPath = 'Department/DeptBanner/';
                $image_parts = explode(";base64,", $request->base64image);

                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $filename = date('Y') . '/' . date('m') . '/' . date('d') . '/' . time() . '-' . rand(0, 999999) . $imageName . '.' . $image_type;
                $file = $folderPath . $filename;
                Storage::disk('private')->put($file, $image_base64);
            }
        }
        $arr = [
            'description' => $request->description,
            'title' => $request->title,
            'status' => $request->status,
            'image' => $filename,
            'created_by' => Auth::user()->id,
        ];
        $record = DepartmentBanner::create($arr);

        if ($record) {
            $messages = [
                array(
                    'message' => 'Record created successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('department.dept-banner.index');
        } else {
            abort(404, 'NOT FOUND');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = DepartmentBanner::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('DepartmentBanner')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');
        $data = array(
            'page_title' => 'Department Banner',
            'p_title' => 'Department Banner',
            'p_summary' => 'Show Department Banner',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('department.dept-banner.update', $record->id),
            'url' => route('department.dept-banner.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'application/x-www-form-urlencoded',
        );
        return view('department.deptBanner.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $record = DepartmentBanner::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $data = array(
            'page_title' => 'Department Banner',
            'p_title' => 'Department Banner',
            'p_summary' => 'Show Department Banner',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('department.dept-banner.update', $record->id),
            'url' => route('department.dept-banner.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );
        return view('department.deptBanner.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = DepartmentBanner::find($id);
        $this->validate($request, [
            'image' => 'file|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Crop Image.
        if ($request->hasFile('image')) {
            //Unlink previous image
            if (isset($record) && $record->image) {
                $prevImage = Storage::disk('private')->path('Department/DeptBanner/'.$record->image);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
            }
            //Get requested image
            $image = $request->file('image');
            $imageOriginalName = $image->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $image->getClientOriginalExtension();
            $imageSize = $image->getSize();
            if ($request->base64image || $request->base64image != '0') {
                $folderPath = 'Department/DeptBanner/';
                $image_parts = explode(";base64,", $request->base64image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $filename = date('Y').'/'.date('m').'/'.date('d').'/'.time().'-'. rand(0, 999999).$imageName.'.'.$image_type;
                $file = $folderPath.$filename;
                Storage::disk('private')->put($file, $image_base64);
                $arr['image'] = $filename;
            }
        }
        else{
            $filename = $record->image;
        }

        $arr = [
            'description' => $request->description,
            'title' => $request->title,
            'image' => $filename,
            'status' => $request->status,
            'updated_by' => Auth::user()->id,
        ];
        $record->update($arr);
        $messages = [
            array(
                'message' => 'Record updated successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('department.dept-banner.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = DepartmentBanner::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $image_path = Storage::disk('private')->path('Department/DeptBanner/' . $record->image);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
        $record->delete();

        $messages = [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('department.dept-banner.index');
    }

    public function deptBannerStatus($id)
    {
        $record = DepartmentBanner::find($id);
        if ($record->status == '0') {
            $value = '1';
        } else {
            $value = '0';
        }
        $arr = [
            'status' => $value,
        ];

        $record->update($arr);
        $messages = [
            array(
                'message' => 'Record updated successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('department.dept-banner.index');

    }
    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Department Banner',
            'p_title' => 'Department Banner',
            'p_summary' => 'Show Department Banner',
            'p_description' => null,
            'url' => route('department.dept-banner.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('department.deptBanner.activity')->with($data);
    }

    public function getActivityLog(Request $request, string $id)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', DepartmentBanner::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', DepartmentBanner::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', DepartmentBanner::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $attributes = (!empty($record->properties['attributes']) ? $record->properties['attributes'] : '');
            $old = (!empty($record->properties['old']) ? $record->properties['old'] : '');
            $current = '<ul class="list-unstyled">';
            //Current
            if (!empty($attributes)) {
                foreach ($attributes as $key => $value) {
                    if (is_array($value)) {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    } else {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                }
            }
            $current .= '</ul>';
            //Old
            $oldValue = '<ul class="list-unstyled">';
            if (!empty($old)) {
                foreach ($old as $key => $value) {
                    if (is_array($value)) {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    } else {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                }
            }
            //updated at
            $updated = 'Updated:' . $record->updated_at->diffForHumans() . '<br> At:' . $record->updated_at->isoFormat('llll');
            $oldValue .= '</ul>';
            //Causer
            $causer = isset($record->causer) ? $record->causer : '';
            $type = $record->description;
            $data_arr[] = array(
                "id" => $id,
                "current" => $current,
                "old" => $oldValue,
                "updated" => $updated,
                "causer" => $causer,
                "type" => $type,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function getTrashActivity()
    {
        //Data Array
        $data = array(
            'page_title' => 'Department Banner',
            'p_title' => 'Department Banner',
            'p_summary' => 'Show Department Banner Trashed Activity',
            'p_description' => null,
            'url' => route('department.dept-banner.index'),
            'url_text' => 'View All',
        );
        return view('department.deptBanner.trash')->with($data);
    }

    public function getTrashActivityLog(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', DepartmentBanner::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', DepartmentBanner::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', DepartmentBanner::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $attributes = (!empty($record->properties['attributes']) ? $record->properties['attributes'] : '');
            $old = (!empty($record->properties['old']) ? $record->properties['old'] : '');
            $current = '<ul class="list-unstyled">';
            //Current
            if (!empty($attributes)) {
                foreach ($attributes as $key => $value) {
                    if (is_array($value)) {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    } else {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                }
            }
            $current .= '</ul>';
            //Old
            $oldValue = '<ul class="list-unstyled">';
            if (!empty($old)) {
                foreach ($old as $key => $value) {
                    if (is_array($value)) {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    } else {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                }
            }
            //updated at
            $updated = 'Updated:' . $record->updated_at->diffForHumans() . '<br> At:' . $record->updated_at->isoFormat('llll');
            $oldValue .= '</ul>';
            //Causer
            $causer = isset($record->causer) ? $record->causer : '';
            $type = $record->description;
            $data_arr[] = array(
                "id" => $id,
                "current" => $current,
                "old" => $oldValue,
                "updated" => $updated,
                "causer" => $causer,
                "type" => $type,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }
}
