<div id="navbar" class="navbar navbar-default ace-save-state navbar-fixed-top" style="background:#ab1014 !important;">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="{{route('dashboard')}}" class="navbar-brand">
                <small>
                    <i class="fa fa-leaf"></i>
                    {{companyProfile()->name}}
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">

                {{-- @if(auth()->user()->role == 'Super Admin')
                @php
                    $branches = App\Models\Branch::all();
                @endphp
                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <big>Branch Acess</big>
                            <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        @foreach($branches as $branch)
                        <li>
                            <a class="btn-add branch-access" href="javascript:" data-id="{{$branch->id}}">
                                <i class="ace-icon fa fa-bank"></i>
                                {{$branch->name}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>	
                @endif --}}

                <li class="clock_li">
                    <a class="clock" style="background:#ab1014 !important;">
                        <span style="font-size:20px;"><i class="ace-icon fa fa-clock-o"></i></span> <span style="font-size:15px;">@php date_default_timezone_set('Asia/Dhaka') @endphp {{date("l, d F Y")}},&nbsp;<span id="timer" style="font-size:15px;"></span></span>
                    </a>
                </li>
                


                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background:#ab1014 !important;">
                        <img class="nav-user-photo" src="{{asset('assets/img/no_image.png')}}" alt="" />

                        <span class="user-info">
                            <small>Welcome,</small>
                            {{auth()->user()->name}}
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="#">
                                <i class="ace-icon fa fa-user"></i>
                                Profile
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <form id="logout_form" method="POST" action="{{ route('logout') }}">
                                @csrf
                            </form>
                            <a href="javascript:" onclick="event.preventDefault();$('#logout_form').submit();">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
