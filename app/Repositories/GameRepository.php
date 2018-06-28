<?php

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class GameRepository
{
    public function list(){
        $result=DB::table('games')->select(
            'games.game_id',
            'games.game_type',
            'games.game_name',
            'games.game_icon_url',
            'games.created_at',
            'games.updated_at'
        )->paginate(20);
        return $result;
    }

    public function find($game_id){
        $result=DB::table('games')->select(
            'games.game_id',
            'games.game_type',
            'games.game_name',
            'games.game_icon_url',
            'games.created_at',
            'games.updated_at'
        )->where('game_id','=',$game_id)->first();
        return $result;
    }


    public function add($data){
        $result=DB::table('games')->insertGetId($data);
        return $result;
    }

    public function edit($data,$game_id){
        $result=DB::table('games')->where('game_id','=',$game_id)->update($data);
        return $result;
    }

    public function del($game_id){
         $result=DB::table('games')->where('game_id','=',$game_id)->delete();
         return $result;
    }

    public function gametype($game_type){
        if($game_type){
            $result = DB::table('games')->select('game_type','game_name')->where('game_type', $gameType)->get();
        }else{
            $result = DB::table('games')->select('game_type','game_name')->get();
        }
        return $result;
    }

    public function listall(){
        $result=DB::table('games')->select(
            'games.game_id',
            'games.kind_id',
            'games.game_name',
            'games.scene_id',
            'game_scenes.scene_name'
        )->leftjoin(
            'game_scenes','game_scenes.scene_id','=','games.scene_id'
        )->orderBy('games.sort','desc')->get();
        return $result;
    }

    public function list_scenes(){
        $result=DB::table('game_scenes')->select(
            'scene_id',
            'scene_name'
        )->get();
        return $result;
    }


}
