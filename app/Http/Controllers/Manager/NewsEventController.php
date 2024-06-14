<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Manager\Department;
use App\Models\Manager\EventCategory;
use App\Models\Manager\NewsEvent;
use App\Models\Manager\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class NewsEventController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_event_news-list', ['only' => ['index', 'getIndex']]);
        $this->middleware('permission:manager_event_news-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manager_event_news-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manager_event_news-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_event_news-activity-log', ['only' => ['getActivity', 'getActivityLog']]);
        $this->middleware('permission:manager_event_news-activity-log-trash', ['only' => ['getTrashActivity', 'getTrashActivityLog']]);
        $this->middleware('permission:manager_event_news-swap', ['only' => ['swapUp', 'swapDown']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'page_title' => 'News & Events',
            'p_title' => 'News & Events',
            's_title' => 'News & Events',
            'p_summary' => 'List of News & Events',
            'p_description' => null,
            'url' => route('manager.news-event.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.news-event-activity-trash'),
            'trash_text' => 'View Trash',
        ];
//        dd($departments);
        return view('manager.news_event.index')->with($data);
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
        $totalRecords = NewsEvent::select('news_events.*')->with('DptID', 'eCateID')
            ->count();


        $totalRecordswithFilter = NewsEvent::select('news_events.*')->with('DptID', 'eCateID')
            ->where(function ($q) use ($searchValue) {
                $q->where('news_events.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('news_events.e_date', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    })->orWhereHas('eCateID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('event_category.name', 'like', '%' . $searchValue . '%');
                    });
            })
            ->count();

        // Fetch records
        $records = NewsEvent::select('news_events.*')->with('DptID', 'eCateID')
            ->where(function ($q) use ($searchValue) {
                $q->where('news_events.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('news_events.e_date', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('DptID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('departments.title', 'like', '%' . $searchValue . '%');
                    })->orWhereHas('eCateID', function ($subQuery) use ($searchValue) {
                        $subQuery->where('event_category.name', 'like', '%' . $searchValue . '%');
                    });
            })
            ->orderBy('news_events.priority', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $name = $record->name;
            $dpt_id = ($record->DptID->title ?? '');
            $eCateID = ($record->eCateID->name ?? '');
            $e_date = $record->e_date;

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "dpt_id" => $dpt_id,
                "e_date" => $e_date,
                "eCateID" => $eCateID,
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

    public function getEventCategoryIndexSelect(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = EventCategory::where('name', 'like', '%' . $search . '%')
                ->get();
        }

        return response()->json($data);

    }

    public function getEventDepartmentIndexSelect(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;

            $data = Department::where('title', 'like', '%' . $search . '%')
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
        $eventCategory = EventCategory::orderBy('name', 'Desc')->get();
        $departments = Department::orderBy('title', 'Desc')->get();
        $data = array(
            'page_title' => 'News & Events',
            'p_title' => 'News & Events',
            'p_summary' => 'Add News & Events',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.news-event.store'),
            'url' => route('manager.news-event.index'),
            'url_text' => 'View All',
            'eventCategory' => $eventCategory,
            'departments' => $departments,
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );
        return view('manager.news_event.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'thumbnail_url' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'banner_url' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'name' => 'required|unique:news_events|string',
            'e_date' => 'required',
            'e_cate' => 'required',
            'dpt_id' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
        ], [
            'thumbnail_url.required' => 'Thumbnail is Required',
            'thumbnail_url.mimes' => 'Thumbnail File Type should be jpg, jpeg, png and gif',
            'banner_url.required' => 'Banner is Required',
            'banner_url.mimes' => 'Banner File Type should be jpg, jpeg, png and gif',
            //Validation for Event
            'e_date.required' => 'Event date is required',
            'e_cate.required' => 'Event category is required',
            'dpt_id.required' => 'Department is required',
        ]);


        $thumbnail_url = null;
        $thumbnailOriginalName = null;
        $thumbnailUrlfileName = null;
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
            $thumbnailPath = $basePath . '/NewsAndEvents';
            $attachmentPath = $thumbnailPath . '/Thumbnail';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $thumbnailUrlfileName = $cThumbnailFileName;
            $thumbnail_url = $request->file('cropper_thumbnail')->storeAs(
                $monthlyAttachmentsPath,
                $cThumbnailFileName
            );
        }


        $banner_url = null;
        $bannerOriginalName = null;
        $bannerUrlfileName = null;
        if ($request->hasFile('cropper_banner')) {
            // Thumbnail
            $cBanner = $request->file('banner_url');
            $bannerOriginalName = $cBanner->getClientOriginalName();
            $bannerName = pathinfo($bannerOriginalName, PATHINFO_FILENAME);
            $bannerExtension = $cBanner->getClientOriginalExtension();
            $bannerOriginalNameSluggy = Str::slug($bannerName);
            $cBannerFileName = time() . rand(0, 999999) . '-' . $bannerOriginalNameSluggy . '.' . $bannerExtension;

            $basePath = 'private';
            $bannerPath = $basePath . '/NewsAndEvents';
            $attachmentPath = $bannerPath . '/Banner';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $bannerUrlfileName = $cBannerFileName;
            $banner_url = $request->file('cropper_banner')->storeAs(
                $monthlyAttachmentsPath,
                $cBannerFileName
            );

        }

        // get max priority from db
        $max = NewsEvent::selectRaw('MAX(priority) as max')->first()->max;
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
            'name' => $request->name,
            'slug' => $this->createSlug($request->name),
            'dpt_id' => $request->dpt_id,
            'e_cate' => $request->e_cate,
            'e_date' => $request->e_date,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'thumbnail_url' => $thumbnail_url,
            'thumbnail_name' => $thumbnailOriginalName,
            'thumbnail_url_name' => $thumbnailUrlfileName,
            'banner_url' => $banner_url,
            'banner_name' => $bannerOriginalName,
            'banner_url_name' => $bannerUrlfileName,
            'priority' => $priority,
            'created_by' => Auth::user()->id,
        ];

        $record = NewsEvent::create($arr);

        if (isset($record)) {
            $messages = [
                array(
                    'message' => 'Record Created Successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.news-event.index');
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
        //Finding the previous record
        $record = NewsEvent::with(['DptID', 'eCateID'])
            ->where('id', $id)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        // Add activity logs
        $user = Auth::user();

        activity('NewsEvent')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');

        $data = array(
            'page_title' => 'News & Events',
            'p_title' => 'News & Events',
            'p_summary' => 'Show News & Events',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.news-event.update', $record->id),
            'url' => route('manager.news-event.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );

        return view('manager.news_event.show')->with($data);
    }

    public function showNewsAndEventAttachment($id, $image_type)
    {
        $record = NewsEvent::where('id', $id)
            ->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        $path = '';
        if ($image_type == 'banner_url') {
            $path = Storage::disk('private')->path('NewsAndEvents/Banner/' . date('Y') . '/' . date('m') . '/' . $record->banner_url_name);
        } else {
            if ($image_type == 'thumbnail_url') {
                $path = Storage::disk('private')->path('NewsAndEvents/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
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
        $record = NewsEvent::with(['DptID', 'eCateID'])->where('id', $id)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }

        // Add activity logs
        $user = Auth::user();
        activity('NewsEvent')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name' => $record->name]])
            ->log('viewed');

        $data = array(
            'page_title' => 'News & Events',
            'p_title' => 'News & Events',
            'p_summary' => 'Edit News & Events',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.news-event.update', $record->id),
            'url' => route('manager.news-event.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data',
        );
//        dd($data);
        return view('manager.news_event.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Finding the previous record
        $record = NewsEvent::find($id);

        //Validating the fields
        $this->validate($request, [
            'name' => 'required|unique:news_events,name,' . $id,
            'e_date' => 'required',
            'e_cate' => 'required',
            'dpt_id' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'thumbnail_url' => 'mimes:jpg,jpeg,png,gif|max:2048',
            'banner_url' => 'mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'thumbnail_url.mimes' => 'Thumbnail File Type should be jpg, jpeg, png and gif',
            'banner_url.mimes' => 'Banner File Type should be jpg, jpeg, png and gif',
            //Validation for Event
            'e_date.required' => 'Event date is required',
            'e_cate.required' => 'Event category is required',
            'dpt_id.required' => 'Department is required',
        ]);

        //Uploading Images if any
        if ($request->hasFile('cropper_thumbnail')) {
            // Thumbnail
            $cThumbnail = $request->file('thumbnail_url');
            $thumbnailOriginalName = $cThumbnail->getClientOriginalName();
            $thumbnailName = pathinfo($thumbnailOriginalName, PATHINFO_FILENAME);
            $thumbnailExtension = $cThumbnail->getClientOriginalExtension();
            $thumbnailOriginalNameSluggy = Str::slug($thumbnailName);
            $cThumbnailFileName = time() . rand(0, 999999) . '-' . $thumbnailOriginalNameSluggy . '.' . $thumbnailExtension;

            $basePath = 'private';
            $departmentPath = $basePath . '/NewsAndEvents';
            $attachmentPath = $departmentPath . '/Thumbnail';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $thumbnailUrlfileName = $cThumbnailFileName;
            $thumbnail_url = $request->file('cropper_thumbnail')->storeAs(
                $monthlyAttachmentsPath,
                $cThumbnailFileName
            );
            //Unlink previous image
            if (isset($record) && $record->thumbnail_url) {
                $prevImage = Storage::disk('private')->path('NewsAndEvents/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
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


        if ($request->hasFile('cropper_banner')) {
            // Banner
            $cBanner = $request->file('banner_url');
            $bannerOriginalName = $cBanner->getClientOriginalName();
            $bannerName = pathinfo($bannerOriginalName, PATHINFO_FILENAME);
            $bannerExtension = $cBanner->getClientOriginalExtension();
            $bannerOriginalNameSluggy = Str::slug($bannerName);
            $cBannerFileName = time() . rand(0, 999999) . '-' . $bannerOriginalNameSluggy . '.' . $bannerExtension;

            $basePath = 'private';
            $bannerPath = $basePath . '/NewsAndEvents';
            $attachmentPath = $bannerPath . '/Banner';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $bannerUrlfileName = $cBannerFileName;
            $banner_url = $request->file('cropper_banner')->storeAs(
                $monthlyAttachmentsPath,
                $cBannerFileName
            );
            //Unlink previous image
            if (isset($record) && $record->banner_url) {
                $prevImage = Storage::disk('private')->path('NewsAndEvents/Banner/' . date('Y') . '/' . date('m') . '/' . $record->banner_url_name);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['banner_url'] = $banner_url;
                $arr['banner_name'] = $bannerOriginalName;
                $arr['banner_url_name'] = $bannerUrlfileName;
            }
        } else {
            $banner_url = $record->banner_url;
            $bannerOriginalName = $record->banner_name;
            $bannerUrlfileName = $record->banner_url_name;
        }

        $arr = [
            'name' => $request->name,
            'dpt_id' => $request->dpt_id,
            'e_cate' => $request->e_cate,
            'e_date' => $request->e_date,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'thumbnail_url' => $thumbnail_url,
            'thumbnail_name' => $thumbnailOriginalName,
            'thumbnail_url_name' => $thumbnailUrlfileName,
            'banner_url' => $banner_url,
            'banner_name' => $bannerOriginalName,
            'banner_url_name' => $bannerUrlfileName,
            'updated_by' => Auth::user()->id,
        ];

        $updated_record = $record->update($arr);

        if ($updated_record) {
            $messages = [
                array(
                    'message' => 'Record updated successfully',
                    'message_type' => 'success'
                ),
            ];

            Session::flash('messages', $messages);

            return redirect()->route('manager.news-event.index');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = NewsEvent::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
//     Thumbnail Image Remove
        $image_path = Storage::disk('private')->path('NewsAndEvents/Thumbnail/' . date('Y') . '/' . date('m') . '/' . $record->thumbnail_url_name);
        if (File::exists($image_path)) {
            File::delete($image_path);
        }

        //        NewsAndEvents Image Remove
        $image_path = Storage::disk('private')->path('NewsAndEvents/Banner/' . date('Y') . '/' . date('m') . '/' . $record->banner_url_name);
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

        return redirect()->route('manager.news-event.index');
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
        return NewsEvent::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'News & Events',
            'p_title' => 'News & Events',
            'p_summary' => 'Show News & Events',
            'p_description' => null,
            'url' => route('manager.news-event.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.news_event.activity')->with($data);
    }

    /**
     * Display the specified resource Activity Logs.
     * @param String_ $id
     * @return \Illuminate\Http\Response
     */
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
            ->where('activity_log.subject_type', NewsEvent::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', NewsEvent::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', NewsEvent::class)
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
            'page_title' => 'News & Events',
            'p_title' => 'News & Events',
            'p_summary' => 'Show News & Events Trashed Activity',
            'p_description' => null,
            'url' => route('manager.news-event.index'),
            'url_text' => 'View All',
        );
        return view('manager.news_event.trash')->with($data);
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
            ->where('activity_log.subject_type', NewsEvent::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', NewsEvent::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', NewsEvent::class)
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
        $event = NewsEvent::find($id);

        // get priority of previous record
        $priority = NewsEvent::select('priority as prev')->where('priority', '>', $event['priority'])->first();
//       dd($priority);
        // if no previous record exists
        if (!isset($priority->prev)) {
            return redirect()->back()->with('message', 'There is no previous record');
        }

        // record with priority to be swapped
        $eventSwap = NewsEvent::where('priority', $priority->prev)->first();

        // get the priorities
        $priority = $event['priority'];
        //dd($priority);

        $swapPriority = $eventSwap['priority'];
        //dd($swapPriority);

        $eventUpdate['priority'] = null;
        $eventSwapUpdate['priority'] = null;

        $eventUpdated = $event->update($eventUpdate);
        $eventSwapped = $eventSwap->update($eventSwapUpdate);

        // swap the priorities
        $eventUpdate2['priority'] = $swapPriority;
        $eventSwapUpdate2['priority'] = $priority;


        if ($event->update($eventUpdate2)) {
            $eventSwap = $eventSwap->update($eventSwapUpdate2);
        }

        return redirect()->route('manager.news-event.index');
    }

    public function swapDown($id)
    {
        // current record
        $event = NewsEvent::find($id);

        // get priority of next record
        $priority = NewsEvent::selectRaw('MAX(priority) as next')->where('priority', '<', $event['priority'])->first();

        // if no next record exists
        if (!isset($priority->next)) {
            return redirect()->back()->with('message', 'There is no next record');
        }

        // record with priority to be swapped
        $eventSwap = NewsEvent::where('priority', $priority->next)->first();

        // get the priorities
        $priority = $event['priority'];
        $swapPriority = $eventSwap['priority'];


        $eventUpdate['priority'] = null;
        $eventSwapUpdate['priority'] = null;

        $eventUpdated = $event->update($eventUpdate);
        $eventSwapped = $eventSwap->update($eventSwapUpdate);

        // swap the priorities
        $eventUpdate2['priority'] = $swapPriority;
        $eventSwapUpdate2['priority'] = $priority;


        if ($event->update($eventUpdate2)) {
            $glimpseSwap = $eventSwap->update($eventSwapUpdate2);
        }

        return redirect()->route('manager.news-event.index');
    }

    public function ckImage($filename)

    {
        $storage = Storage::disk('private');
        $path = 'NewsAndEvents/Ckeditor/' . $filename;
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
            $folderPath = 'NewsAndEvents/Ckeditor/';
            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;
            $file = $folderPath . $filenametostore;
            //Upload File
            Storage::disk('private')->put($file, file_get_contents($request->upload));

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');

//            $url = Storage::disk('private')->path('NewsAndEvents/Ckeditor/' . $filenametostore);
            $url = route('news-event.ckImage', ['filename' => $filenametostore]);

            $msg = 'Image successfully uploaded';

            $re = '<script>window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "' . $url . '", "' . $msg . '")</script>';

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }
}
