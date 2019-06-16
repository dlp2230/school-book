<?php

namespace App\Http\Controllers\Account;

use App\Models\MerchantAccountModel;
use App\Http\Service\Account\MerchantAccountService;
use App\Http\Controllers\Controller;
class TUserController extends Controller
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
        $builder = MerchantAccountModel::query();

        $name = $this->request->get('name');
        $mobile = (int) $this->request->get('mobile');
        if(!empty($name)){
            $builder->where(function ($query) use ($name) {
                $query->where('name', $name);
            });
        }
        if(!empty($mobile)){
            $builder->where(function ($query) use ($mobile) {
                $query->where('mobile', $mobile);
            });
        }

        $builder->orderBy('id','DESC');

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
    public function edit($id,MerchantAccountService $merchantAccountService)
    {
        $result = $merchantAccountService->getAccountById($id);

        $viewData = [];
        $viewData['result'] = $result;
        $viewData['id'] = $id;
        return view('account.merchant_account.edit',$viewData);
    }

    //doEdit
    public function doEdit(MerchantAccountService $merchantAccountService)
    {
        $result = $merchantAccountService->doEdit();
        return $result;
    }

    public function delete(MerchantAccountService $merchantAccountService)
    {
        $result = $merchantAccountService->doDelete();
        return $result;
    }


}
