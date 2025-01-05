<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">

    <a class="navbar-brand ps-3" href="index.html">{{$settings->title}}</a>

    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    @impersonating
    <h1 style="background-color: #f0f0f0; padding: 21px; border-radius: 5px; color:red; font-size: 20px; font-weight: bold; text-align: center;">
        You are impersonating this user
    </h1>
@endImpersonating



    <!-- Navbar-->
    <ul class="navbar-nav ms-auto  me-3 me-lg-4">
        <li class="navbar-nav ms-auto me-3 me-lg-4 position-relative">

            <a class="nav-link dropdown-toggle no-caret" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">

                    Notification
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                    {{ $user->unreadNotifications->count() }}
                </span>
            </a>


            <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg rounded-lg" style="width: 400px;">
                <!-- Header -->
                <div class="dropdown-header text-white text-center py-2" style="background-color: #726d6d;">
                    Notifications
                </div>
                <div class="dropdown-divider m-0"></div>

                <!-- Notifications list -->
                <div class="list-group">
                    @forelse ($user->unreadNotifications as $notification)
                        <a href="/users"
                            class="list-group-item list-group-item-action">
                            <!-- Notification details -->
                            New User Registered: {{ $notification->data['NewwUser'] }}
                            <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                            {{$notification->markAsRead();}}
                        </a>
                    @empty
                        <!-- Fallback for no notifications -->
                        <div class="list-group-item text-center">
                            No notifications available.
                        </div>
                    @endforelse
                </div>

            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{url('profiledashboard')}}">Profile</a></li>

                <li><a class="dropdown-item" href="{{route('settings.show')}}">Setting</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a class="dropdown-item" :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                    </a>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
