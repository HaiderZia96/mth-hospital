

<div class="nav flex-column nav-pills circle-countr" id="v-pills-tab" role="tablist"
     aria-orientation="vertical">

    {{-- @canany(['m-dashboard_employee_personal-info']) --}}
    <a href="{{ route('manager.research.create') }}"
       class="nav-link is_active_notification text-center {{ request()->is('manager/research/*') ? 'active' : '' }}"
       type="button" role="tab">
        <div class="d-flex justify-content-start">
                   <div class="sidebar-number-count me-1 d-inline-block">1 </div>Research
        </div>
    </a>
    {{-- @endcan --}}
{{--     @canany(['m-dashboard_employee_emergency-contact']) --}}
    <a href="{{ route('manager.attachment.index', intval(request()->route()->parameter('rid'))) }}"
       class="nav-link is_active_attachments text-center disabledTab {{ request()->is('manager/research/*/attachment') ? 'active' : '' }}"
       type="button" role="tab">
        <div class="d-flex justify-content-start">
            <div class="sidebar-number-count me-1 d-inline-block">2 </div>Attachments
        </div>
    </a>

</div>

