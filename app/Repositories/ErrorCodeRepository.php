<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class ErrorCodeRepository 
{
    public function info($param){
    	$where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }

        if($param['name']){
            $where['name']=$param['name'];
        }
        $result=DB::table('error_code')->select(
            'ErrorCodeId',
            'id',
            'name',
            'created_at'
        )->where($where)->orderBy('ErrorCodeId','desc')->paginate(20);
        return $result;
    }

    public function findRecordData(){
        $where['table_name'] = 'error_code';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $result=DB::table('error_code')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$result,true); 
    }

    public function testArray($param){
        $result=DB::table('error_code')->select(
            'id',
            'name'
        )->pluck('name','id')->toArray();
        return $result;
    }
}