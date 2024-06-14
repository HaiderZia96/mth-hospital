<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Manager\Conference;

use App\Models\Manager\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class ConferenceController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manager_event_conference-list', ['only' => ['index', 'getIndex']]);
        $this->middleware('permission:manager_event_conference-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manager_event_conference-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manager_event_conference-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_event_conference-activity-log', ['only' => ['getActivity', 'getActivityLog']]);
        $this->middleware('permission:manager_event_conference-activity-log-trash', ['only' => ['getTrashActivity', 'getTrashActivityLog']]);
        $this->middleware('permission:manager_event_conference-swap', ['only' => ['swapUp', 'swapDown']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'page_title' => 'Conferences',
            'p_title'=>'Conferences',
            's_title' => 'Conferences',
            'p_summary' => 'List of Conferences',
            'p_description' => null,
            'url' => route('manager.conference.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.conference-activity-trash'),
            'trash_text' => 'View Trash',
        );

        return view('manager.conference.index')->with($data);
    }
    public function getIndex(Request $request){
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
        $totalRecords = Conference::select('count(*) as allcount')
            ->where('conferences.name', 'like', '%' .$searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();
        $totalRecordswithFilter = Conference::where('conferences.name', 'like', '%' .$searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();
        // Fetch records
        $records = Conference::orderBy('priority', 'DESC')
            ->where('conferences.name', 'like', '%' .$searchValue . '%')
            ->select('conferences.*')
//            ->orderBy('priority', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//        dd($records);

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $name = $record->name;
            if(isset($record['DptID']['title'])){
                $department_id = $record['DptID']['title'];
            }
            else{
                $department_id = "";
            }
            $conference_date = $record->conference_date;
            $venue = $record->venue;

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "department_id" => $department_id,
                "conference_date" => $conference_date,
                "venue" => $venue,
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
            'page_title' => 'Conference',
            'p_title' => 'Conference',
            'p_summary' => 'Add Conference',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.conference.store'),
            'url' => route('manager.conference.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );
        return view('manager.conference.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image_url' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'name' => 'required|unique:conferences|string',
            'department_id' => 'required',
            'conference_date' => 'required',
            'venue' => 'required',
        ]);

        // Image

        $image_url = null;
        $imageOriginalName = null;
        $imageUrlfileName = null;
        if ($request->hasFile('cropper_image')) {
            // Image
            $cImage = $request->file('image_url');
            $imageOriginalName = $cImage->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time()   . rand(0, 999999) . '-' . $imageOriginalNameSluggy.'.'.$imageExtension;

            $basePath = 'private';
            $imagePath = $basePath . '/Conference';
            $attachmentPath = $imagePath . '/Image';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $imageUrlfileName = $cImageFileName;
            $image_url = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
        }


        // get max priority from db
        $max = Conference::selectRaw('MAX(priority) as max')->first()->max;
        //  dd($max);
        // get priority
        if (isset($max)) {
            $priority = $max + 1;
        } // when no records already exist
        else {
            $priority = 1;
        }

        // set service priority
        $input['priority'] = $priority;

        $arr = [
            'description' => $request->description,
            'name' => $request->name,
            'slug' => $this->createSlug($request->name),
            'conference_date' => $request->conference_date,
            'department_id' => $request->department_id,
            'image_url' => $image_url,
            'image_name' => $imageOriginalName,
            'image_url_name' => $imageUrlfileName,
            'venue' => $request->venue,
            'conference_workshop' => $request->conference_workshop,
            'priority' => $priority,
            'created_by' => Auth::user()->id,
        ];
//        dd($arr);
        $record = Conference::create($arr);
        if ($record) {
            $messages = [
                array(
                    'message' => 'Record created successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.conference.index');
        } else {
            abort(404, 'NOT FOUND');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Conference::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('Conference')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');
        $department = Department::select( 'departments.id as department_id','departments.title as department_name')
            ->where('departments.id', '=', $record->department_id)
            ->first();
        if (empty($department)) {
            abort(404, 'NOT FOUND');
        }
//        dd($department);
        $data = array(
            'page_title' => 'Conference',
            'p_title' => 'Conference',
            'p_summary' => 'Show Conference',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.conference.update', $record->id),
            'url' => route('manager.conference.index'),
            'url_text' => 'View All',
            'data' => $record,
            'department' => $department,
            'enctype' => 'application/x-www-form-urlencoded',
        );
//        dd($data);
        return view('manager.conference.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Conference::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('Conference')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');
        $department = Department::select( 'departments.id as department_id','departments.title as department_name')
            ->where('departments.id', '=', $record->department_id)
            ->first();
//        dd($department);
        if (empty($department)) {
            abort(404, 'NOT FOUND');
        }
        $data = array(
            'page_title' => 'Conference',
            'p_title' => 'Conference',
            'p_summary' => 'Edit Conference',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.conference.update', $record->id),
            'url' => route('manager.conference.index'),
            'url_text' => 'View All',
            'data' => $record,
            'department' => $department,
            'enctype' => 'multipart/form-data',
        );
//        dd($data);
        return view('manager.conference.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $record = Conference::find($id);

        $this->validate($request, [
            'name' => 'required|unique:conferences,name,' . $id,
            'department_id' => 'required',
            'conference_date' => 'required',
            'venue' => 'required',
        ]);

//        $image_url = $record->image_url;
        if ($request->hasFile('cropper_image')) {
            // Image
            $cImage = $request->file('image_url');
            $imageOriginalName = $cImage->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time()   . rand(0, 999999) . '-' . $imageOriginalNameSluggy.'.'.$imageExtension;
//            $img = date('Y') . '/' . date('m') . '/' . $cImageFileName;
            $basePath = 'private';
            $departmentPath = $basePath . '/Conference';
            $attachmentPath = $departmentPath . '/Image';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $imageUrlfileName = $cImageFileName;
            $image_url = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
            //Unlink previous image
            if (isset($record) && $record->image_url) {
                $prevImage = Storage::disk('private')->path('Conference/Image/'.$record->image_url);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['image_url'] = $image_url;
                $arr['image_name'] = $imageOriginalName;
                $arr['image_url_name'] = $imageUrlfileName;
            }
        }
        else{
            $image_url = $record->image_url;
            $imageOriginalName = $record->image_name;
            $imageUrlfileName = $record->image_url_name;
        }



        $arr = [
            'description' => $request->description,
            'name' => $request->name,
            'venue' => $request->venue,
            'conference_date' => $request->conference_date,
            'conference_workshop' => $request->conference_workshop,
            'department_id' => $request->department_id,
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
        return redirect()->route('manager.conference.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Conference::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Thumbnail Image Remove
        $image_path = Storage::disk('private')->path('Conference/Image/' . $record->image_url);
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

        return redirect()->route('manager.conference.index');
    }
    public function getDepartmentIndexSelect(Request $request){
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Department::select('departments.id as department_id', 'departments.title as department_name')
                ->where(function ($q) use ($search) {
                    $q->where('departments.title', 'like', '%' . $search . '%');
                })
                ->get();
        }

        return response()->json($data);
    }
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title);
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 100000000; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Conference::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Conference',
            'p_title' => 'Conference',
            'p_summary' => 'Show Conference',
            'p_description' => null,
            'url' => route('manager.conference.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.conference.activity')->with($data);
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
            ->where('activity_log.subject_type', Conference::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Conference::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Conference::class)
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
            'page_title' => 'Conference',
            'p_title' => 'Conference',
            'p_summary' => 'Show Conference Trashed Activity',
            'p_description' => null,
            'url' => route('manager.conference.index'),
            'url_text' => 'View All',
        );
        return view('manager.conference.trash')->with($data);
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
            ->where('activity_log.subject_type', Conference::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Conference::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Conference::class)
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
        $conference = Conference::find($id);

        // get priority of previous record
        $priority = Conference::select('priority as prev')->where('priority', '>', $conference['priority'])->first();
//       dd($priority);
        // if no previous record exists
        if (!isset($priority->prev)) {
            return redirect()->back()->with('message', 'There is no previous record');
        }

        // record with priority to be swapped
        $conferenceSwap = Conference::where('priority', $priority->prev)->first();

        // get the priorities
        $priority = $conference['priority'];
        //dd($priority);

        $swapPriority = $conferenceSwap['priority'];
        //dd($swapPriority);

        $conferenceUpdate['priority'] = null;
        $conferenceSwapUpdate['priority'] = null;

        $conferenceUpdated = $conference->update($conferenceUpdate);
        $conferenceSwapped = $conferenceSwap->update($conferenceSwapUpdate);

        // swap the priorities
        $conferenceUpdate2['priority'] = $swapPriority;
        $conferenceSwapUpdate2['priority'] = $priority;


        if ($conference->update($conferenceUpdate2)) {
            $conferenceSwap = $conferenceSwap->update($conferenceSwapUpdate2);
        }

        return redirect()->route('manager.conference.index');
    }

    public function swapDown($id)
    {
        // current record
        $conference = Conference::find($id);

        // get priority of next record
        $priority = Conference::selectRaw('MAX(priority) as next')->where('priority', '<', $conference['priority'])->first();

        // if no next record exists
        if (!isset($priority->next)) {
            return redirect()->back()->with('message', 'There is no next record');
        }

        // record with priority to be swapped
        $conferenceSwap = Conference::where('priority', $priority->next)->first();

        // get the priorities
        $priority = $conference['priority'];
        $swapPriority = $conferenceSwap['priority'];


        $conferenceUpdate['priority'] = null;
        $conferenceSwapUpdate['priority'] = null;

        $conferenceUpdated = $conference->update($conferenceUpdate);
        $conferenceSwapped = $conferenceSwap->update($conferenceSwapUpdate);

        // swap the priorities
        $conferenceUpdate2['priority'] = $swapPriority;
        $conferenceSwapUpdate2['priority'] = $priority;


        if ($conference->update($conferenceUpdate2)) {
            $glimpseSwap = $conferenceSwap->update($conferenceSwapUpdate2);
        }

        return redirect()->route('manager.conference.index');
    }
    public function ckImage($filename)

    {
        $storage = Storage::disk('private');
        $path = 'Conference/Ckeditor/' . $filename;
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

        if($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();
            $folderPath = 'Conference/Ckeditor/';
            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;
            $file = $folderPath . $filenametostore;
            //Upload File
            Storage::disk('private')->put($file, file_get_contents($request->upload));

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');

//            $url = Storage::disk('private')->path('Service/Ckeditor/' . $filenametostore);
            $url = route('conference.ckImage', ['filename' => $filenametostore]);

            $msg = 'Image successfully uploaded';

            $re ='<script>window.parent.CKEDITOR.tools.callFunction('.$CKEditorFuncNum.', "'.$url.'", "'.$msg.'")</script>';

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }
}
