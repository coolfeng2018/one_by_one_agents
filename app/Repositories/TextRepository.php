<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class TextRepository 
{
    public function info($param){
    	$where=[];
        if($param['content']){
            $where['content']=$param['content'];
        }

        if($param['type']){
            $where['type']=$param['type'];
        }
        $reulst=DB::table('text')->select(
            'TextId',
            'id',
            'content',
            'type',
            'created_at'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'text';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('text')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst); 
    }

}