<?php

namespace App\Http\Controllers\Orders;

use App\Http\Service\Orders\NonUnityService;
use App\Http\Controllers\Controller;
class UnityController extends Controller
{
    protected $paymentTypes = [
        1=>'全款支付',
        2=>'定金支付',
    ];
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
    public function index(NonUnityService $nonUnityService)
    {
        $param = parent::requestInput();
        $result = $nonUnityService->getOrderNonUnityList();

        $viewData = [];
        $viewData['where'] = $param;
        $viewData['list'] = $result['list'];
        $viewData['merchant'] = $result['merchant'];
        $viewData['payment_types'] = $this->paymentTypes;
        return view('order.non_unity.list',$viewData);
    }


}
