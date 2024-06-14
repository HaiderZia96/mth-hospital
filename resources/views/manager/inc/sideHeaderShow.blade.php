<div class="nav flex-column nav-pills circle-countr me-3" id="v-pills-tab" role="tablist"
     aria-orientation="vertical">

    <a href="{{ route('manager.research.show', $rid) }}"
       class="nav-link is_active_notification text-center {{ request()->is('manager/research/*') ? 'active' : '' }}"
       type="button" role="tab">
        <div class="d-flex justify-content-start">
            <div class="sidebar-number-count me-1 d-inline-block">1</div>
            Research
        </div>
    </a>
    <a href="{{ route('manager.attachment.index', ['rid' => $rid]) }}"
       class="nav-link is_active_attachments text-center {{ request()->is('manager/research/*/attachment') ? 'active' : '' }}"
       type="button" role="tab">
        <div class="d-flex justify-content-start">
            <div class="sidebar-number-count me-1 d-inline-block">2</div>
            Attachments
        </div>
    </a>
</div>
