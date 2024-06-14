<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\Attachment;
use App\Models\Manager\NewsEvent;
use App\Models\Manager\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_research_attachment-list', ['only' => ['index', 'getIndex']]);
        $this->middleware('permission:manager_research_attachment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manager_research_attachment-show', ['only' => 'show']);
        $this->middleware('permission:manager_research_research-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index($rid)
    {
        $record = Research::find($rid);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $data = array(
            'page_title' => 'Research Attachments',
            'p_title' => 'Research Attachments',
            's_title' => 'Research Attachments',
            'p_summary' => 'List of Research Attachments',
            'p_description' => null,
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'record' => $record,
            'rid' => $rid,
            'method' => 'POST',
            'action' => route('manager.attachment.store', $rid),
            'enctype' => 'multipart/form-data',
//            'trash' => route('manager.get.research-activity-trash'),
//            'trash_text' => 'View Trash',
        );

        return view('manager.attachment.index')->with($data);
    }

    public function getIndex(Request $request, $rid)
    {
//dd($rid);
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
        $totalRecords = Attachment::select('count(*) as allcount')
            ->where('attachments.research_id', $rid)
            ->where('attachments.attachment_name', 'like', '%' . $searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();

        $totalRecordswithFilter = Attachment::where('attachments.research_id', $rid)
            ->where('attachments.attachment_name', 'like', '%' . $searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();
        // Fetch records
        $records = Attachment::orderBy('id', 'DESC')
            ->where('attachments.research_id', $rid)
            ->where('attachments.attachment_name', 'like', '%' . $searchValue . '%')
            ->select('attachments.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//dd($records);

        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $attachment_name = $record->attachment_name;
            $rid = $rid;


            $data_arr[] = array(
                "id" => $id,
                "attachment_name" => $attachment_name,
                "rid" => $rid,
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
    public function create($rid)
    {
        $record = Research::where('id', $rid)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $data = array(
            'page_title' => 'Research Attachments',
            'p_title' => 'Research Attachments',
            'p_summary' => 'Add Research Attachments',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.attachment.store', $rid),
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'record' => $record,
            'rid' => $rid,
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );

        return view('manager.attachment.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $rid)
    {
        $record = Research::find($rid);

        $this->validate($request, [
            'attachment_url' => 'required|mimes:jpg,jpeg,png,pdf|max:5000',
        ], [
            'attachment_url.required' => 'Attachment is Required',
            'attachment_url.mimes' => 'Attachment Type should be jpg, jpeg, png and pdf',
        ]);

        $attachment_url = null;
        $attachmentOriginalName = null;
        $attachmentUrlfileName = null;
        if ($request->hasFile('attachment_url')) {
            // Image
            $cAttachment = $request->file('attachment_url');
            $attachmentOriginalName = $cAttachment->getClientOriginalName();
            $cAttachmentFileName = time() . rand(0, 999999) . '-' . $attachmentOriginalName;
            $basePath = 'private';
            $attachmentPath = $basePath . '/Research';
            $attachmentPath = $attachmentPath . '/Attachment';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $attachmentUrlfileName = $cAttachmentFileName;
            $attachment_url = $request->file('attachment_url')->storeAs(
                $monthlyAttachmentsPath,
                $cAttachmentFileName
            );
        }


        $arrA = [
            'research_id' => $record->id,
            'attachment_url' => $attachment_url,
            'attachment_name' => $attachmentOriginalName,
            'attachment_url_name' => $attachmentUrlfileName,
            'created_by' => Auth::user()->id,
        ];

        $record = Attachment::create($arrA);

        if (isset($record)) {
            $messages = [
                array(
                    'message' => 'Attachment Uploaded Successfully!',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.attachment.index', $rid);
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
     * @param String_ $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $rid, string $id)
    {
        $record = Attachment::where('id', $id)
            ->where('research_id', $rid)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = Storage::disk('private')->path('Research/Attachment/' . date('Y') . '/' . date('m') . '/' . $record->attachment_url_name);

        if (file_exists($path)) {
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $rid, string $id)
    {
        $record = Attachment::where('id', $id)
            ->where('research_id', $rid)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

//     Research Attachment Remove
        $image_path = Storage::disk('private')->path('Research/Attachment/' . date('Y') . '/' . date('m') . '/' . $record->attachment_url_name);
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

        return redirect()->route('manager.attachment.index', $rid);
    }
}
