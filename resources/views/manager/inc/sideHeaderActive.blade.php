<div class="nav flex-column nav-pills circle-countr me-3" id="v-pills-tab" role="tablist"
     aria-orientation="vertical">

    {{--        @canany(['m-hr_notification_noti-create'])--}}
    <a href="{{ route('manager.research.edit', $rid) }}"
       class="nav-link is_active_notification text-center {{ request()->is('manager/research/*/edit') ? 'active' : '' }}"
       type="button" role="tab">
        <div class="d-flex justify-content-start">
            <div class="sidebar-number-count me-1 d-inline-block">1</div>
            Research
        </div>
    </a>
    {{--        @endcanany--}}
    {{--        @canany(['m-hr_notification_noti-attachment-list','m-hr_notification_noti-attachment-create'])--}}
    <a href="{{ route('manager.attachment.index', ['rid' => $rid]) }}"
       class="nav-link is_active_attachments text-center {{ request()->is('manager/research/*/attachment') ? 'active' : '' }}"
       type="button" role="tab">
        <div class="d-flex justify-content-start">
            <div class="sidebar-number-count me-1 d-inline-block">2</div>
            Attachments
        </div>
    </a>
    {{--        @endcanany--}}


</div>
