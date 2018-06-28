<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class ItemRepository
{
    public function info($param){
        $where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }
        if($param['name']){
            $where['name']=$param['name'];
        }
        $reulst=DB::table('item')->select(
            'ItemId',
            'id',
            'name',
            'description',
            'icon'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'item';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('item')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,true); 
    }

}