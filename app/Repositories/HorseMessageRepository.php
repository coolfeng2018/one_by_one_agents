<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class HorseMessageRepository
{
    public function info($param){
        $where=[];
        if($param['ID']){
            $where['ID']=$param['ID'];
        }
        if($param['content']){
            $where['content']=$param['content'];
        }
        $reulst=DB::table('horse_message')->select(
            'HorseMessageId',
            'ID',
            'content',
            'min_time',
            'max_time'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'horse_message';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('horse_message')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}