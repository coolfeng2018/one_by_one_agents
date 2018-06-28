<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 9:56
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class MessageRepository
{
     public function list(){
         $result=DB::table('message')->select(
             'message_id',
             'concent',
             'statrtime',
             'endtime',
             'interval',
             'createtime'
         )->orderBy('createtime','desc')->paginate(10);
         return $result;
     }

     public function find($message_id){
         $result=DB::table('message')->select(
             'message_id',
             'concent',
             'statrtime',
             'endtime',
             'interval',
             'createtime'
         )->where('message_id','=',$message_id)->first();
         return $result;
     }

     public function edit($data,$message_id){
         if(DB::table('message')->where('message_id','=',$message_id)->update($data)){
             return true;
         }else{
             return false;
         }
     }

     public function add($data){
         if(DB::table('message')->insert($data)){
             return true;
         }else{
             return false;
         }
     }

     public function del($message_id){
         if(DB::table('message')->where('message_id','=',$message_id)->delete()){
             return true;
         }else{
             return  false;
         }
     }
}