<!DOCTYPE HTML>
<!--[if IE 6]><html lang="en" class="ielt7  ielt8  ielt9 ielt10 en"><![endif]-->
<!--[if IE 7]><html lang="en" class="ie7  ielt8  ielt9 ielt10 en"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="ie8 ielt9 ielt10 en"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="ie9 ielt10 en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class=" en"><!--<![endif]-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CMS - @yield('title')</title>
    <meta name="keywords" content="{{ $keywords or '' }}" />
    <meta name="description" content="{{ $description or '' }}" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.css')<!--common css resources-->
    @yield('css')
    <![endif]-->
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

    @include('layouts.header')
            <!-- Left side column. contains the logo and sidebar -->
    @include('layouts.menu')

            <!-- Content Wrapper. Contains page content -->
    @yield('content')
            <!-- /.content-wrapper -->
    @include('layouts.footer')
</div>
<!-- ./wrapper -->
@include('layouts.js')<!--common js resources-->
@yield('js')
</body>
</html>
