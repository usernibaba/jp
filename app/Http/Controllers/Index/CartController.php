<?php
namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\CartModel;
use App\Model\LoginModel;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * 购物车页面
     */
    public function index()
    {
        $uid = session()->get('user_id');
        if(empty($uid))
        {
            return redirect('/login/login');
//            echo  1111;
        }

        //取购物车商品信息
        $list = CartModel::where(['user_id'=>$uid])->get();

        $goods = [];
        foreach($list as $k=>$v)
        {
            $goods[] = GoodsModel::find($v['goods_id'])->toArray();
        }

        $data = [
            'goods' => $goods
        ];

        return view('index/index/cart',$data);



    }


    /**
     * 加入购物车
     */
    public function add(Request $request)
    {

        $uid = session()->get('user_id');
        if(empty($uid))
        {
            return redirect('/login/login');
//            echo  1111;
        }

        $goods_id = $request->get('id');
//        var_dump($goods_id);die;
        $goods_num = $request->get('num',1);

        // 检查是否下架 库存是否充足  ...

        //购物车保存商品信息
        $cart_info = [
            'goods_id'  => $goods_id,
            'user_id'       => $uid,
            'goods_num' => $goods_num,
            'add_time'  => time(),
        ];

        $res =CartModel::insertGetId($cart_info);

        if($res>0)
        {
            return redirect('/index/sss');
        }else{
            $data = [
                'errno' => 500001,
                'msg'   => '加入购物车失败'
            ];

            echo json_encode($data);
        }





    }





}