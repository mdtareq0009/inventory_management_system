@php 
 $role = auth()->user()->role;
 $permissions = auth()->user()->permissions;
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