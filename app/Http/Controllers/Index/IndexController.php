<?php

namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\GithubuserModel;
use GuzzleHttp\Client;
use Validator;
class IndexController extends Controller{

    public function index(){
        return view ('index/index/index');
    }
    public function cart(){
        return view ('index/index/cart');
    }
    public function  search(){
        return view ('index/index/search');
    }
    public function  tianqi(){

//        $url='https://devapi.qweather.com/v7/weather/now?location=101010100&key=25d561a4abf74d8c93dc14ca845af024&gzip=n';

//        $json_str= file_get_contents('https://devapi.qweather.com/v7/weather/now?location=101010100&key=25d561a4abf74d8c93dc14ca845af024&gzip=n');
////        print_r($json_str);die;
//        $data=json_decode($json_str,true);
////        print_r($data);die;
//        echo'<pre>';print_r($data);'</pre>';

//        $data='theCityName=北京';
//        $curlobj=curl_init();
//        curl_setopt($curlobj,CURLOPT_URL,'http://www.webxml.com.cn/WebServices/WeatherWebService.asmx/getWeatherbyCityName');
////        curl_setopt($curlobj,CURLOPT_HEADER,0);
//        curl_setopt($curlobj,CURLOPT_RETURNTRANSFER,1);//用不用在浏览器输出
//        curl_setopt($curlobj,CURLOPT_POST,1);
//        curl_setopt($curlobj,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);//刚开始没加这句，结果就报错(未将对象引用设置到对象的实例) ,然后加上这句,就好了，参数：CURLOPT_USERAGENT : 在HTTP请求中包含一个”user-agent”头的字符串。
//        curl_setopt($curlobj,CURLOPT_POSTFIELDS,$data);
//        curl_setopt($curlobj,CURLOPT_HTTPHEADER,array("application/x-www-form-urlencoded;charset=utf-8","Content-length: ".strlen($data)));
//        $rtn=curl_exec($curlobj);
//        if(!curl_errno($curlobj)){
//            echo'<pre>';echo($rtn);'</pre>';
//        }else{
//            echo 'Curl error:'.curl_error($curlobj);
//        }
//        echo curl_close($curlobj);



//        $url='https://devapi.qweather.com/v7/weather/now?location=101010700&key=25d561a4abf74d8c93dc14ca845af024&gzip=n';
//        $ch=curl_init();
//        //设置URL和相应的选项
//        curl_setopt($ch,CURLOPT_URL,$url);
//        curl_setopt($ch,CURLOPT_HEADER,0);
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//        //关闭HTTPS
//        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
//        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
//        //抓取URLB并把它传递给浏览器
//        $json_str=curl_exec($ch);
//        //捕获错误
//        $err_no=curl_errno($ch);
//        if($err_no){
//            $err_msg=curl_error($ch);
//            echo "错误信息".$err_msg;
//            die;
//        }
//        //关闭URL资源 并且释放资源
//        curl_close($ch);
//        $data=json_decode($json_str,true);
//         echo '<pre>';print_r($data);echo '</pre>';


    }
    public function githubLogin(Request $request)
    {

        // 接收code
        $code = $_GET['code'];

        //换取access_token
        $token = $this->getAccessToken($code);
        //获取用户信息
        $git_user = $this->getGithubUserInfo($token);

        //判断用户是否已存在，不存在则入库新用户
        $u = GithubuserModel::where(['guid'=>$git_user['id']])->first();
        if($u)          //存在
        {
            // TODO 登录逻辑
            $this->webLogin($u->uid);

        }else{          //不存在

            //在 用户主表中创建新用户  获取 uid
            $new_user = [
                'user_name' => Str::random(10)              //生成随机用户名，用户有一次修改机会
            ];
            $uid = UserModel::insertGetId($new_user);

                // 在 github 用户表中记录新用户
                $info = [
                'uid'                   => $uid,       //作为本站新用户
                'guid'                  => $git_user['id'],         //github用户id
                'avatar'                =>  $git_user['avatar_url'],
                'github_url'            =>  $git_user['html_url'],
                'github_username'       =>  $git_user['name'],
                'github_email'          =>  $git_user['email'],
                'add_time'              =>  time()
            ];

            $guid = GithubuserModel::insertGetId($info);        //插入新纪录

            // TODO 登录逻辑
            $this->webLogin($uid);
        }

        //将 token 返回给客户端
        return redirect('/user/center');       //登录成功 返回首页

    }

    /**
     * 根据code 换取 token
     */
    protected function getAccessToken($code)
    {
        $url = 'https://github.com/login/oauth/access_token';

        //post 接口  Guzzle or  curl
        $client = new Client();
        $response = $client->request('POST',$url,[
            'verify'    => false,
            'form_params'   => [
                'client_id'         => env('OAUTH_GITHUB_ID'),
                'client_secret'     => env('OAUTH_GITHUB_SEC'),
                'code'              => $code
            ]
        ]);
        parse_str($response->getBody(),$str); // 返回字符串 access_token=59a8a45407f1c01126f98b5db256f078e54f6d18&scope=&token_type=bearer
        return $str['access_token'];
    }

    /**
     * 获取github个人信息
     * @param $token
     */
    protected function getGithubUserInfo($token)
    {
        $url = 'https://api.github.com/user';
        //GET 请求接口
        $client = new Client();
        $response = $client->request('GET',$url,[
            'verify'    => false,
            'headers'   => [
                'Authorization' => "token $token"
            ]
        ]);
        return json_decode($response->getBody(),true);
    }

    /**
     * WEB登录逻辑
     */
    protected function webLogin($uid)
    {

        //将登录信息保存至session uid 与 token写入 seesion
        session(['uid'=>$uid]);

    }


 }



