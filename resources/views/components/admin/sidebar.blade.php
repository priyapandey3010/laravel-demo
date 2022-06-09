<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('dashboard') }}">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Dashboard</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="{{ url('dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Menu
</div>

@if (acl('display-board-view') || acl('display-board-upload-view'))
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
        aria-expanded="true" aria-controls="collapseThree">
        <i class="fas fa-fw fa-cog"></i>
        <span>Display Board</span>
    </a>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @if (acl('display-board-view'))
            <a class="collapse-item" href="{{ url('display-board') }}">Manage Display Board</a>
            @endif
            @if (acl('display-board-upload-view'))
            <a class="collapse-item" href="{{ url('display-board-uploads') }}">Upload Excel</a>
            @endif
        </div>
    </div>
</li>
@endif

@if (
    acl('user-view') ||
    acl('role-view') ||
    acl('permission-view') ||
    acl('designation-view') ||
    acl('department-view')
)
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
        aria-expanded="true" aria-controls="collapseOne">
        <i class="fas fa-fw fa-cog"></i>
        <span>User Management</span>
    </a>
    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @if (acl('user-view'))
            <a class="collapse-item" href="{{ url('users') }}">Users</a>
            @endif
            @if (acl('role-view'))
            <a class="collapse-item" href="{{ url('roles') }}">Roles</a>
            @endif
            @if (acl('permission-view'))
            <a class="collapse-item" href="{{ url('permissions') }}">Permissions</a>
            @endif
            @if (acl('designation-view'))
            <a class="collapse-item" href="{{ url('designations') }}">Designation</a>
            @endif
            @if (acl('department-view'))
            <a class="collapse-item" href="{{ url('departments') }}">Department</a>
            @endif
        </div>
    </div>
</li>
@endif

<!-- Nav Item - Pages Collapse Menu -->
@if (
    acl('case-master-view') ||
    acl('bench-view') ||
    acl('status-view') ||
    acl('manage-cases-view') ||
    acl('court-view') || 
    acl('category-view')
)
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Masters</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @if (acl('case-master-view'))
                <a class="collapse-item" href="{{ url('case-types') }}">Case Master</a>
            @endif
            @if (acl('bench-view'))
                <a class="collapse-item" href="{{ url('bench') }}">Bench</a>
            @endif
            @if (acl('status-view'))
                <a class="collapse-item" href="{{ url('status') }}">Status</a>
            @endif
            @if (acl('manage-cases-view'))
                <a class="collapse-item" href="{{ url('case') }}">Manage Cases</a>
            @endif
            @if (acl('court-view'))
                <a class="collapse-item" href="{{ url('courts') }}">Court</a>
            @endif
            @if (acl('category-view'))
                <a class="collapse-item" href="{{ url('category') }}">Category</a>
            @endif
        </div>
    </div>
</li>
@endif
<li class="nav-item">
    <a class="nav-link collapsed" href="{{ url('audit-trail') }}">
        <i class="fas fa-fw fa-history"></i>
        <span>Audit Trail</span>
    </a>
</li>
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>


</ul>
<!-- End of Sidebar -->