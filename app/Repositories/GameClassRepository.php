<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 19:59
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class GameClassRepository
{
    public function list(){
        $result=DB::table('games_class')->select(
            'class_id',
            'class_name',
            'class_sort',
            'create_time'
        )->orderBy('class_sort','desc')->paginate(10);
        return $result;
    }

    public function find($class_id){
        $result=DB::table('games_class')->select(
            'class_id',
            'class_name',
            'class_sort',
            'create_time'
        )->where('class_id','=',$class_id)->first();
        return $result;
    }

    public function add($data){
        $result=DB::table('games_class')->insertGetId($data);
        return $result;
    }

    public function edit($data,$class_id){
        $result=DB::table('games_class')->where('class_id','=',$class_id)->update($data);
        return $result;
    }

    public function del($class_id){
        $result=DB::table('games_class')->where('class_id','=',$class_id)->delete();
        return $result;
    }

}