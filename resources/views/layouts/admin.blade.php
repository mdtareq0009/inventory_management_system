<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>@yield('title', 'Hospital Management')</title>

    <meta name="description" content="Static &amp; Dynamic Tables" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}">
    @stack('css')
</head>

<body class="skin-2">
    @include('partials.header')

    <div class="main-container ace-save-state" id="main-container">

        @include('partials.sidebar')

        <div class="main-content">
            <div class="main-content-inner">
                <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="{{route('dashboard')}}">Home</a>
                        </li>

                        <li>
                            <a href="javascript:">@yield('breadcrumb', '')</a>
                        </li>
                    </ul>

                    <div class="nav-search" id="nav-search">
                        <span style="font-weight: bold; color: #972366; font-size: 16px;">
                            {{branchInfo()->name}}
                        </span>
                    </div>
                </div>
                
                <div class="page-content" id="app">
                    @yield('body')
                </div>
            </div>
        </div>

        @include('partials.footer')
    </div>
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('assets/js/myjs.js')}}"></script>
    @stack('js')
</body>
</html>
