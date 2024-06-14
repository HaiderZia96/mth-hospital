<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\Achievement;
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

class AchievementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_achievement_award-list', ['only' => ['index', 'getIndex']]);
        $this->middleware('permission:manager_achievement_award-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manager_achievement_award-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manager_achievement_award-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_achievement_award-activity-log', ['only' => ['getActivity', 'getActivityLog']]);
        $this->middleware('permission:manager_achievement_award-activity-log-trash', ['only' => ['getTrashActivity', 'getTrashActivityLog']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'page_title' => 'Achievements',
            'p_title' => 'Achievements',
            's_title' => 'Achievements',
            'p_summary' => 'List of Achievements',
            'p_description' => null,
            'url' => route('manager.achievement.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.achievement-activity-trash'),
            'trash_text' => 'View Trash',
        ];

        return view('manager.achievement.index')->with($data);
    }

    public function getAchievementDepartmentIndexSelect(Request $request)
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
        $totalRecords = Achievement::with('DptID')
            ->count();


        $totalRecordswithFilter = Achievement::with('DptID')
            ->where(function ($q) use ($searchValue) {
                $q->where('achievements.name', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    });
            })
            ->count();

        // Fetch records
        $records = Achievement::with('DptID')
            ->where(function ($q) use ($searchValue) {
                $q->where('achievements.name', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    });
            })
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $name = $record->name;
            $department_id = ($record->DptID->title ?? '');

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "department_id" => $department_id,
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
            'page_title' => 'Achievements',
            'p_title' => 'Achievements',
            'p_summary' => 'Add Achievements',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.achievement.store'),
            'url' => route('manager.achievement.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );

        return view('manager.achievement.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image_url' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'name' => 'required|unique:achievements|string',
            'department_id' => 'required',
        ], [
            'image_url.required' => 'Image is Required',
            'image_url.mimes' => 'Image Type should be jpg, jpeg, png and gif',
            'department_id.required' => 'Department is Required',
        ]);

        $image_url = null;
        if ($request->hasFile('cropper_image')) {
            // Profile Image
            $cImage = $request->file('image_url');
            $imageOriginalName = $cImage->getClientOriginalName();

            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time() . rand(0, 999999) . '-' . $imageOriginalNameSluggy . '.' . $imageExtension;

            $basePath = 'private';
            $imagePath = $basePath . '/Achievements';
//            $attachmentPath = $imagePath . '/Members';
            $monthlyAttachmentsPath = $imagePath . '/' . date('Y') . '/' . date('m');
            $imageUrlfileName = $cImageFileName;
            $pathImage = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
        }

        $arr = [
            'name' => $request->name,
            'slug' => $this->createSlug($request->name),
            'department_id' => $request->department_id,
            'description' => $request->description,
            'image_url' => $pathImage,
            'image_name' => $imageOriginalName,
            'image_url_name' => $imageUrlfileName,
            'created_by' => Auth::user()->id,
        ];

        $record = Achievement::create($arr);

        if (isset($record)) {
            $messages = [
                array(
                    'message' => 'Record created successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.achievement.index');
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
        $record = Achievement::with('DptID')->where('id', $id)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        // Add activity logs
        $user = Auth::user();
        activity('Achievement')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');

        $data = array(
            'page_title' => 'Achievement',
            'p_title' => 'Achievement',
            'p_summary' => 'Show Achievement',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.achievement.update', $record->id),
            'url' => route('manager.achievement.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'application/x-www-form-urlencoded',
        );

        return view('manager.achievement.show')->with($data);
    }

    public function showAchievementImages($id, $image_type)
    {
        $record = Achievement::where('id', $id)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = '';
        if ($image_type == 'image_url') {
            $path = Storage::disk('private')->path('Achievements/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);
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
        $record = Achievement::with('DptID')->where('id', $id)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('Achievement')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');

        $data = array(
            'page_title' => 'Achievement',
            'p_title' => 'Achievement',
            'p_summary' => 'Edit Achievement',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.achievement.update', $record->id),
            'url' => route('manager.achievement.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data',
        );

        return view('manager.achievement.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Achievement::where('id', $id)->first();

        $this->validate($request, [
            'name' => 'required|unique:team_members,name,' . $id,
            'image_url' => 'mimes:jpg,jpeg,png,gif|max:2048',
            'department_id' => 'required',
        ], [
            'image_url.mimes' => 'Image Type should be jpg, jpeg, png and gif',
            'department_id.required' => 'Department is Required',
        ]);

        if ($request->hasFile('cropper_image')) {
            // Image
            $cImage = $request->file('image_url');
            $imageOriginalName = $cImage->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time() . rand(0, 999999) . '-' . $imageOriginalNameSluggy . '.' . $imageExtension;
//            $image = date('Y') . '/' . date('m') . '/' . $cImageFileName;
            $basePath = 'private';
            $teamPath = $basePath . '/Achievements';
//            $attachmentPath = $teamPath . '/Members';
            $monthlyAttachmentsPath = $teamPath . '/' . date('Y') . '/' . date('m');
            $imageUrlfileName = $cImageFileName;
            $pathImage = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
            //Unlink previous image
            if (isset($record) && $record->image_url) {
                $prevImage = Storage::disk('private')->path('Achievements/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['image_url'] = $pathImage;
                $arr['image_name'] = $imageOriginalName;
                $arr['image_url_name'] = $imageUrlfileName;
            }
        } else {
            $pathImage = $record->image_url;
            $imageOriginalName = $record->image_name;
            $imageUrlfileName = $record->image_url_name;
        }


        $arr = [
            'name' => $request->name,
            'slug' => $this->createSlug($request->name),
            'department_id' => $request->department_id,
            'description' => $request->description,
            'image_url' => $pathImage,
            'image_name' => $imageOriginalName,
            'image_url_name' => $imageUrlfileName,
            'updated_by' => Auth::user()->id,
        ];

        $updated_record = $record->update($arr);

        if (isset($updated_record)) {
            $messages = [
                array(
                    'message' => 'Record updated successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);
            return redirect()->route('manager.achievement.index');
        } else {
            $messages = [
                array(
                    'message' => 'There is something wrong please try again.',
                    'message_type' => 'warning'
                ),
            ];

            Session::flash('messages', $messages);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Achievement::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        //    Image Remove
        $image_path = Storage::disk('private')->path('Achievements/' . date('Y') . '/' . date('m') . '/' . $record->image_url_name);
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

        return redirect()->route('manager.achievement.index');
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
        return Achievement::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Achievements',
            'p_title' => 'Achievements',
            'p_summary' => 'Show Achievements',
            'p_description' => null,
            'url' => route('manager.achievement.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.achievement.activity')->with($data);
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
            ->where('activity_log.subject_type', Achievement::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Achievement::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Achievement::class)
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
            'page_title' => 'Achievements',
            'p_title' => 'Achievements',
            'p_summary' => 'Show Achievements Trashed Activity',
            'p_description' => null,
            'url' => route('manager.achievement.index'),
            'url_text' => 'View All',
        );
        return view('manager.achievement.trash')->with($data);
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
            ->where('activity_log.subject_type', Achievement::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Achievement::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Achievement::class)
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

    public function ckImage($filename)

    {
        $storage = Storage::disk('private');
        $path = 'Achievements/Ckeditor/' . $filename;
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
            $folderPath = 'Achievements/Ckeditor/';
            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;
            $file = $folderPath . $filenametostore;
            //Upload File
            Storage::disk('private')->put($file, file_get_contents($request->upload));

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');

//            $url = Storage::disk('private')->path('Achievements/Ckeditor/' . $filenametostore);
            $url = route('achievement.ckImage', ['filename' => $filenametostore]);
            $msg = 'Image successfully uploaded';

            $re = '<script>window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "' . $url . '", "' . $msg . '")</script>';

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }
}
