<div class="row g-3 g-xl-30 mb-40">
    @forelse($results as $item)
        @switch($item->type)
            @case('project')
                <div class="col-6 col-lg-4">
                    <div class="project">
                        <a class="project__frame" href="{{ route('project_detail',['slug' => $item->slug, 'ref' => 'Dự án kêu gọi đầu tư']) }}">
                            <img src="{{$item->detail_image ?? './images/project-default.jpg' }}" alt="{{ $item->name }}"/></a>
                        <div class="project__body">
                            <h3 class="project__title">
                                <a href="{{ route('project_detail',['slug' => $item->slug, 'ref' => 'Dự án kêu gọi đầu tư']) }}" data-tippy-content="{{$item->name}}">{{$item->name}}</a>
                            </h3>
                            @if($item->is_invest == 0)
                                <div class="project__overlay"><span>Dự án đang kêu gọi đầu tư</span>
                                    <a class="project__like" href="javascript:void(0)" data-id="{{ $item->id }}" data-type="App\Models\Project"><i class="fas fa-fw fa-lg fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i></a>
                                </div>
                            @else
                                <div class="project__overlay"><span>Dự án đã có chủ đầu tư</span>
                                    <a class="project__like" href="javascript:void(0)" data-id="{{ $item->id }}" data-type="App\Models\Project"><i class="fas fa-fw fa-lg fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i></a>
                                </div>
                            @endif
                            <ul class="project__info">
                                <li>
                                    <img class="me-2" src="./images/icon-map-marker.svg" alt=""/>
                                    <span data-tippy-content="Dự án nằm trên địa bàn {{ $item->districts->pluck('name')->join(', ') }}, thành phố Hà Nội">
                                        Dự án nằm trên địa bàn {{ $item->districts->pluck('name')->join(', ') }}, thành phố Hà Nội
                                    </span>
                                </li>
                                <li>
                                    <img class="me-2" src="./images/icon-dimension.svg" alt=""/>
                                    <span>{{$item->area ?? 0}} {{$item->unit_type_text ?? ''}}</span>
                                </li>
                                <li>
                                    <img class="me-2" src="./images/icon-save-money.svg" alt=""/>
                                    <span>Theo đề xuất</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @break
                
            @case('guide')
            @case('post')
                <div class="col-6 col-lg-4">
                    @php
                        $route_name = $item->type == 'guide' ? 'investment_guide_detail' : 'post_detail';
                        $ref_name = $item->type == 'guide' ? 'Cẩm nang đầu tư' : 'Tin tức';
                        $url = route($route_name, ['id' => $item->id, 'slug' => $item->slug, 'ref' => $ref_name]);
                    @endphp
                    <div class="news">
                        <a class="news__frame" href="{{ $url }}">
                            <img src="{{$item->image}}" alt="{{ $item->name }}"/>
                        </a>
                        <div class="news__body">
                            <div class="news__info">
                                <div class="news__time">
                                    <i class="fal fa-clock me-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</span>
                                </div>
                                <a class="news__like" href="javascript:void(0)"
                                       data-id="{{ $item->id }}" data-type="{{ $item->type == 'guide' ? 'App\Models\InvestmentGuide' : 'App\Models\Post' }}">
                                        <i class="fas fa-fw fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i>
                                    </a>
                            </div>
                            <h3 class="news__title custom-desc">
                                <a href="{{ $url }}" data-tippy-content="{{$item->name}}">
                                    {{ $item->name }}
                                </a>
                            </h3>
                            <div class="news__desc text-truncate-3-lines">{{ $item->description }}</div>
                        </div>
                    </div>
                </div>
                @break
        @endswitch
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Không có kết quả nào để hiển thị trong mục {{ $type_name }} này.</p>
        </div>
    @endforelse
</div>
<nav class="d-flex justify-content-center mt-30 ajax-pagination-container" data-type="{{ $results->first()?->type }}">
    {!! $results->links() !!}
</nav>