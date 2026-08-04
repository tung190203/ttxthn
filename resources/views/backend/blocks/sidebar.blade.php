<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('backend_dashboard') }}" class="brand-link hmg-brand-link">
        <img src="{{ asset('backend_assets/images/hmglogo.png') }}" alt="{{ config('cms.name') }}"
            class="hmg-brand-logo hmg-brand-logo--full">
        <img src="{{ asset('backend_assets/images/logomini.png') }}" alt="{{ config('cms.name') }}"
            class="hmg-brand-logo hmg-brand-logo--mini">
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @foreach(config('cms.backend_module') as $backend_module)
                @if(!empty($backend_module['title']))
                @canany(array_keys($backend_module['items']))
                <li class="nav-header">{{ strtoupper($backend_module['title']) }}</li>
                @endcanany
                @endif
                @foreach($backend_module['items'] as $backend_parent_key => $backend_items)
                @if(!empty($backend_items['items']))
                @can($backend_parent_key)
                <li class="nav-item has-treeview @if($selectedMainMenu == $backend_parent_key) menu-open @endif">
                    <a href="#!"
                        class="nav-link @if($selectedMainMenu == $backend_parent_key) active @endif">
                        <i class="nav-icon {{ $backend_items['icon'] }}"></i>
                        <p>
                            {{ $backend_items['title'] }}
                            @if(isset($pendingCounts[$backend_parent_key]) && $pendingCounts[$backend_parent_key] > 0)
                                <span class="badge badge-danger right" style="right: 2.5rem;">{{ $pendingCounts[$backend_parent_key] }}</span>
                            @endif
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($backend_items['items'] as $backend_key => $backend_item )
                        @php
                        $permissionKey = $backend_parent_key === 'user' ? $backend_key : $backend_parent_key . '/' . $backend_key;
                        @endphp
                        @can($permissionKey)
                        <li class="nav-item">
                            <a href="{{ route($backend_item['route']) }}"
                                class="nav-link @if(!empty($selectedSubMenu) && $selectedSubMenu == $backend_key) active @endif">
                                <i class="nav-icon fas fa-angle-right"></i>
                                <p>{{ $backend_item['title'] }}</p>
                            </a>
                        </li>
                        @endcan
                        @endforeach
                    </ul>
                </li>
                @endcan
                @else
                @can($backend_parent_key)
                <li class="nav-item">
                    <a href="{{ route($backend_items['route']) }}"
                        class="nav-link @if($selectedMainMenu == $backend_parent_key) active @endif">
                        <i class="nav-icon {{ $backend_items['icon'] }}"></i>
                        <p>
                            {{ $backend_items['title'] }}
                            @if(isset($pendingCounts[$backend_parent_key]) && $pendingCounts[$backend_parent_key] > 0)
                                <span class="badge badge-danger right">{{ $pendingCounts[$backend_parent_key] }}</span>
                            @endif
                        </p>
                    </a>
                </li>
                @endcan
                @endif
                @endforeach
                @endforeach

            </ul>
        </nav>
    </div>
</aside>
