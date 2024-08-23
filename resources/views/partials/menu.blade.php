@php 
 $role = auth()->user()->role;
 $getPer = auth()->user()->permissions;
 $getPer = preg_replace('/[^A-Za-z0-9\-\,\_]/', '', $getPer);
 $permissions =explode(',', $getPer);
 $module = session()->has('module') ? session('module') : '';
 $branch_id = session()->has('branch_id') ? session('branch_id') : auth()->user()->branch_id;
 $all_access_role = ['Super Admin', 'Admin'];
@endphp
@if($module == 'Dashboard' || $module == '')
<ul class="nav nav-list">
    <li class="active">
        <a href="{{route('dashboard')}}">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Dashboard </span>
        </a>
    </li>


   

    <li>
        <a href="{{route('module', 'Inventory')}}">
            <i class="menu-icon fa fa-cubes"></i>
            <span class="menu-text"> Inventory Module </span>
        </a>
    </li>

   

    <li>
        <a href="{{route('module', 'Administration')}}">
            <i class="menu-icon fa fa-cogs"></i>
            <span class="menu-text"> Administration </span>
        </a>
    </li>
    
    
</ul>

@elseif($module == 'Inventory')
<ul class="nav nav-list">
    <li class="active">
        <a href="{{route('dashboard')}}">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Dashboard </span>
        </a>
    </li>
    <li>
        <a href="{{route('module', 'Inventory')}}" class="module_title">
            <span> Inventory Module </span>
        </a>
    </li>

    @if (array_search("purchase_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('purchase_entry')}}">
            <i class="menu-icon fa fa-shopping-cart"></i>
            <span class="menu-text"> Purchase Entry </span>
        </a>
    </li>
    @endif
    @if (array_search("sale_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('sale_entry')}}">
            <i class="menu-icon fa fa-shopping-basket"></i>
            <span class="menu-text"> Sale Entry </span>
        </a>
    </li>
    @endif
    @if (array_search("purcahse_return_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('purcahse_return_entry')}}">
            <i class="menu-icon fa fa-repeat"></i>
            <span class="menu-text"> Pur. Return Entry </span>
        </a>
    </li>
    @endif
    @if (array_search("sale_return_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('sale_return_entry')}}">
            <i class="menu-icon fa fa-undo"></i>
            <span class="menu-text"> Sale Return Entry </span>
        </a>
    </li>
    @endif
  

    @if (
        array_search("purchase_record", $permissions) > -1
        || array_search("sale_record", $permissions) > -1
        || array_search("sale_return_record", $permissions) > -1
        || array_search("product_list", $permissions) > -1
        || array_search("stock", $permissions) > -1
        || array_search("purchase_return_record", $permissions) > -1
        || in_array($role, $all_access_role)
    )
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-list"></i>
            <span class="menu-text"> Reports </span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        
        <ul class="submenu">
            @if (array_search("purchase_record", $permissions) > -1 || in_array($role, $all_access_role))
                <li>
                    <a href="{{route('purchase_record')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Purchase
                    </a>
                </li>
            @endif
        </ul>
        <ul class="submenu">
            @if (array_search("purchase_return_record", $permissions) > -1 || in_array($role, $all_access_role))
                <li>
                    <a href="{{route('purchase_return_record')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Pur. Return
                    </a>
                </li>
            @endif
        </ul>
        <ul class="submenu">
            @if (array_search("sale_record", $permissions) > -1 || in_array($role, $all_access_role))
                <li>
                    <a href="{{route('sale_record')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Sale
                    </a>
                </li>
            @endif
        </ul>
        <ul class="submenu">
            @if (array_search("sale_return_record", $permissions) > -1 || in_array($role, $all_access_role))
                <li>
                    <a href="{{route('sale_return_record')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Sale Return
                    </a>
                </li>
            @endif
        </ul>
        <ul class="submenu">
            @if (array_search("product_list", $permissions) > -1 || in_array($role, $all_access_role))
                <li>
                    <a href="{{route('product_list')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Product List
                    </a>
                </li>
            @endif
        </ul>
        <ul class="submenu">
            @if (array_search("stock", $permissions) > -1 || in_array($role, $all_access_role))
                <li>
                    <a href="{{route('stock')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Stock
                    </a>
                </li>
            @endif
        </ul>
       
       
    </li>
    @endif
    
    
</ul>

@elseif($module == 'Administration')
<ul class="nav nav-list">
    <li class="active">
        <a href="{{route('dashboard')}}">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Dashboard </span>
        </a>
    </li>
    <li>
        <a href="{{route('module', 'Administration')}}" class="module_title">
            <span>Administration</span>
        </a>
    </li>
   
    @if (array_search("category_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('category_entry')}}">
            <i class="menu-icon fa fa-calendar-plus-o"></i>
            <span class="menu-text"> Category Entry </span>
        </a>
    </li>
    @endif
    @if (array_search("unit_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('unit_entry')}}">
            <i class="menu-icon fa fa-underline"></i>
            <span class="menu-text"> Unit Entry </span>
        </a>
    </li>
    @endif
    @if (array_search("product_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('product_entry')}}">
            <i class="menu-icon fa fa-product-hunt"></i>
            <span class="menu-text"> Product Entry </span>
        </a>
    </li>
    @endif
    @if (array_search("supplier_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('supplier_entry')}}">
            <i class="menu-icon fa fa-user-plus"></i>
            <span class="menu-text"> Supplier Entry </span>
        </a>
    </li>
    @endif
    @if (array_search("customer_entry", $permissions) > -1 || in_array($role, $all_access_role))
    <li>
        <a href="{{route('customer_entry')}}">
            <i class="menu-icon fa fa-user-plus"></i>
            <span class="menu-text"> Customer Entry </span>
        </a>
    </li>
    @endif
    @if (in_array($role, $all_access_role) && $branch_id == 1)
    <li>
        <a href="{{route('company_profile')}}">
            <i class="menu-icon fa fa-bank"></i>
            <span class="menu-text"> Company Profile </span>
        </a>
    </li>
    @endif
    @if (in_array($role, $all_access_role))
    <li>
        <a href="{{route('register')}}">
            <i class="menu-icon fa fa-user-plus"></i>
            <span class="menu-text"> Create User </span>
        </a>
    </li>
    @endif
</ul>
@endif
