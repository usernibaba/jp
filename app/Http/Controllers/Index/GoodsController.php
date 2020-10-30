<?php
namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\FavGoodsModel;
use Illuminate\Http\Request;
use App\Model\ContentModel;
class GoodsController extends Controller
{


    public function detail(Request $request){
            $goods_id=$request->get('id');
            $goods=GoodsModel::find($goods_id);
            $data=[
                'g'=>$goods,
            ];
            $res=ContentModel::get()->toArray();

            return view('index/index/goods',$data,['res'=>$res]);
    }
    //点击收藏
    public function fav(Request $request){
        $this->user_id=session()->get('user_id');
      if(empty($this->user_id)){
          $res=[
            'errno'=>400003,
              'msg'=>'请先登录',
          ];

          return $res;
      }
        $id=$request->get('id');
        echo  $id;
        $data=[
            'goods_id'=>$id,
            'user_id'     =>$this->user_id,
            'add_time'=>time()
        ];
        FavGoodsModel::insertGetId($data);
        $res=[
            'errno' => 0,
            'msg' =>'ok'
        ];
        return $res;
    }
    //评论
    public function contentAdd(Request $request)
    {
        $this->user_name=session()->get('user_name');
        $content=$request->get('content');
        $data=[
            'user_name'=>$this->user_name,
            'content'=>$content,
            'c_time'=>time()
        ];
        $res=ContentModel::insert($data);
        if($res){
           echo '添加成功 ';
        }else{
            echo '添加失败';
        }

    }


}

