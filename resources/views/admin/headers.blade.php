<header class="header fixed-top">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Institute Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="https://th.bing.com/th/id/OIP.fXgYUVPqjNT-LJDpgiKoVAHaHa?rs=1&pid=ImgDetMain"
                    alt="Institute Logo" class="rounded-circle me-2">
            </a>

            <!-- Fullscreen Icon -->
            <a class="navbar-brand d-flex align-items-center text-white fullscreen-toggle" href="javascript:void(0);">
                <i class="bi bi-arrows-fullscreen" style="font-size: 24px;"></i>
            </a>

            <div class="d-flex ms-auto">
                @if (session('success'))
                    <div class="alert alert-success" id="successAlert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" id="dangerAlert">{{ session('danger') }}</div>
                @endif
                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="btn btn-gray d-flex align-items-center" type="button" id="notificationDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell" style="font-size: 20px;"></i>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-circle ms-1">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li class="dropdown-header">Notifications</li>

                        <!-- Unread Notifications -->
                        @foreach (auth()->user()->unreadNotifications as $notification)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('notifications.markAsRead', $notification->id) }}">
                                    <i class="bi bi-envelope-fill text-danger"></i>
                                    {{ $notification->data['message'] ?? 'New Notification' }}
                                    <small
                                        class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                                </a>
                            </li>
                        @endforeach

                        <!-- Read Notifications -->
                        @foreach (auth()->user()->readNotifications as $notification)
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-envelope-open text-success"></i>
                                    {{ $notification->data['message'] ?? 'Read Notification' }}
                                    <small
                                        class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                                </a>
                            </li>
                        @endforeach

                        <!-- No Notifications -->
                        @if (auth()->user()->notifications->count() === 0)
                            <li>
                                <span class="dropdown-item text-muted">No notifications available</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="dropdown">

                    <button class="btn btn-gray dropdown-toggle d-flex align-items-center" type="button"
                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://th.bing.com/th/id/OIP.d6M35RHaKmxu-6Y7QtESOwHaHa?rs=1&pid=ImgDetMain"
                            alt="User Image" class="rounded-circle me-2">
                        <span>{{ Auth::user()->name }}</span>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#"><span class="bi bi-gear"> Settings</span></a></li>
                        <li><a class="dropdown-item" href="#"><span class="bi bi-people"> User Profile</span></a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <span class="bi bi-box-arrow-right"> Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    .header {
        background: linear-gradient(90deg, #4e73df, #1cc88a);
        /* Gradient background for a modern look */
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        height: 70px;
        /* fixed height */
        padding: 0 15px;

    }

    .dropdown-menu {
        max-height: 300px;
        overflow-y: auto;
    }

    .badge {
        font-size: 0.8rem;
        vertical-align: middle;
    }

    .header a.navbar-brand img {
        height: 40px;
        width: 40px;
        object-fit: cover;
        /* Ensures the image fills the circle without distortion */
        border: 2px solid white;
        /* Adds a border for emphasis and blending */
    }

    .header a.navbar-brand {
        font-size: 18px;
        font-weight: bold;
        color: white;
        margin-right: 10px;
        /* Ensures proper spacing between logo and fullscreen icon */
    }

    .header .navbar-brand:hover {
        color: #f1f1f1;
        /* Subtle hover effect */
    }
</style>

<script>
    // JavaScript for Fullscreen Toggle
    document.querySelector('.fullscreen-toggle').addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.body.requestFullscreen().catch(err => {
                console.error("Error attempting to enable fullscreen mode: ", err.message);
            });
        } else {
            document.exitFullscreen().catch(err => {
                console.error("Error attempting to exit fullscreen mode: ", err.message);
            });
        }
    });
</script>
<script>
    // Set a timeout to hide success and danger messages after 3 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('successAlert');
        const dangerAlert = document.getElementById('dangerAlert');

        if (successAlert) {
            successAlert.style.display = 'none';
        }
        if (dangerAlert) {
            dangerAlert.style.display = 'none';
        }
    }, 3000); // 3000 milliseconds = 3 seconds
</script>
