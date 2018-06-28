<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class ChannelVersionRepository
{
    public function info($param){
        $where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }
        if($param['curr_version']){
            $where['curr_version']=$param['curr_version'];
        }

        if($param['task_share_title']){
            $where['task_share_title']=$param['task_share_title'];
        }
        $reulst=DB::table('channel_version')->select(
            'ChannelVersionId',
            'id',
            'curr_version',
            'title',
            'des',
            'targetUrl',
            'img',
            'shareImg',
            'sharetype',
            'sharetab',
            'task_share_title',
            'task_share_content',
            'task_share_url',
            'announcement_url',
            'kefu_url',
            'agent_url',
            'payment_channels',
            'payment_ways',
            'created_at'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'channel_version';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('channel_version')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}