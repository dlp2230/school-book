<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected  $table = 'hx_merchant_account';//这歌就是你新表
    protected $primaryKey = 'account_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'real_name', 'account_sn', 'pass_word',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function getAccountByInfo($account)
    {
       $result =  $this->where('account_sn',$account)->first();

       return !empty($result) ? $result->toArray() :[];
    }
}
