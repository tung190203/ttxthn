<nav class="main-header navbar navbar-expand navbar-dark navbar-gray-dark">
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" data-widget="pushmenu" href="#!">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('backend_dashboard') }}" class="nav-link d-flex align-items-center">Home</a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('home_page') }}" target="_blank" title="Xem website">
                <i class="fas fa-globe-americas mr-1"></i> Xem website
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell"></i>
                @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                <span class="badge badge-warning navbar-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 320px;">
                @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                <span class="dropdown-item dropdown-header">{{ Auth::user()->unreadNotifications->count() }} thông báo chưa đọc</span>
                <div class="dropdown-divider"></div>
                @foreach(Auth::user()->unreadNotifications->take(5) as $notification)
                <a href="{{ route('backend_notification_read', $notification->id) }}" class="dropdown-item">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-sm" style="white-space: normal; line-height: 1.5; margin-bottom: 0;">
                                <i class="fas fa-circle text-warning mr-1" style="font-size: 10px;"></i> {!! $notification->data['message'] ?? 'Có thông báo mới' !!}
                            </p>
                            <p class="text-sm text-muted mt-1 mb-0"><i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                @endforeach
                <a href="{{ route('backend_notification_read_all') }}" class="dropdown-item dropdown-footer text-center">Đánh dấu đã đọc tất cả</a>
                @else
                <span class="dropdown-item dropdown-header">Không có thông báo mới</span>
                @endif
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link hmg-navbar-profile" data-toggle="dropdown" href="#!" title="Profiles">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('backend_assets/images/logo.png') }}"
                    alt="User Avatar"
                    class="hmg-navbar-profile__avatar"
                    id="header-avatar-preview">
                <span class="hmg-navbar-profile__info">
                    <span class="hmg-navbar-profile__name" id="header-user-name">{{ Auth::user()->name ?? '' }}</span>
                    <span class="hmg-navbar-profile__email" id="header-user-email">{{ Auth::user()->email ?? '' }}</span>
                </span>
                <i class="fas fa-angle-down hmg-navbar-profile__caret"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#!" class="dropdown-item" data-toggle="modal" data-target="#adminProfileModal">
                    <i class="fas fa-info-circle mr-2 text-info"></i>
                    Thông tin cá nhân
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer" onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">Đăng xuất</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>

<!-- Modal Cập nhật Profile -->
<div class="modal fade" id="adminProfileModal" tabindex="-1" role="dialog" aria-labelledby="adminProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminProfileModalLabel">Thông tin cá nhân</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="adminProfileForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group text-center">
                        <div class="position-relative d-inline-block">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('backend_assets/images/logo.png') }}"
                                alt="Avatar Preview"
                                id="profileAvatarPreview"
                                class="img-circle elevation-2"
                                style="width: 100px; height: 100px; object-fit: cover; cursor: pointer"
                                onclick="document.getElementById('profileAvatarInput').click()">
                            <div class="profile-avatar-camera" onclick="document.getElementById('profileAvatarInput').click()">
                                <i class="fas fa-camera text-white"></i>
                            </div>
                        </div>
                        <input type="file" id="profileAvatarInput" name="avatar_file" accept="image/*" style="display: none;" onchange="previewProfileAvatar(this)">
                        <small class="form-text text-muted mt-2">Nhấn vào ảnh để thay đổi Avatar</small>
                    </div>

                    <div class="form-group">
                        <label for="profileName">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="profileName" name="name" value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="profileEmail">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="profileEmail" name="email" value="{{ Auth::user()->email }}" required>
                    </div>

                    <hr>
                    <h6>Đổi mật khẩu (Bỏ trống nếu không muốn đổi)</h6>

                    <div class="form-group">
                        <label for="profileCurrentPassword">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="profileCurrentPassword" name="current_password" placeholder="Nhập mật khẩu hiện tại">
                    </div>

                    <div class="form-group">
                        <label for="profileNewPassword">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="profileNewPassword" name="password" placeholder="Nhập mật khẩu mới">
                        <small class="form-text text-muted">Mật khẩu phải lớn hơn 8 ký tự, chứa ít nhất 1 chữ hoa và 1 ký tự đặc biệt.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveProfile">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewProfileAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profileAvatarPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('adminProfileForm');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let btn = document.getElementById('btnSaveProfile');
                let spinner = btn.querySelector('.spinner-border');

                // Reset styling
                btn.disabled = true;
                spinner.classList.remove('d-none');
                // Using fetch API replacing jQuery
                fetch("{{ route('backend.profile.update') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI seamlessly
                            document.getElementById('header-user-name').innerText = data.data.name;
                            document.getElementById('header-user-email').innerText = data.data.email;
                            document.getElementById('header-avatar-preview').src = data.data.avatar;

                            // Clear password fields
                            document.getElementById('profileCurrentPassword').value = '';
                            document.getElementById('profileNewPassword').value = '';

                            // Optional: auto-close after a delay
                            setTimeout(() => {
                                $('#adminProfileModal').modal('hide');
                            }, 500);

                            if (typeof toastr !== 'undefined') {
                                toastr.success(data.message);
                            }
                        } else if (data.errors) {
                            let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                            for (const key in data.errors) {
                                errorHtml += '<li>' + data.errors[key][0] + '</li>';
                            }
                            errorHtml += '</ul></div>';
                            toastr.error(errorHtml);
                        }
                    })
                    .catch(error => {
                        toastr.error('Đã có lỗi xảy ra. Vui lòng thử lại.');
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        btn.disabled = false;
                        spinner.classList.remove('d-none');
                        spinner.classList.add('d-none');
                    });
            });
        }
    });
</script>
