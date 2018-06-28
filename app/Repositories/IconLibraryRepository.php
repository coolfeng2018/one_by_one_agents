<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class IconLibraryRepository
{
    public function info($param){
        $where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }
        if($param['name']){
            $where['name']=$param['name'];
        }
        $reulst=DB::table('icon_library')->select(
            'IconLibraryId',
            'id',
            'name'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'icon_library';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('icon_library')->select(
            $select
        )->get()->toArray(); 
        return convert_array($select,$reulst,true); 
    }

}