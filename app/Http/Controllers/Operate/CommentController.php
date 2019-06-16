<?php
/**
 * Created by PhpStorm.
 * User: deng
 * Date: 2018/9/25
 * Time: 21:58
 */
namespace App\Http\Controllers\Operate;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    //init
   public function __construct()
   {
       parent::__construct();
   }

    //list
    public function index()
    {
        $view = [];
        return view('operate.comment.list',$view);
    }

}