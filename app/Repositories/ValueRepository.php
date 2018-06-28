<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class ValueRepository 
{
    public function info($param){
    	$where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }

        if($param['value']){
            $where['value']=$param['value'];
        }
        $reulst=DB::table('value')->select(
            'ValueId',
            'id',
            'value',
            'created_at'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'value';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('value')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,true); 
    }

}