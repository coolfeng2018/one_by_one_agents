<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class FriendRoomDataRepository
{
    public function info(){
        $reulst=DB::table('friendroomdata')->select(
            'FriendRoomdataId',
            'table_type',
            'name',
            'is_club',
            'cost_type',
            'cost',
            'aa_cost',
            'max_count',
            'play_num',
            'min_dizhu',
            'max_dizhu',
            'min_white_dizhu',
            'max_white_dizhu',
            'min_ration',
            'max_ration',
            'comparable_bet_round',
            'max_bet_round',
            'max_look_round',
            'max_need_money',
            'white_list',
            'ration'
        )->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'friendroomdata';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('friendroomdata')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}