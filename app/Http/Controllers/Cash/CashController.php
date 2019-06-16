<?php

namespace App\Http\Controllers\Cash;

use App\Http\Service\Orders\OrderService;
use App\Http\Controllers\Controller;
class CashController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderService $orderService)
    {
        $param = parent::requestInput();
        $result = $orderService->getOrderList();

        $user = request()->session()->get($this->adminInfoKey);

        $viewData = [];
        $viewData['where'] = $param;
        $viewData['list'] = $result;
        $viewData['user'] = $user;
        return view('cash.cash.list',$viewData);
    }
}
