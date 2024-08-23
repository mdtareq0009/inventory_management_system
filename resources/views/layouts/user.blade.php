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
   
    <div class="main-container ace-save-state" id="main-container">
        <div class="main-content">
            <div class="main-content-inner">
                <div class="page-content" id="app">
                    @yield('body')
                </div>
            </div>
        </div>

    </div>
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('assets/js/myjs.js')}}"></script>
    @stack('js')
</body>
</html>
