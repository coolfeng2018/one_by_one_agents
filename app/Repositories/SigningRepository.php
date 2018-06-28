<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class SigningRepository
{
    public function info($param){
        $where=[];
        if($param['month']){
            $where['month']=$param['month'];
        }
        $reulst=DB::table('signing')->select(
            'SigningId',
            'month',
            'awards_list'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'signing';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('signing')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false,true); 
    }

}