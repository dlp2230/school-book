<?php

namespace App\Http\Controllers\Account;

use App\Models\MerchantAccountModel;
use App\Http\Service\Account\MerchantAccountService;
use App\Http\Controllers\Controller;
class MerchantAccountController extends Controller
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
     * 帐号列表~
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $param = parent::requestInput();
        // 创建一个查询构造器
        $builder = MerchantAccountModel::query()
            ->with(['merchant'])
            ->where('type',2)
            ->where('merchant_id', $this->merchantId);

        $real_name = $this->request->get('real_name');
        if(!empty($real_name)){
            $builder->where(function ($query) use ($real_name) {
                $query->where('real_name', $real_name);
            });
        }
        $builder->orderBy('type','ASC');

        $accounts = $builder->paginate(20);

        return view('account.merchant_account.list',[
            'list'  => $accounts,
            'where' => $param,
        ]);
    }

    /*
     * add
     * **/
    public function add()
    {
        $viewData = [];

        return view('account.merchant_account.add',$viewData);
    }

    //doAdd
    public function doAdd(MerchantAccountService $merchantAccountService)
    {
        $result = $merchantAccountService->add();
        return $result;
    }

    //edit
    public function edit($accountId,MerchantAccountService $merchantAccountService)
    {
        $result = $merchantAccountService->getAccountById($accountId);

        $viewData = [];
        $viewData['result'] = $result;
        $viewData['accountId'] = $accountId;
        return view('account.merchant_account.edit',$viewData);
    }

    //doEdit
    public function doEdit(MerchantAccountService $merchantAccountService)
    {
        $result = $merchantAccountService->doEdit();
        return $result;
    }


}
