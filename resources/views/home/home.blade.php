@extends('layouts.master')
@section('title', 'dashboard')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>数据面板</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua" style="background-color: #404040 !important;">
                        <div class="inner">
                            <h3>15040</h3>

                            <p>总用户数</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users" style="color: #fff0ff"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>8303</h3>

                            <p>会员人数</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-thumbs-up"  style="color: #fff0ff"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>24820</h3>

                            <p>本月新增</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-line-chart"  style="color: #fff0ff"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>65<sup style="font-size: 20px">%</sup></h3>

                            <p>会员占比</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"  style="color: #fff0ff"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">

                    <!-- solid sales graph -->
                    <div class="box box-solid bg-teal-gradient">
                        <div class="box-header">
                            <i class="fa fa-th"></i>

                            <h3 class="box-title">会员增长模型</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body border-radius-none">
                            <div class="chart" id="line-chart" style="height: 250px;"></div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer no-border">
                            <div class="row">
                                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                                    <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                                           data-fgColor="#39CCCC">

                                    <div class="knob-label">入口A</div>
                                </div>
                                <!-- ./col -->
                                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                                    <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                                           data-fgColor="#39CCCC">

                                    <div class="knob-label">入口B</div>
                                </div>
                                <!-- ./col -->
                                <div class="col-xs-4 text-center">
                                    <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                                           data-fgColor="#39CCCC">

                                    <div class="knob-label">入口C</div>
                                </div>
                                <!-- ./col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-12 connectedSortable">
                    <!-- Calendar -->
                    <div class="box box-solid bg-green-gradient">
                        <div class="box-header">
                            <i class="fa fa-calendar"></i>

                            <h3 class="box-title">Calendar</h3>
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <!-- button with a dropdown -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">Add new event</a></li>
                                        <li><a href="#">Clear events</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">View calendar</a></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                            <!-- /. tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <!--The calendar -->
                            <div id="calendar" style="width: 100%"></div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-black">
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Progress bars -->
                                    <div class="clearfix">
                                        <span class="pull-left">Task #1</span>
                                        <small class="pull-right">90%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 90%;"></div>
                                    </div>

                                    <div class="clearfix">
                                        <span class="pull-left">Task #2</span>
                                        <small class="pull-right">70%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 70%;"></div>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6">
                                    <div class="clearfix">
                                        <span class="pull-left">Task #3</span>
                                        <small class="pull-right">60%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 60%;"></div>
                                    </div>

                                    <div class="clearfix">
                                        <span class="pull-left">Task #4</span>
                                        <small class="pull-right">40%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 40%;"></div>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.box -->
                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->

        </section>
        <!-- /.content -->

    </div>
    @endsection

    @section('js')
            <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script>$("#active").addClass('active')</script>
    <script src="/lte_resources/dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/lte_resources/dist/js/demo.js"></script>

@endsection
