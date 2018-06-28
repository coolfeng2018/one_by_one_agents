<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class ShareRepository
{
    public function info($param){
        $where=[];
        if($param['channel']){
            $where['channel']=$param['channel'];
        }
        if($param['title']){
            $where['title']=$param['title'];
        }
        $reulst=DB::table('share')->select(
            'ShareId',
            'channel',
            'title',
            'des',
            'targetUrl',
            'img',
            'shareImg',
            'sharetype',
            'sharetab',
            'task_share_title',
            'task_share_content',
            'task_share_url'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'share';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('share')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}