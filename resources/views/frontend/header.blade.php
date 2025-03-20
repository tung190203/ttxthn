<desktop-header inline-template>
    <div class="tw-hidden md:tw-block homepage-background">
        <div id="miss-modal-activator" style="height: 5px; position: fixed; top: 0; z-index: 100; width: 100%;"></div>
        <header class="container tw-flex tw-justify-between">
            <div class="tw-flex tw-items-center tw-w-1/3 tw-mr-8">
                <a href="{{ route('home_page') }}" style="position: relative; top: -1rem; width:100%;" class="tw-block">
                    <img class="sized-img"
                         src="{{ $setting['logo'] }}"
                         alt="{{ $setting['site_name'] }}"
                         width="379"
                         height="70">
                </a>
            </div>
            <div class="tw-flex tw-flex-col tw-items-end tw-w-2/3 tw-ml-8">
                <div class="tw-flex tw-flex-col tw-items-end tw-w-full tw-my-2">
                    <ul class="tw-list-reset tw-flex tw-justify-between tw-items-center tw-text-xs">
                        @foreach($share['top_menu'] as $top_menu)
                            <a href="{{ $top_menu['href'] }}" class="tw-text-grey tw-pl-4">
                                <li>{{ $top_menu['name'] }}</li>
                            </a>
                        @endforeach
                    </ul>
                </div>
                <merchant-search inline-template>
                    <div class="nav-search tw-relative tw-w-full tw-z-20">
                        <div class="tw-flex">
                            <input type="text" name="merchant" placeholder="Search your favorite merchants..."
                                   class="tw-flex tw-flex-grow tw-rounded-l-sm tw-text-grey-darker tw-p-2">
                            <a class="tw-flex tw-items-center tw-rounded-r-sm tw-text-grey-lightest tw-bg-blue">
                                <i class="tw-p-4 fa fa-search"></i>
                            </a>
                        </div>
                    </div>
                </merchant-search>
                <ul class="tw-list-reset tw-flex tw-justify-between tw-w-full tw-my-4">
                    @foreach($share['main_menu'] as $main_menu)
                        <a href="{{ $main_menu['href'] }}"
                           class="tw-text-grey-lightest hover:tw-text-blue tw-font-normal tw-pr-2">
                            <li>{{ $main_menu['name'] }}</li>
                        </a>
                    @endforeach
                </ul>
            </div>
        </header>
    </div>
</desktop-header>
<mobile-header inline-template>
    <header class="tw-relative md:tw-hidden tw-text-grey-lighter">
        <nav class="tw-flex tw-justify-between tw-text-center tw-bg-grey-darkest tw-text-2xl tw-py-2 tw-px-4">
            <span class="nav-menu-button tw-w-6 tw-cursor-pointer">
                <i class="fa fa-bars tw-h-full"></i>
            </span>
            <a href="{{ route('home_page') }}">
                <img src="{{ $setting['logo'] }}" alt="{{ $setting['site_name'] }}"
                     class="sized-img tw-h-10" width="719" height="132" loading="auto">
            </a>
            <span class="nav-search-button tw-w-6 tw-cursor-pointer">
                <i class="fa fa-search"></i>
            </span>
        </nav>
        <merchant-search inline-template>
            <div dusk="merchant-search" class="nav-search tw-hidden tw-bg-blue tw-w-full tw-z-20">
                <div class="tw-flex tw-p-2">
                    <input type="text" name="merchant" placeholder="Search your favorite merchants..."
                           class="tw-flex tw-flex-grow tw-rounded-sm tw-text-grey-darker tw-h-10 tw-p-2">
                    <a class="tw-flex tw-items-center tw-rounded-r-sm tw-text-grey-lightest tw-bg-blue">
                        <i class="tw-pl-4 tw-pr-2 fa fa-search"></i>
                    </a>
                </div>
            </div>
        </merchant-search>
        <div class="nav-menu tw-hidden tw-bg-blue tw-z-40">
            <ul class="tw-list-reset tw-text-xl">
                <li class="nav-menu-button tw-cursor-pointer tw-text-right te-text-grey tw-text-2xl tw-px-6 tw-py-4">
                    <i class="fa fa-close"></i>
                </li>
                @foreach($share['main_menu'] as $main_menu)
                    <li class="tw-px-6 tw-py-4">
                        <a href="{{ $main_menu['href'] }}" class="tw-text-grey-lightest">{{ $main_menu['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </header>
</mobile-header>