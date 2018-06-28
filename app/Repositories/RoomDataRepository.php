<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class RoomDataRepository
{
    public function info(){
        $reulst=DB::table('roomdata')->select(
            'RoomdataId',
            'ID',
            'name',
            'min',
            'max',
            'cost',
            'dizhu',
            'dingzhu',
            'max_look_round',
            'comparable_bet_round',
            'max_bet_round',
            'img_bg',
            'img_icon',
            'open_robot',
            'robot_type'
        )->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'roomdata';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('roomdata')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}