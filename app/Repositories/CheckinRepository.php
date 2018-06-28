<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9
 * Time: 16:58
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class CheckinRepository
{
    public function list(){
        $reulst=DB::table('checkin_conf')->select(
            'checkin_id',
            'checkin_name',
            'items',
            'status'
        )->orderBy('checkin_id','desc')->paginate(10);
        return $reulst;
    }

    public function find($sign_id){
        $reulst=DB::table('checkin_conf')->select(
            'checkin_id',
            'checkin_name',
            'items',
            'status'
        )->where('checkin_id','=',$sign_id)->first();
        return $reulst;
    }

    public function add($data){
        DB::beginTransaction();
        try{
            DB::table('checkin_conf')->insert($data);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function edit($data,$sign_id){
        DB::beginTransaction();
        try{
            DB::table('checkin_conf')->where('checkin_id','=',$sign_id)->update($data);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function del($sign_id){
        DB::beginTransaction();
        try{
            DB::table('checkin_conf')->where('checkin_id','=',$sign_id)->delete();
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function getProps(){
        $data=DB::table('props')->select(
            'PropsId',
            'PropsName'
        )->get();
        $result=[];
        foreach ($data as $v){
            $result[$v->PropsId]=$v->PropsName;
        }
        return $result;
    }


}