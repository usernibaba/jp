<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopModel extends  Model{
    protected  $table='p_goods';
    protected  $primaryKey='goods_id';
    public $timestamps=false;

}




?>