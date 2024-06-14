<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Manager\Attachment;
use App\Models\Manager\Department;
use App\Models\Manager\Research;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ResearchController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_research_research-list', ['only' => ['index', 'getIndex']]);
        $this->middleware('permission:manager_research_research-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manager_research_research-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manager_research_research-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_research_research-activity-log', ['only' => ['getActivity', 'getActivityLog']]);
        $this->middleware('permission:manager_research_research-activity-log-trash', ['only' => ['getTrashActivity', 'getTrashActivityLog']]);
        $this->middleware('permission:manager_research_research-swap', ['only' => ['swapUp', 'swapDown']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'page_title' => 'Researches',
            'p_title' => 'Researches',
            's_title' => 'Researches',
            'p_summary' => 'List of Researches',
            'p_description' => null,
            'url' => route('manager.research.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.research-activity-trash'),
            'trash_text' => 'View Trash',
        );

        return view('manager.research.index')->with($data);
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
        $totalRecords = Research::with('DptID')->count();

        $totalRecordsWithFilter = Research::with('DptID')
            ->where(function ($q) use ($searchValue) {
                $q->where('researches.title', 'like', '%' . $searchValue . '%')
                    ->orWhere('researches.author', 'like', '%' . $searchValue . '%')
                    ->orWhere('researches.year', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    });
            })
            ->count();

        // Fetch records
        $records = Research::with('DptID')
            ->where(function ($q) use ($searchValue) {
                $q->where('researches.title', 'like', '%' . $searchValue . '%')
                    ->orWhere('researches.author', 'like', '%' . $searchValue . '%')
                    ->orWhere('researches.year', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    });
            })
            ->orderBy('priority', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $title = $record->title;
            $department_id = ($record->DptID->title ?? '');
            $author = ($record->author ?? '');
            $year = ($record->year ?? '');

            $data_arr[] = array(
                "id" => $id,
                "title" => $title,
                "department_id" => $department_id,
                "author" => $author,
                "year" => $year,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
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
            'page_title' => 'Research',
            'p_title' => 'Research',
            'p_summary' => 'Add Research',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.research.store'),
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );

        return view('manager.research.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:researches|string',
            'department_id' => 'required',
            'author' => 'required',
            'year' => 'required',
        ], [
            'department_id.required' => 'Department is required'
        ]);

        // get max priority from db
        $max = Research::selectRaw('MAX(priority) as max')->first()->max;
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
            'title' => $request->title,
            'author' => $request->author,
            'department_id' => $request->department_id,
            'journal' => $request->journal,
            'publish' => $request->publish,
            'impact_factor' => $request->impact_factor,
            'year' => $request->year,
            'detail' => $request->detail,
            'priority' => $priority,
            'created_by' => Auth::user()->id,
        ];

        $record = Research::create($arr);

        if (isset($record)) {
            $messages = [
                array(
                    'message' => 'Record created successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.research.edit', $record->id);
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
    public function show(string $rid)
    {
        $record = Research::with('DptID')->where('id', $rid)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        // Add activity logs
        $user = Auth::user();
        activity('Research')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->title]])
            ->log('viewed');

        $data = array(
            'page_title' => 'Research',
            'p_title' => 'Research',
            'p_summary' => 'Show Research',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.research.update', $record->id),
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'data' => $record,
            'rid' => $rid,
            'enctype' => 'application/x-www-form-urlencoded',
        );
//        dd($data);
        return view('manager.research.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $rid)
    {
        $record = Research::with('DptID')->where('id', $rid)->first();
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        // Add activity logs
        $user = Auth::user();
        activity('Research')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->title]])
            ->log('viewed');

        $data = array(
            'page_title' => 'Research',
            'p_title' => 'Research',
            'p_summary' => 'Edit Research',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.research.update', $record->id),
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'data' => $record,
            'rid' => $rid,
            'enctype' => 'multipart/form-data',
        );

        return view('manager.research.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//                dd($request);
        $record = Research::find($id);

        $this->validate($request, [
            'title' => 'required|unique:researches,title,' . $id,
            'department_id' => 'required',
            'author' => 'required',
            'year' => 'required',
        ]);


        $arr = [
            'title' => $request->title,
            'author' => $request->author,
            'department_id' => $request->department_id,
            'journal' => $request->journal,
            'publish' => $request->publish,
            'impact_factor' => $request->impact_factor,
            'year' => $request->year,
            'detail' => $request->detail,
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
        return redirect()->route('manager.research.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Research::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $recordAttach = Attachment::where('research_id', $id)->get();

        foreach ($recordAttach as $r) {
            // Attachment Remove
            $attachment_path = Storage::disk('private')->path('Research/Attachment/' . $r->attachment_url);
            if (File::exists($attachment_path)) {
                File::delete($attachment_path);
            }
            $r->delete();
        }

//        if($attachment_path){
//
////            dd($recordAttach);
//            $attach = $recordAttach->delete();
//
//            if (empty($recordAttach)) {
//                abort(404, 'NOT FOUND');
//            }
//        }

        $record->delete();


        $messages = [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.research.index');
    }

    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Research',
            'p_title' => 'Research',
            'p_summary' => 'Show Research',
            'p_description' => null,
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.research.activity')->with($data);
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
            ->where('activity_log.subject_type', Research::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Research::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Research::class)
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
            'page_title' => 'Research',
            'p_title' => 'Research',
            'p_summary' => 'Show Research Trashed Activity',
            'p_description' => null,
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
        );
        return view('manager.research.trash')->with($data);
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
            ->where('activity_log.subject_type', Research::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Research::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Research::class)
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
        $research = Research::find($id);

        // get priority of previous record
        $priority = Research::select('priority as prev')->where('priority', '>', $research['priority'])->first();
//       dd($priority);
        // if no previous record exists
        if (!isset($priority->prev)) {
            return redirect()->back()->with('message', 'There is no previous record');
        }

        // record with priority to be swapped
        $researchSwap = Research::where('priority', $priority->prev)->first();

        // get the priorities
        $priority = $research['priority'];
        //dd($priority);

        $swapPriority = $researchSwap['priority'];
        //dd($swapPriority);

        $researchUpdate['priority'] = null;
        $researchSwapUpdate['priority'] = null;

        $researchUpdated = $research->update($researchUpdate);
        $researchSwapped = $researchSwap->update($researchSwapUpdate);

        // swap the priorities
        $researchUpdate2['priority'] = $swapPriority;
        $researchSwapUpdate2['priority'] = $priority;


        if ($research->update($researchUpdate2)) {
            $researchSwap = $researchSwap->update($researchSwapUpdate2);
        }

        return redirect()->route('manager.research.index');
    }

    public function swapDown($id)
    {
        // current record
        $research = Research::find($id);

        // get priority of next record
        $priority = Research::selectRaw('MAX(priority) as next')->where('priority', '<', $research['priority'])->first();

        // if no next record exists
        if (!isset($priority->next)) {
            return redirect()->back()->with('message', 'There is no next record');
        }

        // record with priority to be swapped
        $researchSwap = Research::where('priority', $priority->next)->first();

        // get the priorities
        $priority = $research['priority'];
        $swapPriority = $researchSwap['priority'];


        $researchUpdate['priority'] = null;
        $researchSwapUpdate['priority'] = null;

        $researchUpdated = $research->update($researchUpdate);
        $researchSwapped = $researchSwap->update($researchSwapUpdate);

        // swap the priorities
        $researchUpdate2['priority'] = $swapPriority;
        $researchSwapUpdate2['priority'] = $priority;


        if ($research->update($researchUpdate2)) {
            $glimpseSwap = $researchSwap->update($researchSwapUpdate2);
        }

        return redirect()->route('manager.research.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function detail(string $id)
    {
        $record = Research::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $attachs = Attachment::all();
//        dd($record);

        // Add activity logs
        $user = Auth::user();
        activity('Research')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->title]])
            ->log('viewed');

        $data = array(
            'page_title' => 'Research',
            'p_title' => 'Research',
            'p_summary' => 'Add Research Detail',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.research-detail', $record->id),
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'data' => $record,
            'attachs' => $attachs,
            'enctype' => 'multipart/form-data',
        );
//        dd($attachs);
        return view('manager.research.detail')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function detailUpdate(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);
        $record = Research::find($id);
//        $attach = new Attachment();
        $attachs = Attachment::all();
//        dd($attach);
        $arr = [
            'title' => $request->title,
            'detail' => $request->detail,
            'updated_by' => Auth::user()->id,
        ];
        $record->update($arr);

        if ($request->hasFile('attachment_url')) {
//            dd('123');
            // Attachment
            $cAttachments = $request->file('attachment_url');

            foreach ($cAttachments as $cAttachment) {
                $attachmentOriginalName = $cAttachment->getClientOriginalName();
                $cAttachmentFileName = time() . rand(0, 999999) . '-' . $attachmentOriginalName;
                $img = date('Y') . '/' . date('m') . '/' . $cAttachmentFileName;
                $basePath = 'private';
                $researchPath = $basePath . '/Research';
                $attachmentPath = $researchPath . '/Attachment';
                $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
                $attachmentUrlfileName = date('Y') . '/' . date('m') . '/' . $cAttachmentFileName;

                $attachment_url = $cAttachment->storeAs(
                    $monthlyAttachmentsPath,
                    $cAttachmentFileName
                );

                // Unlink previous image for each attachment
                if (isset($record) && $record->attachment_url) {
                    $prevImage = Storage::disk('private')->path('Research/Attachment/' . $record->attachment_url);
                    if (File::exists($prevImage)) {
                        File::delete($prevImage);
                    }
                }

                $arrAttach[] = [
                    'attachment_url' => $attachment_url,
                    'attachment_name' => $attachmentOriginalName,
                    'attachment_url_name' => $attachmentUrlfileName,
                ];
//                dd($arrAttach);
            }
        } else {
            // Handle the case where there are no new attachments
            $arrAttach = [];
        }

        if ($arrAttach) {

            foreach ($arrAttach as $arr) {
                $arrA = [
                    'research_id' => $id,
                    'attachment_url' => $arr['attachment_url'],
                    'attachment_name' => $arr['attachment_name'],
                    'attachment_url_name' => $arr['attachment_url_name'],
                ];

//                dd($arrA);
                $record->attachID()->create($arrA);

            }
        }


        $messages = [
            array(
                'message' => 'Record updated successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('manager.research.index');
    }

    public function ckImage($filename)

    {
        $storage = Storage::disk('private');
        $path = 'Research/Ckeditor/' . $filename;
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
//        dd('123');
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
            $folderPath = 'Research/Ckeditor/';
            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;
            $file = $folderPath . $filenametostore;
            //Upload File
            Storage::disk('private')->put($file, file_get_contents($request->upload));

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');


            $url = route('research.ckImage', ['filename' => $filenametostore]);

            $msg = 'Image successfully uploaded';

            $re = '<script>window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "' . $url . '", "' . $msg . '")</script>';

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }
}
