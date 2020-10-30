<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoginModel extends  Model{
    protected  $table='p_user';
    protected  $primaryKey='user_id';
    public $timestamps=false;

}




?>