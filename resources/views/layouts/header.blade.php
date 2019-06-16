<header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>M</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Marketing</b>CMS通信录</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/lte_resources/dist/img/user2-128x128.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/lte_resources/dist/img/user2-128x128.png" class="img-circle" alt="User Image">
                            <p>
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div style="float:left">
                                <button class="btn btn-default btn-flat" data-toggle="modal"
                                        data-target="#header-change-password">修改密码
                                </button>
                            </div>

                            <div style="float:right">
                                <a href="/logout" class="btn btn-default btn-flat">退出登录</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<form id="change-password-form" onsubmit="return false">
    <div class="modal fade" id="header-change-password">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">修改密码</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>旧密码</label>
                        <input type="password" class="form-control" name="old_pwd" placeholder="填写旧密码">
                    </div>
                    <div class="form-group">
                        <label>新密码</label>
                        <input type="password" class="form-control" name="pwd" placeholder="填写新密码">
                    </div>
                    <div class="form-group">
                        <label>确认密码</label>
                        <input type="password" class="form-control" name="new_pwd" placeholder="再次填写新密码">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" id=""
                            data-dismiss="modal">关闭
                    </button>
                    <button type="button" onclick="" class="btn btn-primary reset-password">保存
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>

<script src="/lte_resources/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/lte_resources/bower_components/ajax-form/jquery.form.js"></script>
<!-- jQuery UI 1.11.4 -->
<script type="text/javascript">
    $(function () {
        $('.reset-password').click(function () {
            base.universalSubJump("{{url('upd_password')}}", "/" , "#change-password-form");
        })
    })
</script>