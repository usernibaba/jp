<?php

namespace App\Http\Controllers\Index;

use App\Model\CartModel;
use App\Model\GoodsModel;

use App\Http\Controllers\Controller;
use App\Model\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    /**
     * 生成订单
     */
    public function create()
    {
        //TODO 获取购物车中的商品（根据当前用户id）
//        echo 1111;die;
        $uid = session()->get('user_id');
        $cart_goods = CartModel::where(['user_id'=>$uid])->get();

            if(empty($cart_goods))      //空购物车
        {

        }
//        print_r($cart_goods->toArray());die;
        $cart_goods_arr = $cart_goods->toArray();
//        print_r($cart_goods_arr);die;
        //TODO 生成订单号 计算订单总价  记录订单信息（订单表orders）

        //echo '<pre>';print_r($cart_goods->toArray());echo '</pre>';
        $total = 0;
        foreach ($cart_goods_arr as $k=>$v)
        {
            //查询商品表的实时价格
            $g = GoodsModel::find($v['goods_id']);
            //echo '<pre>';print_r($g->toArray());echo '</pre>';die;
            $total += $g->shop_price;
            //dd($total);
            $v['goods_price'] = $g->shop_price;
            $v['goods_name'] = $g->goods_name;
            $order_goods[] = $v;
//            dd($order_goods);
//    print_r($g);die;
        }

        $order_data = [
            'order_sn'  => strtolower(Str::random(20)),     //订单唯一编号
            'user_id'   => $uid,
            'order_amount'  => $total,
            'add_time'  => time()

        ];
//        dd($order_data);
        $oid = OrderModel::insertGetId($order_data);        //订单入库

        // 记录订单商品  （订单商品表orders_goods）
        foreach($order_goods as $k=>$v)
        {
            $goods = [
                'order_id'  => $oid,
                'goods_id'  => $v['goods_id'],
                'goods_name'    => $v['goods_name'],
                'sho_price'   => $v['shop_price']
            ];

            OrderGoodsModel::insertGetId($goods);
        }


        //TODO 清空购物车
        CartModel::where(['user_id'=>$uid])->delete();

        //TODO 跳转至 支付页面
        return redirect('/pay/ali?oid='.$oid);

    }

}