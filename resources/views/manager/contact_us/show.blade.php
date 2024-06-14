@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')

@endpush
@section('content')
    <div class="card mt-3">
        <div class="card-body">
            {{-- Start: Page Content --}}
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0">{{(!empty($p_title) && isset($p_title)) ? $p_title : ''}}</h4>
                    <div
                        class="small text-medium-emphasis">{{(!empty($p_summary) && isset($p_summary)) ? $p_summary : ''}}</div>
                </div>
                <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                    @can('manager_user-management_contact-list')
                        <a href="{{(!empty($url) && isset($url)) ? $url : ''}}"
                           class="btn btn-sm btn-primary">{{(!empty($url_text) && isset($url_text)) ? $url_text : ''}}</a>
                    @endcan
                </div>
            </div>
            <hr>
            {{-- Start: Form --}}
            <div>
                <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           id="name" placeholder="Title"
                                           value="{{(!empty($data->name) && isset($data->name)) ? $data->name : old('name')}}"
                                           disabled>
                                    @error('name')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email *</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           id="email" placeholder="Title"
                                           value="{{(!empty($data->email) && isset($data->email)) ? $data->email : old('email')}}"
                                           disabled>
                                    @error('email')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="subject">Subject *</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                           name="subject"
                                           id="subject" placeholder="Title"
                                           value="{{(!empty($data->subject) && isset($data->subject)) ? $data->subject : old('subject')}}"
                                           disabled>
                                    @error('subject')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="phone_no">Phone No.</label>
                                    <input type="text" class="form-control @error('phone_no') is-invalid @enderror"
                                           name="phone_no"
                                           id="phone_no" placeholder="Title"
                                           value="{{(!empty($data->phone_no) && isset($data->phone_no)) ? $data->phone_no : old('phone_no')}}"
                                           disabled>
                                    @error('phone_no')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="message">Message *</label>
                                    <textarea name="message" id="message" cols="30" rows="3" class="form-control"
                                              disabled>{!! $data->message !!}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{--                    <button type="submit" class="btn btn-sm btn-success">Submit</button>--}}
                </form>
            </div>
            {{-- End: Form --}}
        </div>
    </div>

    {{-- Page Description : Start --}}
    @if(!empty($p_description) && isset($p_description))
        <div class="card-footer">
            <div class="row">
                <div class="col-12 mb-sm-2 mb-0">
                    <p>{{(!empty($p_description) && isset($p_description)) ? $p_description : ''}}</p>
                </div>
            </div>
        </div>
    @endif
    {{-- Page Description : End --}}
@endsection
@push('footer-scripts')
@endpush
