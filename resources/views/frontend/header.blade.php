@php
$listProjectHeader = App\Models\Project::orderBy('is_pinned','desc')->orderByRaw('CASE WHEN pin_order IS NULL THEN 999999 ELSE pin_order END ASC')->orderBy('updated_at', 'desc')->take(5)->get();
$countAllProject = App\Models\Project::count();
@endphp
<header class="header">
    <div class="header__wrapper">
        <div class="container">
            <div class="header__inner">
                <a class="header__logo" href="{{ route('home_page') }}">
                    <img src="{{asset('./images/logo-new.png')}}" alt=""/>
                </a>
                <div class="header__elements">
                    <div class="header__text">CÁC DỰ ÁN THU HÚT ĐẦU TƯ THÀNH PHỐ HÀ NỘI</div>
                </div>
                <button class="btn-toggle text-white d-xl-none js-navbar-toggle ms-1"></button>
            </div>
        </div>
        <nav class="navigation">
            <div class="container">
                <div class="navigation__inner">
                    <section class="navbar js-navbar">
                        <div class="navbar__backdrop js-navbar-toggle"></div>
                        <div class="navbar__wrapper">
                            <div class="navbar__header">
                                <a class="navbar__logo" href="./index.html">
                                    <img src="{{asset('./images/logo-new.png')}}" alt=""/>
                                </a>
                                <button class="btn-toggle js-navbar-toggle ms-auto"></button>
                            </div>
                            <div class="navbar__body">
                                <ul class="menu menu-root">
                                    <li class="menu-item">
                                        <a class="menu-link @if(empty($setting['menu_active'])) active @endif"
                                           href="{{ route('home_page') }}">Trang chủ</a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link @if(($setting['menu_active']??'') == 'cam-nang-dau-tu' ) active @endif"
                                           href="{{ route('category',['slug' => 'cam-nang-dau-tu']) }}">Cẩm nang đầu tư</a>
                                    </li>
                                    <li class="menu-item menu-item-group">
                                        <a class="menu-link @if(($setting['menu_active']??'') == 'du-an-keu-goi-dau-tu' ) active @endif" href="{{ route('projects') }}">Dự án kêu gọi đầu tư
                                            <span
                                        class="badge bg-danger ms-2">{{ $countAllProject ?? 0 }}</span>
                                        </a>
                                        <span class="menu-toggle"></span>
                                        <ul class="menu menu-sub custom-menu-header">
                                            @foreach ($listProjectHeader as $item)
                                                <li class="menu-item">
                                                    <a class="menu-link" href="{{route('project_detail',['slug' => $item->slug])}}">{{$item->name}}</a>
                                                </li>
                                            @endforeach
                                            <li class="menu-item">
                                                <a class="menu-link text-center" href="{{ route('projects') }}">Xem thêm</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="menu-item"><a
                                                class="menu-link @if(($setting['menu_active']??'') == 'tin-tuc' ) active @endif"
                                                href="{{ route('category',['slug'=> 'tin-tuc']) }}">Tin tức</a>
                                    </li>
                                    <li class="menu-item"><a
                                        class="menu-link @if(($setting['menu_active']??'') == 'lien-he' ) active @endif"
                                        href="{{ route('contact') }}">Liên hệ</a>
                            </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                    <form class="search">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Tìm kiếm..." size="1"/>
                            <button class="input-group-text"><i class="far fa-search"></i></button>
                        </div>
                    </form>
                    <button class="h-btn d-none d-xl-flex ms-1 js-search-btn" type="button"><i
                                class="fal fa-fw fa-lg fa-search"></i></button>
                    <div class="h-dropdown">
                        <div class="h-dropdown__toggle">
                            <button class="h-btn ms-1" type="button"><i class="fal fa-fw fa-lg fa-user"></i></button>
                        </div>
                        <div class="h-dropdown__menu">
                            <a class="h-dropdown__item" href="{{ route('account') }}">Thông tin cá nhân</a>
                            <a class="h-dropdown__item" href="{{ route('account') }}">Dự án đã lưu</a>
                            <a class="h-dropdown__item"
                               href="#!"
                               data-bs-toggle="modal"
                               data-bs-target="#md-sign-in">Đăng nhập</a></div>
                    </div>
                    <div class="h-dropdown ms-2">
                        <div class="h-dropdown__toggle">
                            <img src="{{asset('./images/vn.svg')}}" alt=""/><i
                                    class="fal fa-fw fa-angle-down ms-1"></i></div>
                        <div class="h-dropdown__menu"><a class="h-dropdown__item" href="#!">
                                <img src="{{asset('./images/vn.svg')}}"
                                     alt=""/><span>Tiếng Việt</span></a>
                            <a class="h-dropdown__item" href="#!">
                                <img src="{{asset('./images/gb.svg')}}"
                                     alt=""/><span>English</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>