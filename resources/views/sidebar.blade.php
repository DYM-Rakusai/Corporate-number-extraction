@if( Auth::check() )
    <div class="side_accordion_box">
        <a name="side_accordion"><</a>
    </div>
    <div class="main-menu">
       <div class="main-menu__item main-menu__item--has-children">
            <div class="sidebar_head text-center">
                <!-- <span class="main-menu__text">選考管理</span> -->
                <img class="menu-logo" src="{{ asset('upload/image/rakusailogo.png') }}">
            </div>
            <ul class="sub-menu">
                @can('viewMaster')
                <li class="main-menu__item">
                    <a class="main-menu__link register" href="{{ url('/manual-add-user-page') }}">
                        <i class="fas fa-user-plus"></i>
                        <span class="main-menu__text">アカウント登録</span>
                    </a>
                </li>
                @endcan
                @can('viewBoth')
                <li class="main-menu__item">
                    <a class="main-menu__link register" href="{{ url('/user-list-page') }}">
                        <i class="fas fa-user-friends"></i>
                        <span class="main-menu__text">アカウント編集</span>
                    </a>
                </li>
                @endcan
                @can('viewBoth')
                <li class="main-menu__item">
                    <a class="main-menu__link register" href="{{ url('/manual-add-cs-page') }}">
                        <i class="fas fa-user-plus"></i>
                        <span class="main-menu__text">応募者登録</span>
                    </a>
                </li>
                @endcan
                @can('viewBoth')
                <li class="main-menu__item">
                    <a class="main-menu__link register" href="{{ url('/cs-list-page') }}">
                        <i class="fas fa-user-friends"></i>
                        <span class="main-menu__text">応募者一覧</span>
                    </a>
                </li>
                @endcan
                @can('viewBoth')
                <li class="main-menu__item">
                    <a class="main-menu__link register" href="{{ url('/set-schedule-page') }}">
                        <i class="far fa-registered"></i>
                        <span class="main-menu__text">スケジュール管理</span>
                    </a>
                </li>
                @endcan
                
                @can('viewBoth')
                <li class="main-menu__item">
                    <a class="main-menu__link register" href="{{ url('/job-mapping-page') }}">
                        <i class="far fa-building"></i>
                        <span class="main-menu__text">マッピング</span>
                    </a>
                </li>
                @endcan
                
                @can('viewBoth')
                <li class="main-menu__item">
                    <a class="main-menu__link register" href="{{ url('/black-list-page') }}">
                        <i class="far fa-building"></i>
                        <span class="main-menu__text">ブラックリスト</span>
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </div>
<div class="ps__rail-x" style="left: 0px; bottom: 0px;">
    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
</div>
<div class="ps__rail-y" style="top: 0px; right: 0px;">
    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
</div>
<input type="hidden" name="api_token" data-token="{{ $apiToken }}">
@endif
