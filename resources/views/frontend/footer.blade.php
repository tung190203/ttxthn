<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 footer-widget">
                <h4 class="widgettitle">Subscribe to get hand-picked deals direct to your inbox</h4>
                <form method="post" action="#!">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control input-subscribe" placeholder='Enter Your Email Address'
                                   name="email" type="text">
                            <span class="input-group-btn">
                                  <button class="btn btn-primary btn-subscribe" type="button">Sign Up</button>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="footer-social">
                    <a href="{{ $setting['facebook'] }}" target="_blank"><i class="fa fa-facebook"></i></a>
                    <a href="{{ $setting['instagram'] }}" target="_blank"><i class="fa fa-instagram"></i></a>
                    <a href="{{ $setting['twitter'] }}" target="_blank"><i class="fa fa-twitter"></i></a>
                </div>
                <h4 class="disclaimertitle">Disclaimer</h4>
                {!! $setting['footer_info'] !!}
            </div>
            <div class="col-md-2"></div>
            @foreach($share['footer_menu'] as $footer_menu)
                <div class="col-sm-3 col-md-3 footer-widget">
                    <h4 class="widgettitle">{{ $footer_menu['name'] }}</h4>
                    <ul>
                        @foreach($footer_menu['children'] as $submenu)
                            <li><a href="{{ $submenu['href'] }}">{{ $submenu['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
    <div class="copyrights">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <p class="pb-10px">
                        {!! $setting['copyright'] !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>