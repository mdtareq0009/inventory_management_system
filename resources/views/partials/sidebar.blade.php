<div id="sidebar" class="sidebar responsive ace-save-state sidebar-fixed sidebar-scroll">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <a href="#" class="btn btn-success">
                <i class="ace-icon fa fa-signal"></i>
            </a>

            <a href="#" class="btn btn-info">
                <i class="ace-icon fa fa-pencil"></i>
            </a>

            <a href="#" class="btn btn-warning">
                <i class="ace-icon fa fa-users"></i>
            </a>
            
            <a href="#" class="btn btn-danger">
                <i class="ace-icon fa fa-cogs"></i>
            </a>
        </div>

        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>

            <span class="btn btn-info"></span>

            <span class="btn btn-warning"></span>

            <span class="btn btn-danger"></span>
        </div>
    </div>

    @include('partials.menu')

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>