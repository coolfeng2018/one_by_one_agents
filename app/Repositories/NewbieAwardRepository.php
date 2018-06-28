<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class NewbieAwardRepository
{
    public function info($param){
        $where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }
        if($param['item_id']){
            $where['item_id']=$param['item_id'];
        }
        $reulst=DB::table('newbie_award as a')->leftjoin('item as b','a.item_id','=','b.id')
        ->select(
            'a.NewbieAwardId',
            'a.id',
            'a.item_id',
            'a.count',
            'b.name'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'newbie_award';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('newbie_award')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,true); 
    }

}