@php
    $listProjectHeader = App\Models\Project::orderBy('is_pinned', 'desc')->orderByRaw('CASE WHEN pin_order IS NULL THEN 999999 ELSE pin_order END ASC')->orderBy('updated_at', 'desc')->whereNull('parent_id')->where('status', 'approved')->take(5)->get();
    $countAllProject = App\Models\Project::where('is_invest', 0)->where('status', 'approved')->count();
@endphp
<header class="header @if(request()->routeIs(['project_detail', 'investment_guide_detail', 'post_detail', 'guest.compare.show'])) header-solid @endif">
    <div class="header__wrapper">
        <nav class="navigation">
            <div class="container">
                <div class="navigation__inner">
                    <!-- Desktop Logo -->
                    <a class="header__logo d-none d-xl-flex" href="{{ route('home_page') }}" style="position: absolute; left: 0; top: 0; height: 100%; align-items: center; z-index: 9999;">
                        <img src="{{ \App\Models\Setting::getSettingByKey('logo') ?: asset('images/logo_sce.png') }}" alt="Logo" style="max-height: 80px; height: auto !important; margin: 0 !important;" />
                    </a>

                    <section class="navbar js-navbar">
                        <div class="navbar__backdrop js-navbar-toggle"></div>
                        <div class="navbar__wrapper">
                            <div class="navbar__header">
                                <a class="navbar__logo" href="{{ route('home_page') }}" style="display: flex; align-items: center; z-index: 9999;">
                                    <img src="{{ \App\Models\Setting::getSettingByKey('logo') ?: asset('images/logo_sce.png') }}" alt="Logo" style="max-height: 60px; height: auto !important; margin: 0 !important;" />
                                </a>
                                <button class="btn-toggle js-navbar-toggle ms-auto"></button>
                            </div>
                            <div class="navbar__body">
                                <ul class="menu menu-root">
                                    @foreach($share['main_menu'] as $item)
                                        <li
                                            class="menu-item @if($item['name'] == __('app.investment_projects')) menu-item-group @endif">
                                            @php
                                                $currentUrl = request()->url();
                                                $menuHref = $item['href'] ?? '';
                                                
                                                // 1. Exact match for list pages
                                                $isActive = (rtrim($currentUrl, '/') == rtrim($menuHref, '/'));
                                                
                                                // 2. Fallback to menu_active
                                                if (!$isActive && isset($setting['menu_active'])) {
                                                    if ($setting['menu_active'] == \Str::slug($item['name'])) {
                                                        $isActive = true;
                                                    }
                                                }
                                                
                                                // 3. Smart match for detail pages based on Route
                                                if (!$isActive) {
                                                    $routeName = request()->route() ? request()->route()->getName() : '';
                                                    if ($routeName == 'post_detail') {
                                                        if (str_contains($menuHref, '/tin-tuc') || str_contains($menuHref, '/news')) $isActive = true;
                                                    } elseif ($routeName == 'project_detail') {
                                                        if (str_contains($menuHref, '/projects')) $isActive = true;
                                                    } elseif ($routeName == 'investment_guide_detail') {
                                                        if (str_contains($menuHref, '/cam-nang') || str_contains($menuHref, '/investment-guide')) $isActive = true;
                                                    }
                                                }
                                                
                                                // Xác định trạng thái active cuối cùng của menu
                                                $isCurrentActive = false;
                                                if (empty($setting['menu_active']) && request()->routeIs('home_page') && $item['name'] == __('app.home')) {
                                                    $isCurrentActive = true;
                                                } elseif ($isActive) {
                                                    $isCurrentActive = true;
                                                }
                                            @endphp
                                            <a class="menu-link {{ $isCurrentActive ? 'active' : '' }}" href="{{ $item['href'] }}">
                                                {{ $item['name'] }}

                                                {{-- Hiển thị badge cho menu nhóm --}}
                                                @if($item['name'] == __('app.investment_projects'))
                                                    <span class="badge bg-danger-custom {{ $isCurrentActive ? 'bg-danger text-white border-0' : '' }} ms-2">{{ $countAllProject ?? 0 }}</span>
                                                @endif
                                            </a>

                                            {{-- Menu nhóm dự án --}}
                                            @if($item['name'] == __('app.investment_projects'))
                                                <span class="menu-toggle"></span>
                                                <ul class="menu menu-sub custom-menu-header">
                                                    @foreach($listProjectHeader as $project)
                                                        <li class="menu-item">
                                                            <a class="menu-link"
                                                                href="{{ route('project_detail', ['slug' => $project->slug, 'ref' => __('app.investment_projects')]) }}">
                                                                {{ $project->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                    <li class="menu-item">
                                                    @php
                                                        $locale = app()->getLocale() === 'vi' ? 'vn' : app()->getLocale();
                                                    @endphp
                                                        <a class="menu-link text-center"
                                                            href="{{ url($locale . '/' . __('app.projects_link')) }}">{{ __('app.view_more') }}</a>
                                                    </li>
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    </section>
                    <form class="search" method="GET" action="{{ route('search') }}">
                        <div class="input-group">
                            <input class="form-control" name="keyword" type="text" placeholder="{{ __('app.search') }}"
                                size="1" />
                            <button class="input-group-text"><i class="far fa-search"></i></button>
                        </div>
                    </form>
                    <button class="h-btn d-none d-xl-flex ms-1 js-search-btn" type="button"><i
                            class="fal fa-fw fa-lg fa-search"></i></button>
                    @if(Auth::guard('guest')->check())
                    <button class="h-btn ms-1 d-none d-md-inline-flex" type="button" onclick="document.getElementById('btnPortalFeedLauncher') && document.getElementById('btnPortalFeedLauncher').click();" title="Xem Bảng tin cập nhật (New Feed)">
                        <i class="fal fa-fw fa-lg fa-newspaper"></i>
                    </button>
                    <div class="h-dropdown">
                        <div class="h-dropdown__toggle position-relative">
                            <button class="h-btn ms-1" type="button"><i class="fal fa-fw fa-lg fa-bell"></i></button>
                            @if(Auth::guard('guest')->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; transform: translate(-50%, -20%) !important;">
                                    {{ Auth::guard('guest')->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </div>
                        <div class="h-dropdown__menu" style="min-width: 380px; max-height: 440px; overflow-y: auto; right: 0; left: auto;">
                            <div class="dropdown-header d-flex justify-content-between align-items-center" style="padding: 10px 15px; background: #f8f9fa; border-bottom: 1px solid #eee;">
                                <h6 class="text-uppercase fw-bold mb-0" style="font-size: 13px;">Thông báo của bạn</h6>
                                @if(Auth::guard('guest')->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('guest.notifications.read_all') }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 text-decoration-none" style="font-size: 12px;">Đánh dấu đã đọc</button>
                                    </form>
                                @endif
                            </div>
                            @forelse(Auth::guard('guest')->user()->notifications->take(10) as $notification)
                                <a class="h-dropdown__item d-block {{ $notification->read_at ? '' : 'fw-bold bg-light' }}" href="{{ route('guest.notifications.read', $notification->id) }}" style="white-space: normal; line-height: 1.5; border-bottom: 1px solid #eee; padding: 12px 15px;">
                                    <div style="font-size: 14px; margin-bottom: 5px;">{{ $notification->data['message'] ?? 'Bạn có thông báo mới' }}</div>
                                    <div class="text-secondary fw-normal" style="font-size: 12px;"><i class="fal fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div class="p-3 text-center text-muted">Không có thông báo mới.</div>
                            @endforelse
                            <div class="p-2 bg-light border-top text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary w-100 fw-bold" onclick="document.getElementById('btnPortalFeedLauncher') && document.getElementById('btnPortalFeedLauncher').click();" style="font-size: 12px;">
                                    <i class="fas fa-newspaper me-1"></i> Mở Bảng tin cập nhật đầy đủ (New Feed)
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="h-dropdown">
                        <div class="h-dropdown__toggle">
                            <button class="h-btn ms-1" type="button"><i class="fal fa-fw fa-lg fa-user"></i></button>
                        </div>
                        <div class="h-dropdown__menu">
                            @if(Auth::guard('guest')->check())
                                <!-- Nếu đã login -->
                                <a class="h-dropdown__item"
                                    href="{{ route('account') }}">{{ __('app.personal_information') }}</a>
                                <a class="h-dropdown__item"
                                    href="{{ route('guest.dashboard') }}">Dashboard</a>
                                <a class="h-dropdown__item" href="{{ route('guest_logout') }}">{{ __('app.logout') }}</a>
                            @else
                                <!-- Nếu chưa login -->
                                <a class="h-dropdown__item" href="#!" data-bs-toggle="modal"
                                    data-bs-target="#md-sign-in">{{ __('app.login') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="h-dropdown ms-2">
                        <div class="h-dropdown__toggle">
                            @php
                                $currentLocale = App::getLocale();
                            @endphp
                            @if($currentLocale === 'en')
                                <img src="{{ asset('images/gb.svg') }}" alt="{{ __('app.english') }}" />
                            @else
                                <img src="{{ asset('images/vn.svg') }}" alt="{{ __('app.vietnamese') }}" />
                            @endif
                            <i class="fal fa-fw fa-angle-down ms-1"></i>
                        </div>
                        @php
                            $availableLocales = config('app.available_locales');
                            $currentPath = Request::path();

                            foreach ($availableLocales as $lang) {
                                if ($currentPath === $lang || str_starts_with($currentPath, $lang . '/')) {
                                    $currentPath = ltrim(substr($currentPath, strlen($lang)), '/');
                                    break;
                                }
                            }
                            $queries = Request::except(['lang', 'page']);
                            $queryString = http_build_query($queries);
                            $querySuffix = $queryString ? '?' . $queryString : '';
                            $basePath = $currentPath ? '/' . $currentPath : '';
                        @endphp

                        <div class="h-dropdown__menu">
                            <a class="h-dropdown__item" 
                               href="{{ url('vn' . $basePath . $querySuffix) }}">
                                <img src="{{ asset('images/vn.svg') }}" alt="" />
                                <span>{{ __('app.vietnamese') }}</span>
                            </a>

                            <a class="h-dropdown__item" 
                               href="{{ url('en' . $basePath . $querySuffix) }}">
                                <img src="{{ asset('images/gb.svg') }}" alt="" />
                                <span>{{ __('app.english') }}</span>
                            </a>
                        </div>
                    </div>
                    <button class="btn-toggle js-navbar-toggle text-white d-xl-none ms-3"></button>
                </div>
            </div>
        </nav>
    </div>
</header>