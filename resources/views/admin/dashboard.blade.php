@extends('layouts.admin')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('body')

@php 
 $role = auth()->user()->role;
 $permissions = auth()->user()->permissions;
 $module = session()->has('module') ? session('module') : '';
 $branch_id = session()->has('branch_id') ? session('branch_id') : auth()->user()->branch_id;
 $all_access_role = ['Super Admin', 'Admin'];
 $doctor_role = ['Doctor'];


 
@endphp

@if($module == 'Dashboard' || $module == '')

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="col-md-12 header" style="height: 80px;">
            <h3>Welcome To Inventroy Managment System</h3>
        </div>
       
        
                
            <div class="col-md-4 col-xs-6 section4">
                <div class="col-md-12 section122" style="background-color:#dcf5ea;" onmouseover="this.style.background = '#bdecd7'" onmouseout="this.style.background = '#dcf5ea'">
                    <a href="{{route('module', 'Inventory')}}">
                        <div class="logo">
                            <i class="fa fa-cubes"></i>
                        </div>
                        <div class="textModule">
                            Inventory Module
                        </div>
                    </a>
                </div>
            </div>

           

            <div class="col-md-4 col-xs-6 section4">
                <div class="col-md-12 section122" style="background-color:#A7ECFB;" onmouseover="this.style.background = '#A7ECFB'" onmouseout="this.style.background = '#ecffd9'">
                    <a href="{{route('module', 'Administration')}}">
                        <div class="logo">
                            <i class="fa fa-cogs"></i>
                        </div>
                        <div class="textModule">
                            Administration
                        </div>
                    </a>
                </div>
            </div>
       
             <div class="col-md-4 col-xs-6 section4">
                <div class="col-md-12 section122" style="background-color:#A7ECFB;" onmouseover="this.style.background = '#A7ECFB'" onmouseout="this.style.background = '#ecffd9'">
                    <form id="logout_form" method="POST" action="{{ route('logout') }}">
                        @csrf
                    </form>
                    <a href="javascript:" onclick="event.preventDefault();$('#logout_form').submit();">
                        <div class="logo">
                            <i class="fa fa fa-power-off"></i>
                        </div>
                        <div class="textModule">
                           Logout
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@elseif($module == 'Inventory')
<div class="row">
    <div class="col-md-12 col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <!-- Header Logo -->
            <div class="col-md-12 header">
                <h3> Inventory Module </h3>
            </div>
            
    </div><!-- /.col -->
</div><!-- /.row -->

@elseif($module == 'Administration')
<div class="row">
    <div class="col-md-12 col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <!-- Header Logo -->
            <div class="col-md-12 header">
                <h3> Administration Module </h3>
            </div>
              
          
            @if (in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('company_profile')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-bed"></i>
                            </div>
                            <div class="textModule">
                                <span class="menu-text"> Company Profile</span>
                            </div>
                        </a>
                    </div>
                </div>
           @endif   
           @if (in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('register')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-plus"></i>
                            </div>
                            <div class="textModule">
                                <span class="menu-text">  Create User </span>
                            </div>
                        </a>
                    </div>
                </div>
           @endif   
           

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endif

@endsection

@push('css')
<style>

.widgets {
   
        width: 100%;
        min-height: 50px;
        padding: 8px;
        box-shadow: 0px 1px 2px #454545;
        border-radius: 3px;
        text-align: center;
    }
    .widgets .widget-icon {
        width: 40px;
        height: 40px;
        padding-top: 8px;
        padding-left: 5px;
        border-radius: 50%;
        color: white;
    }
    .widgets .widget-content {
        flex-grow: 2;
        font-weight: 500;
    }
    .widgets .widget-content .widget-text {
        font-size: 13px;
        color: #fff;
    }
    .widgets .widget-content .widget-value {
        font-size: 25px;
        color: #fff;
    }


</style>
@include('partials.dashboard_style')
@endpush