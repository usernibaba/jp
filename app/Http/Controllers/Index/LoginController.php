<?php
namespace App\Http\Controllers\Index;
use App\Model\LoginModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function  register(){
        return view('index/login/register');
    }
    public function  registerdo(Request $request){
        $validator=Validator::make($request->all(), [
            'user_name'=>'required|unique:p_user',
            'password'=>'required',
            'email'=>'required|unique:p_user',
            'passwords'=>'required|same:password',
            'user_tel'=>'required|unique:p_user'
    ],[
        'user_name.required'=>'用户名必填',
         'user_name.unique'=>'用户名已存在',
         'email.required'=>'邮箱必填',
         'email.unique'=>'邮箱已存在',
         'password.required'=>'密码必填',
         'passwords.required'=>'确认密码密码必填',
         'passwords.same'=>'两次密码不一致',
         'user_tel.required'=>'手机号必填',
         'user_tel.unique'=>'手机号已存在',
        ]);
        //表单验证


        if ($validator->fails()) {
            return redirect('login/register')
                ->withErrors($validator)
                ->withInput();
        }
        $data=$request->except('_token');
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $res =LoginModel::insert($data);
        //发送激活邮件
        $active_code=Str::random(64);
        //保存激活码与用户的对应关系  使用有序集合
        $redis_active_key='ss:user:active';
        Redis::zAdd($redis_active_key,$res,$active_code);


        $active_url=env('UPLOADS_URL').'/login/active?code='.$active_code;
        echo  $active_url;die;
        if($res){return redirect('login/login');
        }
    }
    public  function  login(){
        return view('index/login/login');
    }
    public  function  logindo(Request $request){
        $user_name=$request->input('user_name');
        $password =$request->input('password');
        $user_tel=$request->input('user_tel');
        $email=$request->input('email');
//        $post = $request->except('_token');
        $username=LoginModel::where(['user_name'=>$user_name])
            ->orwhere(['user_tel'=>$user_name])
            ->orwhere(['email'=>$email])
            ->first();
//        $username=$username->toArray();
//        dd($username);
        //判断账号是否和数据库一致
        if(!$username){
            return redirect('login/login')->with('msg','没有此用户');
        }
        if(!password_verify($password,$username['password'])){
            return redirect('/login/login')->with('msg','密码错误');
        }
        session(['user_id'=>$username->user_id,'user_name'=>$username->user_name,'user_tel'=>$user_tel]);
        return redirect('index/index/');
    }
    public  function  quit(Request $request){
        $request->session()->flush();
        return redirect('login/login');
    }
    public  function  active(Request $request){
        $active_code=$request->get('code');
        echo '激活码:'.$active_code;echo'<br>';

        $redis_active_key='ss:user:active';
        $uid=Redis::zScore($redis_active_key,$active_code);
        echo "uid:".$uid;echo'<br>';
        //激活用户
        if($uid){
            LoginModel::where(['user_id'=>$uid])->update(['is_validated'=>1]);
            echo"激活成功";
            Redis::zRem($redis_active_key, $active_code);
        }else{
            echo '激活码失效';
        }
    }
    //GITHUB
    public function git(Request $request){
        $code=$request->get('code');
        echo  "code:" .$code;
        $this->getAccessToken($code);
    }
    public  function  getAccessToken(){

    }



}







