<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 9:57
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class HeadshotRepository
{
    public function list(){
        $result=DB::table('user_headshot')->select(
            'headshot_id',
            'user_id',
            'headshot_url',
            'status',
            'createtime'
        )->orderBy('createtime','desc')->paginate(10);
        return $result;
    }

    public function audit($headshot_id,$status){
        if(DB::table('user_headshot')->where('headshot_id','=',$headshot_id)->update(['status'=>$status])){
            return true;
        }else{
            return false;
        }
    }

}