<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <h5 class="mb-0">{{!empty(session('currentModule.0')) ? ucwords(session('currentModule.0')) : ''}}</h5>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/dashboard') ? 'active' : '' }}" href="{{route('manager.dashboard')}}">
                <i class="nav-icon cil-speedometer"></i> Dashboard
            </a>
        </li>
        @canany(['manager_event_category-list','manager_event_news-list','manager_team_member-list','manager_achievement_award-list','manager_service_service-list','manager_user-management_contact-list','manager_department_department-list','manager_research_research-list','manager_event_conference-list'])
            @can('manager_department_department-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('manager/dept') ? 'active' : '' }}" href="{{route('manager.dept.index')}}">
                    <i class="nav-icon cil-speedometer"></i> Department
                </a>
            </li>
            @endcan

            @can('manager_event_news-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/news-event') ? 'active' : '' }}" href="{{route('manager.news-event.index')}}">
                <i class="nav-icon cil-speedometer"></i>News & Events
            </a>
        </li>
           @endcan
                @can('manager_team_member-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/team-member') ? 'active' : '' }}" href="{{route('manager.team-member.index')}}">
                <i class="nav-icon cil-speedometer"></i>Team Member
            </a>
        </li>
                @endcan
                @can('manager_achievement_award-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/achievement') ? 'active' : '' }}" href="{{route('manager.achievement.index')}}">
                <i class="nav-icon cil-speedometer"></i>Achievements
            </a>
        </li>
                @endcan

                @can('manager_user-management_contact-list')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('manager/contact-us') ? 'active' : '' }}" href="{{route('manager.contact-us.index')}}">
                        <i class="nav-icon cil-speedometer"></i>Contact Us
                    </a>
                </li>
                @endcan
                @can('manager_research_research-list')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('manager/research') ? 'active' : '' }}" href="{{route('manager.research.index')}}">
                            <i class="nav-icon cil-speedometer"></i>Researches
                        </a>
                    </li>
                @endcan
{{--                @can('manager_event_conference-list')--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ request()->is('manager/conference') ? 'active' : '' }}" href="{{route('manager.conference.index')}}">--}}
{{--                        <i class="nav-icon cil-speedometer"></i>Conferences--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                @endcan--}}

                @can('manager_event_category-list')
                    <li class="nav-title">Master Data</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('manager/event-category') ? 'active' : '' }}" href="{{route('manager.event-category.index')}}">
                            <i class="nav-icon cil-speedometer"></i> Event Category
                        </a>
                    </li>
                @endcan
        @endcanany
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
