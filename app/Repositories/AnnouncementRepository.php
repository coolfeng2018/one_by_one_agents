<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:30
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnnouncementRepository
{
        public function list(){
           $result=DB::table('announcement')->select(
               'AnnouncementId',
               'Title',
               'Tag',
               'Description',
               'ImageUrl',
               'StartTime',
               'EndTime',
               'Action',
               'ActionType',
               'Status',
               'CreateTime'
           )->orderBy('AnnouncementId','desc')->paginate(10);
           return $result;
        }

        public function find($announcementId){
            $result=DB::table('announcement')->select(
                'AnnouncementId',
                'Title',
                'Tag',
                'Description',
                'ImageUrl',
                'StartTime',
                'EndTime',
                'Action',
                'ActionType',
                'Sort',
                'Status',
                'CreateTime'
            )->where('AnnouncementId','=',$announcementId)->first();
            return $result;
        }

        public function add($data){
            Log::info(json_encode($data));
            try{
                DB::table('announcement')->insert($data);
                return true;
            }catch (\Exception $e){
                Log::info($e);
                return false;
            }
        }

        public function edit($data,$announcementId){
            try{
                DB::table('announcement')->where('AnnouncementId','=',$announcementId)->update($data);
                return true;
            }catch (\Exception $e){
                return false;
            }
        }

        public function del($announcementId){
            try{
                DB::table('announcement')->where('AnnouncementId','=',$announcementId)->delete();
                return true;
            }catch (\Exception $e){
                return false;
            }
        }
}