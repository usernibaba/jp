<?php
namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function detail(Request $request){
            $goods_id=$request->get('id');
            $goods=GoodsModel::find($goods_id);
            $data=[
                'g'=>$goods,
            ];
            return view('index/index/goods',$data);
    }



}