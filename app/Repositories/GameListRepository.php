<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class GameListRepository
{
    public function info($param){
        $where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }
        if($param['game_type']){
            $where['game_type']=$param['game_type'];
        }
        $reulst=DB::table('game_list')->select(
            'GameListId',
            'id',
            'game_type',
            'name',
            'icon',
            'shown_type',
            'status',
            'created_at',
            'updated_at'
        )->where($where)->paginate(20);
 
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'game_list';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('game_list')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}