<header id="header" class="sticky-top header">
    <div class="header-nav d-flex align-items-center justify-content-between">
        <!-- Left Section -->
        <div class="d-flex align-items-center">
            <button id="sidebarToggle" class="btn btn-link text-dark me-2" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <!-- Right Section -->
        <div class="d-flex align-items-center">
            <!-- Search -->
            <div class="search-bar me-3">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control ps-4" placeholder="Search..." aria-label="Search">
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" id="successAlert" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- @if (session('success'))
        <div class="notification show" style="background: #4CC9F0; display: block;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif --}}

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" id="errorAlert" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Notifications -->
            <div class="dropdown me-3">
                <a class="btn btn-link text-dark position-relative" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell" style="font-size: 20px;"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger rounded-circle ms-1">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0 mt-2 notification-dropdown-menu" role="menu"
                    aria-label="Notifications menu" style="margin-left:30%">


                    <div class="dropdown-header bg-light py-2">
                        <strong>Notifications</strong>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            {{-- <a href="{{ route('notifications.markAllAsRead') }}" class="float-end small">Mark all as
                                read</a> --}}
                        @endif
                    </div>

                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <a class="dropdown-item py-2" href="{{ route('notifications.redirect', $notification->id) }}">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-envelope-fill text-danger"></i>
                                </div>
                                <div>
                                    <div>{{ $notification->data['message'] ?? 'New Notification' }}</div>
                                    <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="dropdown-item py-2 text-muted text-center">
                            No unread notifications
                        </div>
                    @endforelse



                    @if (auth()->user()->readNotifications->count() > 0)
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-header bg-light py-2">
                            <strong>Earlier</strong>
                        </div>
                        @foreach (auth()->user()->readNotifications->take(3) as $notification)
                            <a class="dropdown-item py-2" href="#">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-envelope-open text-success"></i>
                                    </div>
                                    <div>
                                        <div>{{ $notification->data['message'] ?? 'Notification' }}</div>
                                        <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="nav-item-divider d-none d-lg-flex"></div>

            <!-- User Profile -->
            <div class="dropdown">
                <a class="btn btn-link text-dark dropdown-toggle d-flex align-items-center" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                    <div class="user-avatar me-2">
                        <img src="{{ auth()->user()->avatar_url ?? 'https://static.vecteezy.com/system/resources/previews/006/487/917/original/man-avatar-icon-free-vector.jpg' }}"
                            alt="{{ auth()->user()->name }}" class="profile-image rounded-circle"
                            style="width: 32px; height: 32px; object-fit: cover; text-decoration:none">
                    </div>
                    <span class="d-none d-lg-inline" style="text-decoration:none">{{ auth()->user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0 mt-2" role="menu" aria-label="User menu">
                    <div class="dropdown-header bg-light py-2 d-flex align-items-center">
                        <div class="user-avatar me-2 position-relative">
                            <img id="profileImagePreview"
                                src="{{ auth()->user()->avatar_url ?? 'https://static.vecteezy.com/system/resources/previews/006/487/917/original/man-avatar-icon-free-vector.jpg' }}"
                                alt="{{ auth()->user()->name }}" class="profile-image rounded-circle"
                                style="width: 48px; height: 48px; object-fit: cover;">
                            <label for="profileImageUpload"
                                class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle p-0"
                                style="width: 20px; height: 20px;">
                                <i class="bi bi-camera-fill" style="font-size: 10px;"></i>
                            </label>
                            <input type="file" id="profileImageUpload" accept="image/*" style="display: none;">
                        </div>
                        <div>
                            <strong>{{ auth()->user()->name }}</strong>
                            <div class="text-muted small">{{ auth()->user()->email }}</div>
                        </div>
                    </div>

                    <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                    <a class="dropdown-item py-2" href="#" id="toggleThemeBtn">
                        <i class="bi bi-moon me-2"></i> Change Theme
                    </a>

                    <!-- Theme Color Options -->
                    {{-- <div id="themeOptions" class="px-3 pb-2 d-flex flex-wrap gap-2" style="display: none;">
                        
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#ffffff" class="form-check-input">
                            <span class="color-swatch" style="background-color: #ffffff;" title="Light"></span>
                            Light
                        </label>

                        
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#121212" class="form-check-input">
                            <span class="color-swatch" style="background-color: #121212;" title="Dark"></span> Dark
                        </label>

                       
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#1E90FF" class="form-check-input">
                            <span class="color-swatch" style="background-color: #1E90FF;" title="Dodger Blue"></span>
                            Blue
                        </label>

                        
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#198754" class="form-check-input">
                            <span class="color-swatch" style="background-color: #198754;"
                                title="Emerald Green"></span> Green
                        </label>

                        
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value=" #11245A" class="form-check-input">
                            <span class="color-swatch" style="background-color:  #11245A;" title="Match"></span>
                            Match
                        </label>
                        
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#6f42c1" class="form-check-input">
                            <span class="color-swatch" style="background-color: #6f42c1;" title="Purple"></span>
                            Purple
                        </label>
                    
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#fd7e14" class="form-check-input">
                            <span class="color-swatch" style="background-color: #fd7e14;" title="Orange"></span>
                            Orange
                        </label>

                       
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#20c997" class="form-check-input">
                            <span class="color-swatch" style="background-color: #20c997;" title="Teal"></span> Teal
                        </label>

                       
                        <label class="form-check d-flex align-items-center gap-1">
                            <input type="radio" name="themeColor" value="#e83e8c" class="form-check-input">
                            <span class="color-swatch" style="background-color: #e83e8c;" title="Pink"></span> Pink
                        </label>
                    </div> --}}




                    <a class="dropdown-item py-2" href="{{ route('change_password_form') }}">
                        <i class="bi bi-list-check me-2"></i> Change Password
                    </a>

                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function isDarkColor(hexColor) {
        // Remove hash if present
        const color = hexColor.replace('#', '');

        const r = parseInt(color.substr(0, 2), 16);
        const g = parseInt(color.substr(2, 2), 16);
        const b = parseInt(color.substr(4, 2), 16);

        // Standard luminance formula
        const luminance = 0.299 * r + 0.587 * g + 0.114 * b;

        return luminance < 128; // Adjust this threshold if needed
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts after 3 seconds
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        if (successAlert) {
            setTimeout(() => {
                const alert = new bootstrap.Alert(successAlert);
                alert.close();
            }, 3000);
        }

        if (errorAlert) {
            setTimeout(() => {
                const alert = new bootstrap.Alert(errorAlert);
                alert.close();
            }, 3000);
        }

        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        let debounceTimeout;

        const toggleSidebar = () => {
            document.body.classList.toggle('sidebar-collapsed');

            const icon = sidebarToggle.querySelector('i');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-justify');

            localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
        };

        sidebarToggle.addEventListener('click', () => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(toggleSidebar, 100);
        });

        // Initialize sidebar state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
            const icon = sidebarToggle.querySelector('i');
            icon.classList.replace('bi-list', 'bi-justify');
        }

        // Pusher notifications
        @if (config('broadcasting.default') === 'pusher')
            Echo.private(`App.Models.User.{{ auth()->id() }}`)
                .notification((notification) => {
                    window.dispatchEvent(new Event('new-notification'));
                });
        @endif
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleThemeBtn = document.getElementById('toggleThemeBtn');
        const themeOptions = document.getElementById('themeOptions');

        const headers = document.querySelectorAll('.header');
        const sidebars = document.querySelectorAll('.sidebar');

        toggleThemeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            themeOptions.style.display = themeOptions.style.display === 'none' ? 'block' : 'none';
        });

        document.querySelectorAll('input[name="themeColor"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedColor = this.value;
                const isDark = isDarkColor(selectedColor);

                headers.forEach(header => {
                    header.style.backgroundColor = selectedColor;
                    header.style.color = isDark ? '#ffffff' : '#000000';
                    header.classList.remove('bg-light', 'bg-dark');
                });

                sidebars.forEach(sidebar => {
                    sidebar.style.backgroundColor = selectedColor;
                    sidebar.style.color = isDark ? '#ffffff' : '#000000';
                    sidebar.classList.remove('bg-light', 'bg-dark');

                    // Optional: also change sidebar link or icon colors
                    sidebar.querySelectorAll('a, i, span, li, .nav-link, .nav-item')
                        .forEach(el => {
                            el.style.color = isDark ? '#ffffff' : '#000000';
                        });
                });

                localStorage.setItem('themeColor', selectedColor);
            });
        });


        // Load theme color from localStorage
        const savedColor = localStorage.getItem('themeColor');
        if (savedColor) {
            const isDark = isDarkColor(savedColor);

            headers.forEach(header => {
                header.style.backgroundColor = savedColor;
                header.style.color = isDark ? '#ffffff' : '#000000';
                header.classList.remove('bg-light', 'bg-dark');
            });

            sidebars.forEach(sidebar => {
                sidebar.style.backgroundColor = savedColor;
                sidebar.style.color = isDark ? '#ffffff' : '#000000';
                sidebar.classList.remove('bg-light', 'bg-dark');

                sidebar.querySelectorAll('a, i, span, li, .nav-link, .nav-item').forEach(el => {
                    el.style.color = isDark ? '#ffffff' : '#000000';
                });
            });

            const selectedInput = document.querySelector(`input[name="themeColor"][value="${savedColor}"]`);
            if (selectedInput) {
                selectedInput.checked = true;
            }
        }

    });
    // here profile image upload
</script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    const uploadRoute = '{{ route('profile.image.upload') }}';

    document.addEventListener('DOMContentLoaded', function() {
        const profileImageUpload = document.getElementById('profileImageUpload');
        const profileImagePreview = document.getElementById('profileImagePreview');

        if (profileImageUpload) {
            profileImageUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        profileImagePreview.src = event.target.result;
                        uploadProfileImage(file);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function uploadProfileImage(file) {
            console.log('Selected file:', file.name, file.size, file.type);

            const formData = new FormData();
            formData.append('profile_image', file);
            formData.append('_token', csrfToken);

            fetch(uploadRoute, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(errorText || 'Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Upload response:', data);
                    if (data.success) {
                        document.querySelectorAll('.profile-image').forEach(img => {
                            img.src = data.avatar_url + '?t=' + Date.now();
                        });
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Upload failed', 'error');
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    showToast('Upload failed: ' + error.message, 'error');
                });
        }

        function showToast(message, type) {
            // Implement your toast notification here or use an existing one
            const toast = document.createElement('div');
            toast.className = toast align - items - center text - white bg - $ {
                type
            }
            border - 0;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = <
                div class = "d-flex" >
                <
                div class = "toast-body" >
                $ {
                    message
                } <
                /div> <
                button type = "button"
            class = "btn-close btn-close-white me-2 m-auto"
            data - bs - dismiss = "toast"
            aria - label = "Close" > < /button> <
                /div>;

            const toastContainer = document.getElementById('toastContainer') || createToastContainer();
            toastContainer.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '11';
            document.body.appendChild(container);
            return container;
        }
    });
</script>
