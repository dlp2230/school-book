<?php
namespace App\Http\Service\Account;

use Auth;
use App\Models\MerchantAccountModel;
use App\Libraries\Helper;
use App\Http\Service\BaseService;

class MerchantAccountService extends BaseService
{
    protected $accountModel;
    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new MerchantAccountModel();
    }

    //account
    public function get()
    {
        $result = MerchantAccountModel::with(['merchant'])->get()->toArray();

        return $result;
    }

    //doAdd
    public function add()
    {
        if(request()->isMethod('post')){
            $param = request()->all();
            $name = trim($param['name']);
            $company = trim($param['company']);
            $mobile = trim($param['mobile']);
            $eMail = trim($param['e_mail']);
            if(empty($name)){
                return $this->failJson("姓名不能为空~");
            }
            if(empty($company)){
                return $this->failJson("公司名称不能为空~");
            }
            if(empty($mobile)){
                return $this->failJson("手机号码不能为空~");
            }
            if(empty($eMail)){
                return $this->failJson("邮箱不能为空~");
            }

            $accountModel = new MerchantAccountModel();
            $accountModel->name = $name;
            $accountModel->company = $company;
            $accountModel->mobile = $mobile;
            $accountModel->e_mail = $eMail;
            $accountModel->del_flag = 1;
            $accountModel->created_at = date('Y-m-d H:i:s');
            $accountModel->updated_at = date('Y-m-d H:i:s');


            $res = $accountModel->save();

            if($res){
                return $this->successJson("添加成功~");
            }
            return $this->failJson("添加失败~");
        }

        return $this->failJson("提交方式有误~");

    }

    //edit
    public function getAccountById($id)
    {
        $res = $this->accountModel->getAccountById($id);

        return $res;
    }

    //edit
    public function doEdit()
    {
        if(request()->isMethod('post')){
            $param = request()->all();
            $id = trim($param['id']);
            $name = trim($param['name']);
            $company = trim($param['company']);
            $mobile = trim($param['mobile']);
            $eMail = trim($param['e_mail']);
            if(empty($name)){
                return $this->failJson("姓名不能为空~");
            }
            if(empty($company)){
                return $this->failJson("公司名称不能为空~");
            }
            if(empty($mobile)){
                return $this->failJson("手机号码不能为空~");
            }
            if(empty($eMail)){
                return $this->failJson("邮箱不能为空~");
            }

            $accountModel = new MerchantAccountModel();

            $res = $accountModel->UpdateAccount($param);

            if($res === true){
                return $this->successJson("修改成功~");
            }
            return $this->failJson("修改失败~");
        }

        return $this->failJson("提交方式有误~");
    }

    public function doDelete()
    {
        if(request()->isMethod('post')){
            $param = request()->all();
            $param['del_flag'] = -1;
            unset($param['_token']);
            $accountModel = new MerchantAccountModel();

            $res = $accountModel->UpdateAccount($param);

            if($res === true){
                return $this->successJson("修改成功~");
            }
            return $this->failJson("修改失败~");
        }

        return $this->failJson("提交方式有误~");
    }

}