<aside class="main-sidebar fixed">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/lte_resources/dist/img/user2-128x128.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p></p>
                <a href="javascript:;"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">菜单导航</li>

            <li id="active">
                <a href="{{url('/')}}">
                    <i class="fa fa-dashboard text-red"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-user"></i> <span>同学录管理</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu" style="">
                    <li class=""><a href="{{url('account/list')}}"><i class="fa fa-circle-o"></i>同学录列表</a></li>
                </ul>
            </li>


            <li class="header" style="margin-top: 150px;"></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<script>
    var pathname = window.location.pathname;
    var host = window.location.host;
    var protocol = window.location.protocol;
    var url = protocol + '//' + host + pathname;

    $("li a").each(function () {
        var href = $(this).attr("href");
        if (url == href) {

            $(this).parents("ul").parent("li").addClass("active");

            $(this).parent("li").addClass("active");
        }
    });
</script>

