<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\Attachment;
use App\Models\Manager\Conference;
use App\Models\Manager\Department;
use App\Models\Manager\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
        $data = [
            'page_title' => 'Researches',
            'p_title' => 'Researches',
            'footerDepartments' => $footerDepartments
        ];
        return view('front.research')->with($data);
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
        $totalRecords = Research::select('count(*) as allcount')
            ->where('researches.title', 'like', '%' .$searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();
        $totalRecordswithFilter = Research::where('researches.title', 'like', '%' .$searchValue . '%')
            ->orderBy('id', 'DESC')
            ->count();
        // Fetch records
        $records = Research::orderBy('priority', 'DESC')
            ->where('researches.title', 'like', '%' .$searchValue . '%')
            ->select('researches.*')
//            ->orderBy('priority', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//        dd($records);

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $title = $record->title;
            if(isset($record['DptID']['title'])){
                $department_id = $record['DptID']['title'];
            }
            else{
                $department_id = "";
            }
            $year = $record->year;

            $data_arr[] = array(
                "id" => $id,
                "title" => $title,
                "department_id" => $department_id,
                "year" => $year,
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
     * Show the form for editing the specified resource.
     */
    public function detail(string $id)
    {

        $record = Research::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $attachRecord = Attachment::select('*')->
            where('research_id', '=', $record->id)->get();
//        dd($attachRecord);
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
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
//            'action' => route('manager.research-detail', $record->id),
            'url' => route('manager.research.index'),
            'url_text' => 'View All',
            'data' => $record,
            'footerDepartments' => $footerDepartments,
            'attachRecord' => $attachRecord,
            'enctype' => 'multipart/form-data',
        );
//        dd($data);
        return view('front.research_detail')->with($data);
    }
    public function getFile($id, $filename)
    {
        $record = Attachment::where('research_id', $id)->where('attachment_url_name','=',$filename )->first();
//        dd($record);
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }
        $path = Storage::disk('private')->path('Research/Attachment/'. date('Y'). '/' . date('m') . '/' . $record->attachment_url_name);
//       dd($path);
        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
        else{
            abort(404, 'NOT FOUND');
        }
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
