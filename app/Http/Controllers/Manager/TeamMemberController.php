<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\Department;
use App\Models\Manager\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Spatie\Activitylog\Models\Activity;

class TeamMemberController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_team_member-list', ['only' => ['index', 'getIndex']]);
        $this->middleware('permission:manager_team_member-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manager_team_member-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manager_team_member-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_team_member-activity-log', ['only' => ['getActivity', 'getActivityLog']]);
        $this->middleware('permission:manager_team_member-activity-log-trash', ['only' => ['getTrashActivity', 'getTrashActivityLog']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'page_title' => 'Team Member',
            'p_title' => 'Team Member',
            's_title' => 'Team Member',
            'p_summary' => 'List of Team Member',
            'p_description' => null,
            'url' => route('manager.team-member.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.team-member-activity-trash'),
            'trash_text' => 'View Trash',
        ];
//        dd($departments);
        return view('manager.team_member.index')->with($data);
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
        $totalRecords = TeamMember::with('DptID')
            ->count();


        $totalRecordswithFilter = TeamMember::with('DptID')
            ->where(function ($q) use ($searchValue) {
                $q->where('team_members.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('team_members.designation', 'like', '%' . $searchValue . '%')
                    ->orWhere('team_members.phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    });
            })
            ->count();

        // Fetch records
        $records = TeamMember::with('DptID')
            ->where(function ($q) use ($searchValue) {
                $q->where('team_members.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('team_members.designation', 'like', '%' . $searchValue . '%')
                    ->orWhere('team_members.phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    });;
            })
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $name = $record->name;
            $designation = $record->designation;
            $department_id = (isset($record->DptID->title) ? $record->DptID->title : '');
            $phone_no = $record->phone_no;
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "designation" => $designation,
                "phone_no" => $phone_no,
                "department_id" => $department_id,
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

    public function getDepartmentIndexSelect(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Department::where('departments.title', 'like', '%' . $search . '%')
                ->where('status', 1)
                ->get();
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'page_title' => 'Team Member',
            'p_title' => 'Team Member',
            'p_summary' => 'Add Team Member',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.team-member.store'),
            'url' => route('manager.team-member.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );

        return view('manager.team_member.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image_url' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'name' => 'required|unique:team_members|string',
            'department_id' => 'required',
        ], [
            'image_url.required' => 'Image is required',
            'image_url.mimes' => 'Image Type will be Jpg, Jpeg, Png and Gif',
            'department_id.required' => 'Department is Required'
        ]);

        $image_url = null;
        $imageOriginalName = null;
        $imageUrlfileName = null;
        if ($request->hasFile('cropper_image')) {
            // Profile Image
            $cImage = $request->file('image_url');
            $imageOriginalName = $cImage->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time() . rand(0, 999999) . '-' . $imageOriginalNameSluggy . '.' . $imageExtension;
            $basePath = 'private';
            $imagePath = $basePath . '/Team';
            $attachmentPath = $imagePath . '/Members';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $imageUrlfileName = $cImageFileName;
            $image_url = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
        }

        $arr = [
            'name' => $request->name,
            'designation' => $request->designation,
            'slug' => $this->createSlug($request->name),
            'address' => $request->address,
            'phone_no' => $request->phone_no,
            'department_id' => $request->department_id,
            'description' => $request->description,
            'education' => $request->education,
            'employment' => $request->employment,
            'membership' => $request->membership,
            'sitting_time' => $request->sitting_time,
            'speciality' => $request->speciality,
            'image_url' => $image_url,
            'image_name' => $imageOriginalName,
            'image_url_name' => $imageUrlfileName,
            'created_by' => Auth::user()->id,
        ];

        $record = TeamMember::create($arr);

        if (isset($record)) {
            $messages = [
                array(
                    'message' => 'Record created successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.team-member.index');
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
        $record = TeamMember::with('DptID')->where('id', $id)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        // Add activity logs
        $user = Auth::user();
        activity('TeamMember')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');

        $data = array(
            'page_title' => 'Team Member',
            'p_title' => 'Team Member',
            'p_summary' => 'Show Team Member',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.team-member.update', $record->id),
            'url' => route('manager.team-member.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data',
        );

        return view('manager.team_member.show')->with($data);
    }

    public function showTeamMemberImages($team_id, $image_type)
    {
        $record = TeamMember::where('id', $team_id)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = '';
        if ($image_type == 'image_url') {
            $path = Storage::disk('private')->path('Team/Members/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);
        } else {
            $messages = [
                array(
                    'message' => 'Unable to find image there is something wrong please try again!',
                    'message_type' => 'warning'
                ),
            ];

            Session::flash('messages', $messages);

            return redirect()->back();
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
        $record = TeamMember::with('DptID')->where('id', $id)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('TeamMember')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');

        $data = array(
            'page_title' => 'Team Member',
            'p_title' => 'Team Member',
            'p_summary' => 'Edit Team Member',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.team-member.update', $record->id),
            'url' => route('manager.team-member.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data',
        );
//        dd($data);
        return view('manager.team_member.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = TeamMember::find($id);

        $this->validate($request, [
            'name' => 'required|unique:team_members,name,' . $id,
            'image_url' => 'mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'image_url.mimes' => 'Image Type will be Jpg, Jpeg, Png and Gif',
            'department_id.required' => 'Department is Required'
        ]);

        if ($request->hasFile('cropper_image')) {
            // Image
            $cImage = $request->file('image_url');
            $imageOriginalName = $cImage->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time() . rand(0, 999999) . '-' . $imageOriginalNameSluggy . '.' . $imageExtension;

            $basePath = 'private';
            $teamPath = $basePath . '/Team';
            $attachmentPath = $teamPath . '/Members';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $imageUrlfileName = $cImageFileName;
            $image_url = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
            //Unlink previous image
            if (isset($record) && $record->image_url) {
                $prevImage = Storage::disk('private')->path('Team/Members/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['image_url'] = $image_url;
                $arr['image_name'] = $imageOriginalName;
                $arr['image_url_name'] = $imageUrlfileName;
            }
        } else {
            $image_url = $record->image_url;
            $imageOriginalName = $record->image_name;
            $imageUrlfileName = $record->image_url_name;
        }

        $arr = [
            'name' => $request->name,
            'designation' => $request->designation,
            'address' => $request->address,
            'phone_no' => $request->phone_no,
            'department_id' => $request->department_id,
            'description' => $request->description,
            'education' => $request->education,
            'employment' => $request->employment,
            'membership' => $request->membership,
            'sitting_time' => $request->sitting_time,
            'speciality' => $request->speciality,
            'image_url' => $image_url,
            'image_name' => $imageOriginalName,
            'image_url_name' => $imageUrlfileName,
            'updated_by' => Auth::user()->id,
        ];
//        dd($arr);
        $record->update($arr);
        $messages = [
            array(
                'message' => 'Record updated successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('manager.team-member.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = TeamMember::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
//    Image Remove
        $image_path = Storage::disk('private')->path('Team/Members/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);
        if (File::exists($image_path)) {
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

        return redirect()->route('manager.team-member.index');
    }


    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Team Member',
            'p_title' => 'Team Member',
            'p_summary' => 'Show Team Member',
            'p_description' => null,
            'url' => route('manager.team-member.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.team_member.activity')->with($data);
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
            ->where('activity_log.subject_type', TeamMember::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', TeamMember::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', TeamMember::class)
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
            'page_title' => 'Team Members',
            'p_title' => 'Team Members',
            'p_summary' => 'Show Team Members Trashed Activity',
            'p_description' => null,
            'url' => route('manager.team-member.index'),
            'url_text' => 'View All',
        );
        return view('manager.team_member.trash')->with($data);
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
            ->where('activity_log.subject_type', TeamMember::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', TeamMember::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', TeamMember::class)
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
        return TeamMember::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function ckImage($filename)
    {
        $storage = Storage::disk('private');
        $path = 'Team/Ckeditor/' . $filename;

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
            $folderPath = 'Team/Ckeditor/';
            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;
            $file = $folderPath . $filenametostore;
            //Upload File
//            $request->file('upload')->storeAs('public/post/gallery/', $filenametostore);
            Storage::disk('private')->put($file, file_get_contents($request->upload));

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
//            $url = storage_path('app/private/Team/Ckeditor/'.$filenametostore);
            $url = Storage::disk('private')->path('Team/Ckeditor/' . $filenametostore);
            $url = route('team-member.ckImage', ['filename' => $filenametostore]);

            $msg = 'Image successfully uploaded';

            $re = '<script>window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "' . $url . '", "' . $msg . '")</script>';

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }
}
