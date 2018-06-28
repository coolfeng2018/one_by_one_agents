<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:31
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignRepository
{
     public function list(){
         $result=DB::table('campaign')->select(
             'CampaignId',
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
         )->orderBy('CampaignId','desc')->paginate(10);
         return $result;
     }

     public function find($campaignId){
         $result=DB::table('campaign')->select(
             'CampaignId',
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
         )->where('CampaignId','=',$campaignId)->first();
         return $result;
     }

     public function add($data){
         try{
             DB::table('campaign')->insert($data);
             return true;
         }catch (\Exception $e){
             Log::info($e);
             return false;
         }
     }

     public function edit($data,$campaignId){
         try{
             DB::table('campaign')->where('CampaignId','=',$campaignId)->update($data);
             return true;
         }catch (\Exception $e){
             return false;
         }
     }

     public function del($campaignId){
         try{
             DB::table('campaign')->where('CampaignId','=',$campaignId)->delete();
             return true;
         }catch (\Exception $e){
             return false;
         }
     }
}