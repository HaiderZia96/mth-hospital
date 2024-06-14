<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\CandidateDocument;
use App\Models\Country;
use App\Models\Manager\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class DepartmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_department_department-list');
        $this->middleware('permission:manager_department_department-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manager_department_department-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manager_department_department-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_department_department-activity-log', ['only' => ['getActivity', 'getActivityLog']]);
        $this->middleware('permission:manager_department_department-activity-log-trash', ['only' => ['getTrashActivity', 'getTrashActivityLog']]);
        $this->middleware('permission:manager_department_department-swap', ['only' => ['swapUp', 'swapDown']]);
        //update status
        $this->middleware('permission:manager_department_update-status', ['only' => ['updateStatus']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'page_title' => 'Departments',
            'p_title' => 'Departments',
            's_title' => 'Departments',
            'p_summary' => 'List of Departments',
            'p_description' => null,
            'url' => route('manager.dept.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.dept-activity-trash'),
            'trash_text' => 'View Trash',
        );
//        dd($data);
        return view('manager.dept.index')->with($data);
    }

    public function getIndex(Request $request)
    {
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
        $totalRecords = Department::count();


        $totalRecordswithFilter = Department::where('departments.title', 'like', '%' . $searchValue . '%')
            ->count();

        // Fetch records
        $records = Department::where('departments.title', 'like', '%' . $searchValue . '%')
            ->orderBy('priority', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $title = $record->title;
            $description = $record->description;
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "title" => $title,
                "status" => $status,
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'page_title' => 'Department',
            'p_title' => 'Department',
            'p_summary' => 'Add Department',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.dept.store'),
            'url' => route('manager.dept.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );

        return view('manager.dept.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'thumbnail_url' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'department_banner_url' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'cover_image_url' => 'mimes:jpg,jpeg,png,gif|max:2048',

            'title' => 'required|unique:departments|string',
            'priority' => 'nullable|unique:departments,priority',
            'icon_url' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'thumbnail_url.required' => 'Thumbnail is Required',
            'thumbnail_url.mimes' => 'Thumbnail File Type should be jpg, jpeg, png and gif',
            //Validation for Department Banner
            'department_banner_url.required' => 'Banner is Required',
            'department_banner_url.mimes' => 'Banner File Type should be jpg, jpeg, png and gif',
            //Validation for Department Icon
            'icon_url.required' => 'Icon is Required',
            'icon_url.mimes' => 'Icon File Type should be jpg, jpeg, png and gif',

            //Validation for Cover Image
            'cover_image_url.mimes' => 'Cover Image File Type should be jpg, jpeg, png and gif',
        ]);

        //Department Thumbnail
        $thumbnail_url = '';
        $thumbnailOriginalName = '';
        $thumbnailUrlfileName = '';
        if ($request->hasFile('cropper_thumbnail')) {
            // Thumbnail
            $cThumbnail = $request->file('thumbnail_url');
            $thumbnailOriginalName = $cThumbnail->getClientOriginalName();
            $thumbnailName = pathinfo($thumbnailOriginalName, PATHINFO_FILENAME);
            $thumbnailExtension = $cThumbnail->getClientOriginalExtension();
            $thumbnailOriginalNameSluggy = Str::slug($thumbnailName);
            $cThumbnailFileName = time() . rand(0, 999999) . '-' . $thumbnailOriginalNameSluggy . '.' . $thumbnailExtension;
//            $thumbnail_url = date('Y'). '/' . date('m') . '/' . $cThumbnailFileName;
            $basePath = 'private';
            $thumbnailPath = $basePath . '/Department';
            $attachmentPath = $thumbnailPath . '/Thumbnail';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $thumbnailUrlfileName = $cThumbnailFileName;
            $thumbnail_url = $request->file('cropper_thumbnail')->storeAs(
                $monthlyAttachmentsPath,
                $cThumbnailFileName
            );
        }

        //Department Banner
        $department_banner_url = '';
        $departmentBannerOriginalName = '';
        $departmentBannerUrlfileName = '';
        if ($request->hasFile('cropper_banner')) {
            // Thumbnail
            $cDepartmentBanner = $request->file('department_banner_url');
            $departmentBannerOriginalName = $cDepartmentBanner->getClientOriginalName();
            $departmentBannerName = pathinfo($departmentBannerOriginalName, PATHINFO_FILENAME);
            $departmentBannerExtension = $cDepartmentBanner->getClientOriginalExtension();
            $departmentBannerOriginalNameSluggy = Str::slug($departmentBannerName);
            $cDepartmentBannerFileName = time() . rand(0, 999999) . '-' . $departmentBannerOriginalNameSluggy . '.' . $departmentBannerExtension;
            $basePath = 'private';
            $bannerPath = $basePath . '/Department';
            $attachmentPath = $bannerPath . '/DeptBanner';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $departmentBannerUrlfileName = $cDepartmentBannerFileName;
            $department_banner_url = $request->file('cropper_banner')->storeAs(
                $monthlyAttachmentsPath,
                $cDepartmentBannerFileName
            );
        }

        // Department Icon
        $icon_url = '';
        $iconOriginalName = '';
        $iconUrlfileName = '';

        if ($request->hasFile('icon_url')) {
            // Icon
            $cIcon = $request->file('icon_url');
            $iconOriginalName = $cIcon->getClientOriginalName();
            $iconName = pathinfo($iconOriginalName, PATHINFO_FILENAME);
            $iconExtension = $cIcon->getClientOriginalExtension();
            $iconOriginalNameSluggy = Str::slug($iconName);
            $cIconFileName = time() . rand(0, 999999) . '-' . $iconOriginalNameSluggy . '.' . $iconExtension;
            $basePath = 'private';
            $iconPath = $basePath . '/Department';
            $attachmentPath = $iconPath . '/Icons';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $iconUrlfileName = $cIconFileName;
            $icon_url = $request->file('icon_url')->storeAs(
                $monthlyAttachmentsPath,
                $cIconFileName
            );
        }

        // Cover Image Icon
        $cover_url = '';
        $coverOriginalName = '';
        $final_cover_image_name = '';
        if ($request->hasFile('cover_image_url')) {
            // Icon
            $cover_file = $request->file('cover_image_url');
            $coverOriginalName = $cover_file->getClientOriginalName();
            $cover_name = pathinfo($coverOriginalName, PATHINFO_FILENAME);
            $cover_extension = $cover_file->getClientOriginalExtension();
            $coverOriginalNameSluggy = Str::slug($cover_name);

            $basePath = 'private';
            $iconPath = $basePath . '/Department';
            $attachmentPath = $iconPath . '/CoverImage';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');

            $final_cover_image_name = time() . rand(0, 999999) . '-' . $coverOriginalNameSluggy . '.' . $cover_extension;

            $cover_url = $request->file('cover_image_url')->storeAs(
                $monthlyAttachmentsPath,
                $final_cover_image_name
            );
        }

        // get max priority from db
        $max = Department::selectRaw('MAX(priority) as max')->first()->max;
        //  dd($max);
        // get priority
        if (isset($max)) {
            $priority = $max + 1;
        } // when no records already exist
        else {
            $priority = 1;
        }

        // set department priority
        $input['priority'] = $priority;

        $arr = [
            'description' => $request->description,
            'title' => $request->title,
            'slug' => $this->createSlug($request->title),
            'thumbnail_url' => $thumbnail_url,
            'thumbnail_name' => $thumbnailOriginalName,
            'thumbnail_url_name' => $thumbnailUrlfileName,
            //Department Banner
            'department_banner_url' => $department_banner_url,
            'department_banner_name' => $departmentBannerOriginalName,
            'department_banner_url_name' => $departmentBannerUrlfileName,
            //Department Icon
            'icon_url' => $icon_url,
            'icon_name' => $iconOriginalName,
            'icon_url_name' => $iconUrlfileName,
            //Cover Image
            'cover_image_url' => $cover_url,
            'cover_image_name' => $coverOriginalName,
            'cover_image_url_name' => $final_cover_image_name,

            'priority' => $priority,
            'created_by' => Auth::user()->id,
            'status' => 1
        ];

        $record = Department::create($arr);

        if (isset($record)) {
            $messages = [
                array(
                    'message' => 'Record created successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.dept.index');
        } else {
            $messages = [
                array(
                    'message' => 'There is something wrong please try again',
                    'message_type' => 'warning'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Department::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('Department')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->title]])
            ->log('viewed');
        $data = array(
            'page_title' => 'Department',
            'p_title' => 'Department',
            'p_summary' => 'Show Department',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.dept.update', $record->id),
            'url' => route('manager.dept.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'application/x-www-form-urlencoded',
        );

        return view('manager.dept.show')->with($data);
    }

    public function showDepartmentAttachment($dept_id, $image_type)
    {
        $record = Department::where('id', $dept_id)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = '';
        if ($image_type == 'icon_url') {
            $path = Storage::disk('private')->path('Department/Icons/' . date('Y') . '/' . date('m') . '/' . $record->icon_url_name);
        } else if ($image_type == 'thumbnail_url') {
            $path = Storage::disk('private')->path('Department/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
        } else if ($image_type == 'department_banner_url') {
            $path = Storage::disk('private')->path('Department/DeptBanner/' . date('Y') . '/' . date('m') . '/' . $record->department_banner_url_name);
        } else {
            if ($image_type == 'cover_image_url') {
                $path = Storage::disk('private')->path('Department/CoverImage/' . date('Y') . '/' . date('m') . '/' . $record->cover_image_url_name);
            }
        }

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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Department::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $data = array(
            'page_title' => 'Department',
            'p_title' => 'Department',
            'p_summary' => 'Edit Department',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.dept.update', $record->id),
            'url' => route('manager.dept.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );
//        dd($data);
        return view('manager.dept.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Department::find($id);

        $this->validate($request, [
            'title' => 'required|unique:departments,title,' . $id,
            'thumbnail_url' => 'mimes:jpg,jpeg,png,gif|max:2048',
            'department_banner_url' => 'mimes:jpg,jpeg,png,gif|max:2048',
            'icon_url' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover_image_url' => 'mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'thumbnail_url.mimes' => 'Thumbnail File Type should be jpg, jpeg, png and gif',
            //Validation for Department Banner
            'department_banner_url.mimes' => 'Banner File Type should be jpg, jpeg, png and gif',
            //Validation for Department Icon
            'icon_url.mimes' => 'Icon File Type should be jpg, jpeg, png and gif',
            //Validation for Cover Image
            'cover_image_url.mimes' => 'Cover Image File Type should be jpg, jpeg, png and gif',
        ]);

//        $thumbnail_url = $record->thumbnail_url;
//        $thumbnail_url = $record->thumbnail_url;
        if ($request->hasFile('cropper_thumbnail')) {
            // Thumbnail
            $cThumbnail = $request->file('thumbnail_url');
            $thumbnailOriginalName = $cThumbnail->getClientOriginalName();
            $thumbnailName = pathinfo($thumbnailOriginalName, PATHINFO_FILENAME);
            $thumbnailExtension = $cThumbnail->getClientOriginalExtension();
            $thumbnailOriginalNameSluggy = Str::slug($thumbnailName);
            $cThumbnailFileName = time() . rand(0, 999999) . '-' . $thumbnailOriginalNameSluggy . '.' . $thumbnailExtension;

//            $img = date('Y') . '/' . date('m') . '/' . $cThumbnailFileName;
            $basePath = 'private';
            $departmentPath = $basePath . '/Department';
            $attachmentPath = $departmentPath . '/Thumbnail';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $thumbnailUrlfileName = $cThumbnailFileName;
            $thumbnail_url = $request->file('cropper_thumbnail')->storeAs(
                $monthlyAttachmentsPath,
                $cThumbnailFileName
            );
            //Unlink previous image
            if (isset($record) && $record->thumbnail_url) {
                $prevImage = Storage::disk('private')->path('Department/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['thumbnail_url'] = $thumbnail_url;
                $arr['thumbnail_name'] = $thumbnailOriginalName;
                $arr['thumbnail_url_name'] = $thumbnailUrlfileName;
            }
        } else {
            $thumbnail_url = $record->thumbnail_url;
            $thumbnailOriginalName = $record->thumbnail_name;
            $thumbnailUrlfileName = $record->thumbnail_url_name;
        }


//        $department_banner_url = $record->department_banner_url;
        if ($request->hasFile('cropper_banner')) {
            // Banner
            $cDepartmentBanner = $request->file('department_banner_url');
            $departmentBannerOriginalName = $cDepartmentBanner->getClientOriginalName();
            $departmentBannerName = pathinfo($departmentBannerOriginalName, PATHINFO_FILENAME);
            $departmentBannerExtension = $cDepartmentBanner->getClientOriginalExtension();
            $departmentBannerOriginalNameSluggy = Str::slug($departmentBannerName);
            $cDepartmentBannerFileName = time() . rand(0, 999999) . '-' . $departmentBannerOriginalNameSluggy . '.' . $departmentBannerExtension;
//            $banner = date('Y') . '/' . date('m') . '/' . $cBannerFileName;
            $basePath = 'private';
            $bannerPath = $basePath . '/Department';
            $attachmentPath = $bannerPath . '/DeptBanner';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');

            $departmentBannerUrlfileName = $cDepartmentBannerFileName;
            $department_banner_url = $request->file('cropper_banner')->storeAs(
                $monthlyAttachmentsPath,
                $cDepartmentBannerFileName
            );
            //Unlink previous image
            if (isset($record) && $record->department_banner_url) {
                $prevImage = Storage::disk('private')->path('Department/DeptBanner/' . date('Y') . '/' . date('m') . '/' . $record->department_banner_url_name);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['department_banner_url'] = $department_banner_url;
                $arr['department_banner_name'] = $departmentBannerOriginalName;
                $arr['department_banner_url_name'] = $departmentBannerUrlfileName;
            }
        } else {
            $department_banner_url = $record->department_banner_url;
            $departmentBannerOriginalName = $record->department_banner_name;
            $departmentBannerUrlfileName = $record->department_banner_url_name;
        }


        if ($request->hasFile('icon_url')) {
            // Icon
            $cIcon = $request->file('icon_url');
            $iconOriginalName = $cIcon->getClientOriginalName();
            $iconName = pathinfo($iconOriginalName, PATHINFO_FILENAME);
            $iconExtension = $cIcon->getClientOriginalExtension();
            $iconOriginalNameSluggy = Str::slug($iconName);
            $cIconFileName = time() . rand(0, 999999) . '-' . $iconOriginalNameSluggy . '.' . $iconExtension;
            $basePath = 'private';
            $iconPath = $basePath . '/Department';
            $attachmentPath = $iconPath . '/Icons';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $iconUrlfileName = $cIconFileName;
            $icon_url = $request->file('icon_url')->storeAs(
                $monthlyAttachmentsPath,
                $cIconFileName
            );
            //Unlink previous image
            if (isset($record) && $record->icon_url) {
                $prevImage = Storage::disk('private')->path('Department/Icons/' . date('Y') . '/' . date('m') . '/' . $record->icon_url_name);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['icon_url'] = $icon_url;
                $arr['icon_name'] = $iconOriginalName;
                $arr['icon_url_name'] = $iconUrlfileName;
            }
        } else {
            $icon_url = $record->icon_url;
            $iconOriginalName = $record->icon_name;
            $iconUrlfileName = $record->icon_url_name;
        }

        // Cover Image Icon
        $cover_url = '';
        $coverOriginalName = '';
        $final_cover_image_name = '';
        if ($request->hasFile('cover_image_url')) {
            // Icon
            $cover_file = $request->file('cover_image_url');
            $coverOriginalName = $cover_file->getClientOriginalName();
            $cover_name = pathinfo($coverOriginalName, PATHINFO_FILENAME);
            $cover_extension = $cover_file->getClientOriginalExtension();
            $coverOriginalNameSluggy = Str::slug($cover_name);

            $basePath = 'private';
            $iconPath = $basePath . '/Department';
            $attachmentPath = $iconPath . '/CoverImage';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');

            $final_cover_image_name = time() . rand(0, 999999) . '-' . $coverOriginalNameSluggy . '.' . $cover_extension;

            $cover_url = $request->file('cover_image_url')->storeAs(
                $monthlyAttachmentsPath,
                $final_cover_image_name
            );

            //Unlink previous Cover Image
            if (isset($record) && $record->cover_image_url) {
                $prevImage = Storage::disk('private')->path('Department/CoverImage/' . date('Y') . '/' . date('m') . '/' . $record->cover_image_url_name);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['cover_image_url'] = $cover_url;
                $arr['cover_image_name'] = $coverOriginalName;
                $arr['cover_image_url_name'] = $final_cover_image_name;
            }
        } else {
            $cover_url = $record->cover_image_url;
            $coverOriginalName = $record->cover_image_name;
            $final_cover_image_name = $record->cover_image_url_name;
        }

        $arr = [
            'description' => $request->description,
            'title' => $request->title,
            //Thumbnail
            'thumbnail_url' => $thumbnail_url,
            'thumbnail_name' => $thumbnailOriginalName,
            'thumbnail_url_name' => $thumbnailUrlfileName,

            //Department Banner
            'department_banner_url' => $department_banner_url,
            'department_banner_name' => $departmentBannerOriginalName,
            'department_banner_url_name' => $departmentBannerUrlfileName,
            //Icon
            'icon_url' => $icon_url,
            'icon_name' => $iconOriginalName,
            'icon_url_name' => $iconUrlfileName,
            //Cover Image
            'cover_image_url' => $cover_url,
            'cover_image_name' => $coverOriginalName,
            'cover_image_url_name' => $final_cover_image_name,

            'updated_by' => Auth::user()->id,
            'status' => 1
        ];

        $update_dept = $record->update($arr);

        if ($update_dept) {
            $messages = [
                array(
                    'message' => 'Record updated successfully',
                    'message_type' => 'success'
                ),
            ];

            Session::flash('messages', $messages);

            return redirect()->route('manager.dept.index');
        } else {
            $messages = [
                array(
                    'message' => 'There is something wrong please try again!',
                    'message_type' => 'warning'
                ),
            ];

            Session::flash('messages', $messages);

            return redirect()->back();
        }
    }

    public function updateStatus(string $id)
    {
        //get previous department record
        $record = Department::where('id', $id)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        //If previous record has status zero & the current entered status is also zero then show message
        if ($record->status == 0) {
            $status = 1;
            //set record status value to 1
            $arr = [
                'status' => $status,
                'updated_by' => Auth::user()->id,
            ];

            $record->update($arr);

            $messages = [
                array(
                    'message' => 'Record Updated successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.dept.index');

        } else {
            $status = 0;
            //set record status value to 0
            $arr = [
                'status' => $status,
                'updated_by' => Auth::user()->id,
            ];

            $record->update($arr);

            $messages = [
                array(
                    'message' => 'Record Updated successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.dept.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Department::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
//     Thumbnail Image Remove
        $image_path_thumbnail = Storage::disk('private')->path('Department/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
        if (File::exists($image_path_thumbnail)) {
            File::delete($image_path_thumbnail);
        }

        //        Department Image Remove
        $image_path_banner = Storage::disk('private')->path('Department/DeptBanner/' . date('Y') . '/' . date('m') . '/' . $record->department_banner_url_name);
        if (File::exists($image_path_banner)) {
            File::delete($image_path_banner);
        }
        $record->delete();

        //        Department Icon Remove
        $image_path_icon = Storage::disk('private')->path('Department/Icons/' . date('Y') . '/' . date('m') . '/' . $record->icon_url_name);
        if (File::exists($image_path_icon)) {
            File::delete($image_path_icon);
        }

        //        Department Cover Image
        $image_path_cover = Storage::disk('private')->path('Department/CoverImage/' . date('Y') . '/' . date('m') . '/' . $record->cover_image_url_name);
        if (File::exists($image_path_cover)) {
            File::delete($image_path_cover);
        }
        $record->delete();
        $messages = [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.dept.index');
    }


    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title);
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 100000000; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Department::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Department',
            'p_title' => 'Department',
            'p_summary' => 'Show Department',
            'p_description' => null,
            'url' => route('manager.dept.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.dept.activity')->with($data);
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
            ->where('activity_log.subject_type', Department::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Department::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Department::class)
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
            'page_title' => 'Department',
            'p_title' => 'Department',
            'p_summary' => 'Show Department Trashed Activity',
            'p_description' => null,
            'url' => route('manager.dept.index'),
            'url_text' => 'View All',
        );
        return view('manager.dept.trash')->with($data);
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
            ->where('activity_log.subject_type', Department::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Department::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Department::class)
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

    public function swapUp($id)
    {
        // current record
        $dept = Department::find($id);

        // get priority of previous record
        $priority = Department::select('priority as prev')->where('priority', '>', $dept['priority'])->first();
//       dd($priority);
        // if no previous record exists
        if (!isset($priority->prev)) {
            return redirect()->back()->with('message', 'There is no previous record');
        }

        // record with priority to be swapped
        $deptSwap = Department::where('priority', $priority->prev)->first();

        // get the priorities
        $priority = $dept['priority'];
        //dd($priority);

        $swapPriority = $deptSwap['priority'];
        //dd($swapPriority);

        $deptUpdate['priority'] = null;
        $deptSwapUpdate['priority'] = null;

        $deptUpdated = $dept->update($deptUpdate);
        $deptSwapped = $deptSwap->update($deptSwapUpdate);

        // swap the priorities
        $deptUpdate2['priority'] = $swapPriority;
        $deptSwapUpdate2['priority'] = $priority;


        if ($dept->update($deptUpdate2)) {
            $deptSwap = $deptSwap->update($deptSwapUpdate2);
        }

        return redirect()->route('manager.dept.index');
    }

    public function swapDown($id)
    {
        // current record
        $dept = Department::find($id);

        // get priority of next record
        $priority = Department::selectRaw('MAX(priority) as next')->where('priority', '<', $dept['priority'])->first();

        // if no next record exists
        if (!isset($priority->next)) {
            return redirect()->back()->with('message', 'There is no next record');
        }

        // record with priority to be swapped
        $deptSwap = Department::where('priority', $priority->next)->first();

        // get the priorities
        $priority = $dept['priority'];
        $swapPriority = $deptSwap['priority'];


        $deptUpdate['priority'] = null;
        $deptSwapUpdate['priority'] = null;

        $deptUpdated = $dept->update($deptUpdate);
        $deptSwapped = $deptSwap->update($deptSwapUpdate);

        // swap the priorities
        $deptUpdate2['priority'] = $swapPriority;
        $deptSwapUpdate2['priority'] = $priority;


        if ($dept->update($deptUpdate2)) {
            $glimpseSwap = $deptSwap->update($deptSwapUpdate2);
        }

        return redirect()->route('manager.dept.index');
    }

    public function ckImage($filename)

    {
        $storage = Storage::disk('private');
        $path = 'Department/Ckeditor/' . $filename;
        if ($storage->exists($path)) {
            $file = $storage->get($path);
            $type = $storage->mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        } else {
            abort(404); // Or handle the case when the file is not found
        }
    }

    public function ckeditorUpload(Request $request)
    {
        $this->validate($request, [
            'upload' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();
            $folderPath = 'Department/Ckeditor/';
            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;
            $file = $folderPath . $filenametostore;
            //Upload File
            Storage::disk('private')->put($file, file_get_contents($request->upload));

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');

//            $url = Storage::disk('private')->path('Department/Ckeditor/' . $filenametostore);
            $url = route('dept.ckImage', ['filename' => $filenametostore]);

            $msg = 'Image successfully uploaded';

            $re = '<script>window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "' . $url . '", "' . $msg . '")</script>';
//            dd($re);
            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;

        }
    }


}
