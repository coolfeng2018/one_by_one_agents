<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 10:07
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class GameShowRepository
{
    public function list(){
        $result=DB::table('game_show')->select(
            'gameshow_id',
            'allowChannel',
            'denyChannel',
            'allowVersion',
            'denyVersion',
            'gameshow_name',
            'type'
        )->orderBy('gameshow_id','desc')->paginate(10);
        return $result;
    }

    public function find($gameshow_id){
        $result=Db::table('game_show')->select(
            'gameshow_id',
            'allowChannel',
            'denyChannel',
            'allowVersion',
            'denyVersion',
            'gameshow_name'
        )->where('gameshow_id','=',$gameshow_id)->first();
        return $result;
    }

    public function add($data){
        $result=DB::table('game_show')->insertGetId($data);
        return $result;
    }

    public function edit($data,$gameshow_id){
        $result=DB::table('game_show')->where('gameshow_id','=',$gameshow_id)->update($data);
        return $result;
    }

    public function del($gameshow_id){
        $result=DB::table('game_show')->where('gameshow_id','=',$gameshow_id)->delete();
        return $result;
    }






    public function list_showpieces($gameshow_id){
        $data=DB::table('showpieces')->select(
            'showpieces.ShowpiecesId',
            'showpieces.ShowpiecesName',
            'showpieces.ShowpiecesType',
            'showpieces.GameId',
            'showpieces.ParentId',
            'showpieces.SortNum',
            'showpieces.Available',
            'showpieces.CreateTime',
            'showpieces.gameshow_id',
            'games.game_name'
        )->leftjoin(
            'games','games.game_id','=','showpieces.GameId'
        )->where('gameshow_id','=',$gameshow_id)->orderBy('showpieces.ShowpiecesId','ASC')->get();
        return $data;
    }

    public function addshowpieces($data){
        $result=DB::table('showpieces')->insertGetId($data);
        return $result;
    }


    public function delshowpieces($ShowpiecesId){
        $result=DB::table('showpieces')->where('ShowpiecesId','=',$ShowpiecesId)->delete();
        return $result;
    }

    public function pidshowpieces(){
        $result=DB::table('showpieces')->select(
            'showpieces.ShowpiecesId',
            'showpieces.ShowpiecesName'
        )->where('ShowpiecesType','=','2')->where('ParentId','=','0')->get();
        return $result;
    }

//    public function postedgame(){
//[
//    {
//        "allowChannel": "",
//        "denyChannel": "",
//        "allowVersion": "",
//        "denyVersion": "",
//        "showpieces": [
//            {
//                "Name": "私人房",
//                "Type": 2,
//                "GameList": [
//                    {
//                        "Name": "牛牛",
//                        "GameId": 1001,
//                        "SortNum": 0,
//                        "GameType": 1,
//                        "ServerIp": "192.168.1.84",
//                        "ServerPort": 8886,
//                        "Status": 1
//                    }
//                ]
//            },
//            {
//                "Name": "快速开始",
//                "Type": 1,
//                "GameId": 1002,
//                "GameType": 0,
//                "ServerIp": "192.168.1.126",
//                "ServerPort": 8886
//            },
//            {
//                "Name": "百人牛牛",
//                "Type": 0,
//                "GameId": 1002,
//                "GameType": 0,
//                "SortNum": 0,
//                "ServerIp": "192.168.1.126",
//                "ServerPort": 8886,
//                "Status": 1
//            },
//            {
//                "Name": "牛牛",
//                "Type": 0,
//                "GameId": 1001,
//                "GameType": 0,
//                "SortNum": 2,
//                "ServerIp": "192.168.1.126",
//                "ServerPort": 8887,
//                "Status": 1
//            }
//        ]
//    }
//]

//    }



    public function getFirstLevel(){
        $result=DB::table('showpieces')->select(
            'showpieces.ShowpiecesName',
            'showpieces.ShowpiecesType',
            'showpieces.GameId',
            'showpieces.ParentId',
            'showpieces.SortNum',
            'showpieces.Available'
        )->join(
            'game_show','game_show.gameshow_id','=','showpieces.gameshow_id'
        )->where('game_show.type','=',1)->where('showpieces.ParentId','=',0)->orderBy('showpieces.ShowpiecesId','asc')->get();
        $tmp=json_decode(json_encode($result), true);
        return $tmp;
    }
    public function getFatherLevel(){
        $result=DB::table('showpieces')->select(
            'showpieces.ShowpiecesId'
        )->join(
            'game_show','game_show.gameshow_id','=','showpieces.gameshow_id'
        )->where('game_show.type','=',1)->where('showpieces.ParentId','=',0)->orderBy('showpieces.ShowpiecesId','asc')->get();
        $tmp=json_decode(json_encode($result), true);
        return $tmp;
    }

    public function getSecondLevel(){
        $result=DB::table('showpieces')->select(
            'showpieces.ShowpiecesName',
            'showpieces.ShowpiecesType',
            'showpieces.GameId',
            'showpieces.ParentId',
            'showpieces.SortNum',
            'showpieces.Available'
        )->join(
            'game_show','game_show.gameshow_id','=','showpieces.gameshow_id'
        )->where('game_show.type','=',1)->where('showpieces.ParentId','<>',0)->orderBy('showpieces.ShowpiecesId','asc')->get();
        $tmp=json_decode(json_encode($result), true);
        return $tmp;
    }

}