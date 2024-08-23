@extends('layouts.admin')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('body')

@php 
 $role = auth()->user()->role;
 $getPer = auth()->user()->permissions;
 $getPer = preg_replace('/[^A-Za-z0-9\-\,\_]/', '', $getPer);
 $permissions =explode(',', $getPer);
 $module = session()->has('module') ? session('module') : '';
 $branch_id = session()->has('branch_id') ? session('branch_id') : auth()->user()->branch_id;
 $all_access_role = ['Super Admin', 'Admin'];
 $doctor_role = ['Doctor'];


 
@endphp

@if($module == 'Dashboard' || $module == '')

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="col-md-12 header" style="height: 80px;">
            <h3>Welcome To Inventory Managment System</h3>
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
                <h3> Inventory</h3>
            </div>

            @if (array_search("purchase_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('purchase_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-shopping-cart"></i>
                            </div>
                            <div class="textModule">
                                Purchase Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
            @if (array_search("sale_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('sale_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-shopping-basket"></i>
                            </div>
                            <div class="textModule">
                                Sale Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
            @if (array_search("purcahse_return_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('purcahse_return_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-repeat"></i>
                            </div>
                            <div class="textModule">
                                Purchase Return Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
            @if (array_search("sale_return_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('sale_return_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-undo"></i>
                            </div>
                            <div class="textModule">
                                Sale Return Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
            @if (array_search("purchase_record", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('purchase_record')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-list-ul"></i>
                            </div>
                            <div class="textModule">
                                Purchase Record
                            </div>
                        </a>
                    </div>
                </div>
           @endif
           @if (array_search("purchase_return_record", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('purchase_return_record')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-list-ul"></i>
                            </div>
                            <div class="textModule">
                                Purchase Return Record
                            </div>
                        </a>
                    </div>
                </div>
           @endif
            @if (array_search("sale_record", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('sale_record')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-list-ol"></i>
                            </div>
                            <div class="textModule">
                                Sale Record
                            </div>
                        </a>
                    </div>
                </div>
           @endif
            @if (array_search("sale_return_record", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('sale_return_record')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-list-ol"></i>
                            </div>
                            <div class="textModule">
                                Sale Return Record
                            </div>
                        </a>
                    </div>
                </div>
           @endif
         
            @if (array_search("product_list", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('product_list')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-list"></i>
                            </div>
                            <div class="textModule">
                                Product List
                            </div>
                        </a>
                    </div>
                </div>
           @endif
            @if (array_search("stock", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('stock')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-stack-exchange"></i>
                            </div>
                            <div class="textModule">
                                Stock
                            </div>
                        </a>
                    </div>
                </div>
           @endif
         
            
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
                <h3> Administration </h3>
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
           @if (array_search("product_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('product_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-product-hunt"></i>
                            </div>
                            <div class="textModule">
                                Product Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
           @if (array_search("category_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('category_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-calendar-plus-o"></i>
                            </div>
                            <div class="textModule">
                                category Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
           @if (array_search("unit_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('unit_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-underline"></i>
                            </div>
                            <div class="textModule">
                                Unit Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
          
           @if (array_search("supplier_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('supplier_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-user-plus"></i>
                            </div>
                            <div class="textModule">
                                Supplier Entry
                            </div>
                        </a>
                    </div>
                </div>
           @endif
           @if (array_search("customer_entry", $permissions) > -1 || in_array($role, $all_access_role))
                <div class="col-md-2 col-xs-6 ">
                    <div class="col-md-12 section20">
                        <a href="{{route('customer_entry')}}">
                            <div class="logo">
                                <i class="menu-icon fa fa-user-plus"></i>
                            </div>
                            <div class="textModule">
                                Customer Entry
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