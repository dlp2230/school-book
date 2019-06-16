<?php
/**
 * Created by PhpStorm.
 * User: deng
 * Date: 2018/9/27
 * Time: 21:56
 */
namespace App\Http\Controllers\SettlCenter;

use App\Http\Service\SettlCenter\SettlService;
use App\Http\Controllers\Controller;

class SettlController extends Controller
{
    //init
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * 统一收银结算
     * @serach city && activity
     * **/
    public function unified(SettlService $settlService)
    {
        $param = parent::requestInput();

        $viewData = $settlService->getUnifiedList();

        $viewData['where'] = $param;

        return view('settl_center.settl.unified',$viewData);
    }

    // 滞保金明细
    public function hGoldDetail()
    {
        echo 1111;
        dd(" 滞保金明细~");
    }

    //滞保金退款~
    public function hGoldRefund()
    {
        dd('滞保金退款~');
    }

    //非统一收银结算
    public function nonUnity()
    {

    }

}