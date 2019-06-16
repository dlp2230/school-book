<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Libraries\Helper;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(User $user,Helper $helper)
    {
        if(request()->isMethod('post')){
           $auth = request('auth');
           $username = request('username');
           $password = request('password');
           $result =  $user->getAccountByInfo($username);
           if(empty($result)){
               return $this->failJson('用户名或密码错误~');
           }
           if($result['pass_word'] != $helper::jbMd5($password)){
               return $this->failJson('密码错误~');
           }

            // 登录信息
            Auth::loginUsingId($result['account_id']);

            $auth = array('userId' => $result['account_id'], 'secretKey' => Helper::jbMd5($result['account_id'])); // 登录认证

            session()->put('adminUser' , $result);
            // 全局变量$_SERVER
           // $server = $this->request->server();

            // 设置COOKIE
            if (isset($auth) && $auth) {// 自动登录 cookie保存7天
                setcookie('auth', json_encode($auth), time() + 7 * 86400, '/', $_SERVER['HTTP_HOST']);
            }
            return $this->successJson("登录成功~");

        }
        return view('auth.login');
    }

    //退出
    public function logout()
    {
        // 全局变量$_SERVER
        $server = $_SERVER;
        setcookie('auth', '', time() - 1, '/', $server['HTTP_HOST']);
        session()->remove(Auth::getName());
        session()->forget('auth');

        return redirect(url('login'));
    }

    public function username()
    {
        return 'account_sn';
    }
}
