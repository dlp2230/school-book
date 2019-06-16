<?php

namespace App\Http\Controllers\Auth;
use App\Exceptions\CouponCodeUnavailableException;
use App\Http\Controllers\Controller;

use App\Models\MerchantAccountModel;
use App\Libraries\Helper;
use Auth;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('guest');
    }

    /*
     * 修改密码
     * @date 2018-11-06 15:53
     * @param[old_pwd] $old_pwd 旧密码~
     * @param[pwd]   $pwd 新密码
     * @param[new_pwd]  $new_pwd 确认密码
     * **/
    public function updPassword(MerchantAccountModel $merchantAccountModel,Helper $helper)
    {
        if(request()->isMethod('post')){
            $oldPwd = trim(request('old_pwd'));
            $pwd = trim(request('pwd'));
            $newPwd = trim(request('new_pwd'));
            if(strlen($pwd) < 6 || strlen($newPwd) < 6){
                return $this->failJson("密码过于简单，请设置6位以上密码~");
            }
            if($pwd !== $newPwd){
                return $this->failJson("两次密码输入不一致~");
            }

            $account = $merchantAccountModel->getAccountById($this->accountId);
            if(!$account){
                return $this->failJson("用户不存在~");
            }
            if(Helper::jbMd5($oldPwd) != $account['pass_word']){
                return $this->failJson("老密码输入有误~");
            }
            //更新用户信息~
            $merchantAccount = MerchantAccountModel::find($this->accountId);
            $merchantAccount->pass_word = Helper::jbMd5($pwd);
            $merchantAccount->save();

            return $this->successJson("修改成功~");
        }
        return $this->failJson("请求方式有误~");
    }

}
