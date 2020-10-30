<?php
namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ContentModel;
use App\Model\StartModel;
class StartController extends Controller
{

 public  function  start(){
     return view('index/index/start');
 }

public function  prize(){
     $user_id=session()->get('user_id');
     $user_id=212312;
        if(empty($user_id)){
            $res= [
                'errno'=>400003,
                'msg'=>'请先登录'
            ];
            return  $res;
        }
        $time1=strtotime(date("Y-m-d "));
        $ress=StartModel::where(['user_id'=>$user_id])->where('add_time','>=',$time1)->first();
        if($ress){   //已经有记录
            $res=[
                'errno' =>300008,
                'msg'   =>'今日抽奖次数上限'
            ];
            return  $res;
        }
        $rand=mt_rand(1,1000);


    $level= 0;
        if($rand>=1 && $rand<=10){
            $level=1;
        }elseif($rand>=11&& $rand<=30){
            $level=2;
        }elseif($rand>=31 && $rand<= 60) {
            $level=3;
        }

        //抽奖信息
        $prize_data=[
            'user_id'=>$user_id,
            'level'  =>$level,
            'add_time'=>time()
        ];
       $pid= StartModel::insertGetId($prize_data);
       if ($pid>0){
           $data=[
               'errno'=>0,
               'msg'=>'ok',
               'data'=>[
                   'rand' =>$rand,
                   'level'=>$level     //中奖等级
               ]
           ];
       }else{
           //异常
           $data =[
               'error'=>500000,
               'msg'  =>'网关繁忙，请联系管理员',

           ];

       }

        return $data;
}
 }

