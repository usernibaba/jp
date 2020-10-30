<?php

namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;
class IndexController extends Controller{
    public  function  alipay(Request $request){
        $oid=$request->get('oid');
        echo "订单ID：".$oid;
        //根据订单号，查询订单信息，验证订单是否有效(未支付，未删除，未过期)

        //组合参数 调整支付接口，支付

        //请求参数
        $param2=[
          'out_trade_no'   => $oid,//商户订单
          'product_code'   =>'FAST_INSTANT_TRADE_PAY',
          'total_amout'    =>99,//订单总额
          'subject'        =>'2004-测试订单-'.Str::random(16),
        ];
        $param1=[
            'app_id'       =>env('ALIPAY_APP_ID'),
            'method'       =>'alipay.trade.page.pay',
            'return_url'   =>'2004-测试订单-'.Str::random(16),
        ];
        print_r($param1);die;
    }
}