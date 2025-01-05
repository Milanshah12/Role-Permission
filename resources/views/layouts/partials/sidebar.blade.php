<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{ url('dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>


            @if (auth()->user() &&
            auth()->user()->hasRole(['admin', 'super-admin', 'Editor', 'manager']))

<a class="nav-link" href="{{ url('users') }}">
    <div class="sb-nav-link-icon"><i class="fas fa-user-alt"></i></div>
    User
</a>

<a class="nav-link" href="{{ url('permission') }}">
    <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
   Permission
</a>

<a class="nav-link" href="{{ url('roles') }}">
    <div class="sb-nav-link-icon"><i class="fas fa-unlock-alt"></i></div>
  Roles
</a>
@impersonating($guard = null)

<a class="nav-link" href="{{route('impersonate.leave')}}">
    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
    Leave impersanate
</a>
@endImpersonating
@endif


            <a class="nav-link" href="tables.html">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Tables
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
{{Auth::user()->getRoleNames()->first()}}
    </div>
</nav>
