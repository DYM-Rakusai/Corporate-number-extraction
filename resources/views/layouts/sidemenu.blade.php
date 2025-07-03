<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->
    <script src="https://kit.fontawesome.com/742154adeb.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}{{ $versionParam }}" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}{{ $versionParam }}" rel="stylesheet">

    @if( request()->path() == 'manual-add-user-page' )
        <link href="{{ asset('css/auth/manualAddUser.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'edit-user-page' )
        <link href="{{ asset('css/auth/userEdit.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'user-list-page' )
        <link href="{{ asset('css/auth/userList.css') }}{{ $versionParam }}" rel="stylesheet">
        <link href="{{ asset('css/auth/user/userSearch.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'manual-add-cs-page' )
        <link href="{{ asset('css/auth/manualAddCs.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'edit-cs-page' )
        <link href="{{ asset('css/auth/consumerEdit.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'cs-detail-page' )
        <link href="{{ asset('css/auth/consumerDetail.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'cs-list-page' )
        <link href="{{ asset('css/auth/consumerList.css') }}{{ $versionParam }}" rel="stylesheet">
        <link href="{{ asset('css/auth/consumer/csSearch.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'black-list-page' )
        <link href="{{ asset('css/auth/blackList.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'set-schedule-page' )
        <link href="{{ asset('css/auth/schedule/scheduleDetail.css') }}{{ $versionParam }}" rel="stylesheet">
        <link href="{{ asset('css/auth/schedule/schedulePage.css') }}{{ $versionParam }}" rel="stylesheet">
    @elseif( request()->path() == 'job-mapping-page')
        <link href="{{ asset('css/auth/jobMappingPage.css') }}{{ $versionParam }}" rel="stylesheet">
    @endif
</head>
<body>
    <div id="app">
        <nav class="header navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container mr-3">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @if( Auth::check() )
            <aside class="sidebar">
            @include('sidebar')
            </aside>
        @endif
        <div class="main-container">
        @yield('content')
        </div>
    </div>
 <!-- Scripts -->
    <script type="text/javascript">
        const dymline = {};
        dymline['root'] = "{{ url('/api') }}";
        dymline['domain'] = "{{ url('') }}";
    </script>

    <script src="{{ asset('js/app.js') }}{{ $versionParam }}" defer></script>
    <script src="{{ asset('js/auth/adminCommon.js') }}{{ $versionParam }}" defer></script>

    @if( request()->path() == 'manual-add-user-page' )
        <script src="{{ asset('js/auth/manualAddUser.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'edit-user-page' )
        <script src="{{ asset('js/auth/userEdit.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'user-list-page' )
        <script src="{{ asset('js/auth/userList.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'manual-add-cs-page' )
        <script src="{{ asset('js/auth/manualAddCs.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'edit-cs-page' )
        <script src="{{ asset('js/auth/consumerEdit.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'cs-detail-page' )
        <script src="{{ asset('js/auth/consumerDetail.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'cs-list-page' )
        <script src="{{ asset('js/auth/csList.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'black-list-page' )
        <script src="{{ asset('js/auth/blackList.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'set-schedule-page' )
        <script src="{{ asset('js/auth/schedule/setSchedulePage.js') }}{{ $versionParam }}" defer></script>
        <script src="{{ asset('js/auth/schedule/updateSchedule.js') }}{{ $versionParam }}" defer></script>
    @elseif( request()->path() == 'job-mapping-page')
        <script src="{{ asset('js/auth/jobMappingPage.js') }}{{ $versionParam }}" defer></script>
    @endif
</body>
</html>
