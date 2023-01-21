<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                  <img class="navbar-logo" src="{{ asset('img/logo.png') }}" alt="Логотип Togolden">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">
                              <span>{{ __('Компании') }}</span>
                            </a>
                        </li>
                        <!-- Authentication Links -->
                        @guest
							@if (Route::has('login'))
								<li class="nav-item">
									<a class="nav-link" href="{{ route('login') }}">
										<span>{{ __('Войти') }}</span>
										<i class="fa fa-sign-in" aria-hidden="true"></i>
									</a>
								</li>
							@endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                      <span>{{ __('Регистрация') }}</span>
                                      <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
								<a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									<span>{{ __('Выйти') }}</span>
									<i class="fa fa-sign-out" aria-hidden="true"></i>
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
									@csrf
								</form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
