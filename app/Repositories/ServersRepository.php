<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 20:49
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServersRepository
{
     public function list(){
        $result=DB::table('games_server')->select(
                'games_server.games_server_id',
                'games_server.server_id',
                'games_server.server_name',
                'games_server.server_ip',
                'games_server.server_port',
                'games_server.online_quantity',
                'games_server.create_time'
            )->orderBy('games_server.server_id','desc')->paginate(10);
        return $result;
     }

     public function find($games_server_id){
        $result=DB::table('games_server')->select(
                'games_server.games_server_id',
                'games_server.server_id',
                'games_server.server_name',
                'games_server.server_ip',
                'games_server.server_port',
                'games_server.online_quantity',
                'games_server.kind_id',
                'games_server.deskset_level',
                'games_server.create_time'
            )->where('games_server.games_server_id','=',$games_server_id)->first();
        return $result;
     }

     public function add($data){
         DB::beginTransaction();
         try{
             DB::table('games_server')->insert($data);
//             $game_idarr=$data['game_id'];
//             foreach ($game_idarr as $v){
//                 DB::table('games_servers')->insert([
//                     'server_id'=>$data['server_id'],
//                     'game_id'=>$v
//                 ]);
//             }
             DB::commit();
             return true;
         }catch (\Exception $e){
             Log::info($e);
             DB::rollback();
             return false;
         }
     }

     public function edit($data,$games_server_id){
         DB::beginTransaction();
         try{
             //DB::table('games_servers')->where('server_id','=',games_server_id)->delete();

             DB::table('games_server')->where('games_server_id','=',$games_server_id)->update($data);
//
//             $game_idarr=$data['game_id'];
//             foreach ($game_idarr as $v){
//                 DB::table('games_servers')->insert([
//                     'server_id'=>$server_id,
//                     'game_id'=>$v
//                 ]);
//             }

             DB::commit();
             return true;
         }catch (\Exception $e){
             DB::rollback();
             return false;
         }

     }

     public function del($server_id){
         DB::beginTransaction();
         try{
             //DB::table('games_servers')->where('games_server_id','=',$server_id)->delete();
             DB::table('games_server')->where('games_server_id','=',$server_id)->delete();
             DB::commit();
             return true;
         }catch (\Exception $e){
             DB::rollback();
             return false;
         }
     }

     public function listall(){
         $result=DB::table('games_server')->select(
                 'server_id',
                 'server_name'
             )->orderBy('server_id','desc')->get();
         return $result;
     }

     public function findbydeskset($kind_id){
         $result=DB::table('desksets')->select(
             'deskset_id',
             'deskset_name',
             'level'
         )->where('kind_id','=',$kind_id)->get();
         return $result;
     }
}