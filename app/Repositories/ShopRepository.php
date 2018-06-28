<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class ShopRepository
{
    public function info($param){
        $where=[];
        if($param['id']){
            $where['id']=$param['id'];
        }
        if($param['name']){
            $where['name']=$param['name'];
        }
        $reulst=DB::table('shop')->select(
            'ShopId',
            'id',
            'name',
            'price',
            'goods',
            'index',
            'discount',
            'icon_name',
            'ios_goods_id'
        )->where($where)->paginate(20);
        return $reulst;
    }

    public function findRecordData(){
        $where['table_name'] = 'shop';
        $result=Db::table('user_behavior_record')->select(
            'server_record',
            'customer_record'
        )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('shop')->select(
            $select
        )->get()->toArray();
        foreach ($reulst as $k => $v) {    
            $reulst[$k]->icon_name = $v->icon_name ? config('suit.ImgRemoteUrl').$v->icon_name : '';
        } 
        return convert_array($select,$reulst,false); 
    }

}