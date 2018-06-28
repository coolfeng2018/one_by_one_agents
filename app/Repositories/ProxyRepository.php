<?php

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProxyRepository
{
     public function info($pram){
         //where
         $whereID=[];
         if($pram['uid']){
             $whereID['proxy.UserId']=$pram['uid'];
         }
         if($pram['mobile']){
             $whereID['proxy.Mobile']=$pram['mobile'];
         }

         //分页
         $config=[];
         $config['page']=$pram['page'];

         if($pram['page']>0){
             $page=$pram['page']-1;
         }else{
             $page=$pram['page'];
         }
         //区间
         $config['pre']=10;
         $offset=$page*$config['pre'];
         $time=date('Y-m-d H:i:s');

         if( $pram['stime'] && $pram['etime']){
             $results = DB::table('proxy')
                         ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                         ->leftjoin('user_assets', 'proxy.UserId', '=', 'user_assets.UserId')
                         ->select('proxy.AgentId', 'proxy.UserId', 'proxy.Mobile', 'proxy.CreateTime','proxy.Status', 'role_info.diamond', 'role_info.Score',DB::raw('SUM(user_assets.Number) as RoomCardNum'))
                         ->where('proxy.Pid', '=', 0)
                         ->where('user_assets.ExpiredAt', '>', $time)
                         ->where('user_assets.Number', '>', 0)
                         ->where($whereID)
                         ->where('proxy.CreateTime','>=',$pram['stime'])
                         ->where('proxy.CreateTime','<=',$pram['etime'])
                         ->groupBy('proxy.UserId')
                         ->offset($offset)->limit($config['pre'])
                         ->get();

             $total = DB::table('proxy')
                         ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                         ->select('proxy.AgentId')
                         ->where('proxy.Pid', '=', 0)
                         ->where($whereID)
                         ->where('proxy.CreateTime','>=',$pram['stime'])
                         ->where('proxy.CreateTime','<=',$pram['etime'])
                         ->count();
         }else{

             $results = DB::table('proxy')
                 ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                 ->leftjoin('user_assets', 'proxy.UserId', '=', 'user_assets.UserId')
                 ->select('proxy.AgentId', 'proxy.UserId', 'proxy.Mobile', 'proxy.CreateTime','proxy.Status', 'role_info.diamond', 'role_info.Score',DB::raw('SUM(user_assets.Number) as RoomCardNum'))
                 ->where('proxy.Pid', '=', 0)
                 ->where('user_assets.ExpiredAt', '>', $time)
                 ->where('user_assets.Number', '>', 0)
                 ->where($whereID)
                 ->groupBy('proxy.UserId')
                 ->offset($offset)->limit($config['pre'])
                 ->get();
             $total = DB::table('proxy')
                 ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                 ->select('proxy.AgentId')
                 ->where('proxy.Pid', '=', 0)
                 ->where($whereID)
                 ->count();
         }
         //分页信息
         $config['total']=$total;
         $config['pages']=ceil($total/$config['pre']);

         $res['config']=$config;
         $res['results']=$results;

         return $res;
     }

     public function chargeInfo($pram){

         //分页
         $config=[];
         $config['page']=$pram['page'];

         if($pram['page']>0){
             $page=$pram['page']-1;
         }else{
             $page=$pram['page'];
         }
         //区间
         $config['pre']=10;
         $offset=$page*$config['pre'];

         $results = DB::table('orders')
             ->select('order_id', 'order_code', 'pay_way', 'platform', 'sale_id', 'goods_name','amount','number','create_time')
             ->where('uid','=',$pram['uid'])
             ->where('create_time','>=',$pram['stime'])
             ->where('create_time','<=',$pram['etime'])
             ->offset($offset)->limit($config['pre'])
             ->get();

         $total = DB::table('orders')
             ->select('order_id')
             ->where('uid','=',$pram['uid'])
             ->where('create_time','>=',$pram['stime'])
             ->where('create_time','<=',$pram['etime'])
             ->count();

         //分页信息
         $config['total']=$total;
         $config['pages']=ceil($total/$config['pre']);

         $res['config']=$config;
         $res['results']=$results;

         return $res;
     }

    public function tradeInfo($pram){

        //where
        $whereID=[];
        if($pram['uid']){
            $whereID['a.UserId']=$pram['uid'];
        }
        if($pram['mobile']){
            $whereID['c.Mobile']=$pram['mobile'];
        }

        //分页
        $config=[];
        $config['page']=$pram['page'];

        if($pram['page']>0){
            $page=$pram['page']-1;
        }else{
            $page=$pram['page'];
        }
        //区间
        $config['pre']=10;
        $offset=$page*$config['pre'];

        if( $pram['stime'] && $pram['etime']){
            $results = DB::table('proxy_record as a')
                ->join('proxy as b', 'a.AgentId', '=', 'b.AgentId')
                ->join('proxy as c', 'a.GameId', '=', 'c.UserId')
                ->select('a.AgentId','a.Id', 'c.UserId', 'c.Mobile',  'a.Type', 'a.Number','a.CreateTime')
                ->where('a.AgentId', '=', $pram['aid'])
                ->where($whereID)
                ->where('a.CreateTime','>=',$pram['stime'])
                ->where('a.CreateTime','<=',$pram['etime'])
                ->offset($offset)->limit($config['pre'])
                ->get();

            $total = DB::table('proxy_record as a')
                ->join('proxy as b', 'a.AgentId', '=', 'b.AgentId')
                ->join('proxy as c', 'a.GameId', '=', 'c.UserId')
                ->select('a.Id')
                ->where('a.AgentId', '=', $pram['aid'])
                ->where($whereID)
                ->where('a.CreateTime','>=',$pram['stime'])
                ->where('a.CreateTime','<=',$pram['etime'])
                ->count();
        }else{
            $results = DB::table('proxy_record as a')
                ->join('proxy as b', 'a.AgentId', '=', 'b.AgentId')
                ->join('proxy as c', 'a.GameId', '=', 'c.UserId')
                ->select('a.AgentId','a.Id', 'c.UserId', 'c.Mobile',  'a.Type', 'a.Number','a.CreateTime')
                ->where('a.AgentId', '=', $pram['aid'])
                ->where($whereID)
                ->offset($offset)->limit($config['pre'])
                ->get();

            $total = DB::table('proxy_record as a')
                ->join('proxy as b', 'a.AgentId', '=', 'b.AgentId')
                ->join('proxy as c', 'a.GameId', '=', 'c.UserId')
                ->select('a.AgentId','a.Id', 'c.UserId', 'c.Mobile',  'a.Type', 'a.Number','a.CreateTime')
                ->where('a.AgentId', '=', $pram['aid'])
                ->where($whereID)
                ->count();
        }
        //分页信息
        $config['total']=$total;
        $config['pages']=ceil($total/$config['pre']);

        $res['config']=$config;
        $res['results']=$results;

        return $res;
    }

    public function levelInfo($pram){

        //where
        $whereID=[];
        if($pram['uid']){
            $whereID['proxy.UserId']=$pram['uid'];
        }
        if($pram['mobile']){
            $whereID['proxy.Mobile']=$pram['mobile'];
        }

        //分页
        $config=[];
        $config['page']=$pram['page'];

        if($pram['page']>0){
            $page=$pram['page']-1;
        }else{
            $page=$pram['page'];
        }
        //区间
        $config['pre']=10;
        $offset=$page*$config['pre'];
        $time=date('Y-m-d H:i:s');

        if( $pram['stime'] && $pram['etime']){
            $results = DB::table('proxy')
                ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                ->leftjoin('user_assets', 'proxy.UserId', '=', 'user_assets.UserId')
                ->select('proxy.AgentId', 'proxy.UserId', 'proxy.Mobile', 'proxy.CreateTime','proxy.Status', 'role_info.diamond', 'role_info.Score',DB::raw('SUM(user_assets.Number) as RoomCardNum'))
                ->where('proxy.Pid', '=', $pram['aid'])
                ->where('user_assets.ExpiredAt', '>', $time)
                ->where('user_assets.Number', '>', 0)
                ->where($whereID)
                ->where('proxy.CreateTime','>=',$pram['stime'])
                ->where('proxy.CreateTime','<=',$pram['etime'])
                ->groupBy('proxy.UserId')
                ->offset($offset)->limit($config['pre'])
                ->get();

            $total = DB::table('proxy')
                ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                ->select('proxy.AgentId')
                ->where('proxy.Pid', '=', $pram['aid'])
                ->where($whereID)
                ->where('proxy.CreateTime','>=',$pram['stime'])
                ->where('proxy.CreateTime','<=',$pram['etime'])
                ->count();
        }else{
            $results = DB::table('proxy')
                ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                ->leftjoin('user_assets', 'proxy.UserId', '=', 'user_assets.UserId')
                ->select('proxy.AgentId', 'proxy.UserId', 'proxy.Mobile', 'proxy.CreateTime','proxy.Status', 'role_info.diamond', 'role_info.Score',DB::raw('SUM(user_assets.Number) as RoomCardNum'))
                ->where('proxy.Pid', '=',  $pram['aid'])
                ->where('user_assets.ExpiredAt', '>', $time)
                ->where('user_assets.Number', '>', 0)
                ->where($whereID)
                ->groupBy('proxy.UserId')
                ->offset($offset)->limit($config['pre'])
                ->get();

            $total = DB::table('proxy')
                ->join('role_info', 'role_info.UserId', '=', 'proxy.UserId')
                ->select('proxy.AgentId')
                ->where('proxy.Pid', '=',  $pram['aid'])
                ->where($whereID)
                ->count();
        }
        //分页信息
        $config['total']=$total;
        $config['pages']=ceil($total/$config['pre']);

        $res['config']=$config;
        $res['results']=$results;

        return $res;
    }
}