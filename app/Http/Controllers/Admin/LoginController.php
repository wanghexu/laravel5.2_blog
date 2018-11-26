<?php

namespace App\Http\Controllers\Admin;
use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

require_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    public function login()
    {
        if($input = Input::all()){
           // dd($input);
            //加斜杠，路径去底层找，strtoupper是转换成大写
            $code = new \Code;
            $_code = $code->get();
            if(strtoupper($input['code'])!=$_code){
                return back()->with('msg','验证码错误！');
            }
            $user = User::first();
            //Crypt::encrypt是加密
            if($user->user_name != $input['user_name'] || Crypt::decrypt($user->user_pass)!= $input['user_pass']){
                return back()->with('msg','用户名或者密码错误！');
            }
            session(['user'=>$user]);
            return redirect('admin/index');
            //查看旧密码
           // echo  Crypt::decrypt($user->user_pass);
        }else {
            return view('admin.login');
        }
    }

    public function quit()
    {
        session(['user'=>null]);
        return redirect('admin/login');
    }

    public function code()
    {
        $code = new \Code;
        $code->make();
    }

}
