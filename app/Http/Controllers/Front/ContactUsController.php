<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manager\ContactUs;
use App\Models\Manager\Department;
use App\Models\Manager\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $footerDepartments = Department::orderBy('priority','desc')->paginate(5);
        $data = [
            'page_title' => 'Contact Us',
            'p_title' => 'Contact Us',
            'method' => 'POST',
            'action' => route('contact'),
            'enctype' => 'multipart/form-data',
            'footerDepartments' => $footerDepartments
        ];
        return view('front.contact')->with($data);
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

        $validator = $this->validate($request, [
            'name' =>  'required',
            'email' =>  'required',
            'subject' =>  'required',
            'message' =>  'required',
        ]);
        $arr = [

            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'subject' => $request->subject,
            'message' => $request->message,
        ];
//        dd($arr);
        $record = ContactUs::create($arr);
        if ($record) {
            $messages = [
                array(
                    'message' => 'Message sent successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('contact-us');
        }
//        else {
//            abort(404, 'NOT FOUND');
//        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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

    }
}
