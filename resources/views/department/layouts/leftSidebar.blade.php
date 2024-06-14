<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <h5 class="mb-0">{{!empty(session('currentModule.0')) ? ucwords(session('currentModule.0')) : ''}}</h5>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('department/dashboard') ? 'active' : '' }}" href="{{route('department.dashboard')}}">
                <i class="nav-icon cil-speedometer"></i> Dashboard
            </a>
        </li>
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link {{ request()->is('department/dept-banner') ? 'active' : '' }}" href="{{route('department.dept-banner.index')}}">--}}
{{--                <i class="nav-icon cil-speedometer"></i> Department Banner--}}
{{--            </a>--}}
{{--        </li>--}}
        @canany(['department_department_department-list'])
            @can('department_department_department-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('department/dept') ? 'active' : '' }}" href="{{route('department.dept.index')}}">
                <i class="nav-icon cil-speedometer"></i> Department
            </a>
        </li>
            @endcan
        @endcanany
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
