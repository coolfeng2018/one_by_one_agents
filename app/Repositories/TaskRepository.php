<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class TaskRepository
{
    public function info($param){
        $where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }
        if($param['type']){
            $where['type']=$param['type'];
        }
        $reulst=DB::table('task as a')->leftjoin('games as b','b.game_type','=','a.game_type')->select(
            'a.TaskId',
            'a.id',
            'a.type',
            'a.param',
            'a.game_type',
            'a.cycle',
            'a.process',
            'a.name',
            'a.award_list',
            'a.next_id',
            'a.created_at',
            'a.updated_at',
            'b.game_name'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'task';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('task')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}