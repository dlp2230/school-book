<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>通信录CMS - 登录</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/lte_resources/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/lte_resources/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/lte_resources/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/lte_resources/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/lte_resources/plugins/iCheck/square/blue.css">

    <style>
        canvas{position: absolute; top: 0px;z-index: -999;}
    </style>
</head>
<body class="hold-transition login-page" style="background: #4a4a4a; height: 100%; overflow: hidden;">
<div style="width: 100%"  id="particles-js">
    <div class="login-box">
        <div class="login-logo">
            <a href="javascript:;"  style="color: #fff0ff;"><b>CMS后台</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">CMS商户后台</p>
            <form id="login-form">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="账号">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="密码">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
            </form>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="auth"> 记住账号
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="login">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.login-box-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="/lte_resources/bower_components/jquery/dist/jquery.min.js"></script>

{{--<!-- scripts -->--}}
<script src="/lte_resources/bower_components/particles.js/js/particles.js"></script>
<script src="/lte_resources/bower_components/particles.js/js/app.js"></script>

<script src="/plugins/layer/layer.js"></script>
<script src="/js/base.js"></script>
</body>
</html>
<script>
    $(function () {
        $('#login').click(function () {
            base.universalSubJump("/login" , "/" , "#login-form");
        })
    })
</script>