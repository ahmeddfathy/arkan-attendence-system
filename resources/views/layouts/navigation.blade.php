<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('/') }}">
            <span class="brand-text">ARKAN</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Home Link -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('/') ? 'active' : '' }}" href="{{ route('/') }}">Home</a>
                </li>

                @auth
                    <!-- Manager-Specific Links -->
                    @if(auth()->user()->role == 'manager')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}" href="{{ route('attendances.index') }}">Attendance Records</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('leaves.index') ? 'active' : '' }}" href="{{ route('leaves.index') }}">Leave Records</a>
                        </li>
                    @endif

                    <!-- Employee-Specific Links -->
                    @if(auth()->user()->role == 'employee')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}" href="{{ route('attendances.create') }}">Mark Attendance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('leaves.create') ? 'active' : '' }}" href="{{ route('leaves.create') }}">Mark Leave</a>
                        </li>
                    @endif

                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/user/profile">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Login Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
