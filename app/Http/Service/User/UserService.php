<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/30
 * Time: 16:14
 */
namespace App\Http\Service\User;

use App\Models\UserModel;
use App\Models\UserAddressModel;
use App\Http\Service\BaseService;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUserById($uid)
    {
        $userModel = new UserModel();
        $user = $userModel->getUserById($uid);

        return $user;
    }

    //获取用户信息
    public function getUserInfo($mobile)
    {
        $userModel = new UserModel();
        $user = $userModel->getUserMobileByInfo($mobile);

        return $user;
    }

    /*
     * 用户的收货地址
     * @param[uid] $uid 用户UID
     * **/
    public function getUserAddressList($uid)
    {
        $userAddressModel = new UserAddressModel();
        $addressList = $userAddressModel->getUserAddressList($uid);

        return $addressList;
    }

}