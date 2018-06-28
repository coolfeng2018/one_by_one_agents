<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 17:44
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class GameUpdataRepository extends BaseRepository 
{
     public function list(){
         $result=DB::table('version_release')->select(
             'version_release.release_id',
             'version_release.version_id',
             'version_release.allow_channel',
             'version_release.deny_channel',
             'version_release.allow_version',
             'version_release.deny_version',
             'version_release.release_time',
             'version_release.create_time',
             'version.version',
             'version.update_type',
             'version.game_id',
             'version.game_code',
             'version.description',
             'version.is_force',
             'version.is_public',
             'version.allowance',
             'version.update_url',
             'version.version_manifest',
             'version.manifest_url',
             'version.search_path'
         )->leftjoin(
             'version','version.version_id','=','version_release.version_id'
         )->orderBy('version_id','desc')->paginate(10);
         return $result;
     }

     public function find($release_id){
         $result=DB::table('version_release')->select(
             'version_release.release_id',
             'version_release.version_id',
             'version_release.allow_channel',
             'version_release.deny_channel',
             'version_release.allow_version',
             'version_release.deny_version',
             'version_release.release_time',
             'version_release.create_time',
             'version.version',
             'version.update_type',
             'version.game_id',
             'version.game_code',
             'version.description',
             'version.is_force',
             'version.allowance',
             'version.update_url',
             'version.size',
             'version.version_manifest',
             'version.manifest_url',
             'version.search_path'
         )->leftjoin(
             'version','version.version_id','=','version_release.version_id'
         )->where('release_id','=',$release_id)->first();
         return $result;
     }

     public function gamelist(){
         $result=DB::select('select DISTINCT od_games.kind_id,od_games.game_name,game_rules.rule_name FROM game_rules left join od_games on od_games.rule_id = game_rules.rule_id');
         return $result;
     }

     public function add($data){
         DB::beginTransaction();
         $versiondata['version']=$data['version'];
         $versiondata['update_type']=$data['update_type'];
         $versiondata['game_id']=$data['game_id']??0;
         $versiondata['game_code']=$data['game_code'];
         $versiondata['description']=$data['description'];
         $versiondata['is_force']=$data['is_force'];
         $versiondata['allowance']=$data['allowance'];
         $versiondata['update_url']=$data['update_url'];
         $versiondata['version_manifest']=$data['version_manifest'];
         $versiondata['manifest_url']=$data['manifest_url'];
         $versiondata['search_path']=$data['search_path'];
         $versiondata['size']=$data['size']??0;
         try{
             $version_id=DB::table('version')->insertGetId($versiondata);
             $releasedata['version_id']=$version_id;
             $releasedata['allow_channel']=$data['allow_channel'];
             $releasedata['deny_channel']=$data['deny_channel']??'';
             $releasedata['allow_version']=$data['allow_version'];
             $releasedata['deny_version']=$data['deny_version']??'';
             $releasedata['release_time']=$data['release_time'];
             DB::table('version_release')->insert($releasedata);

             DB::commit();
             return $version_id;
         }catch (\Exception $e){
             Log::info($e);
             DB::rollback();
             return false;
         }
     }

     public function edit($data,$release_id,$version_id){
         DB::beginTransaction();
         $versiondata['version']=$data['version'];
         $versiondata['update_type']=$data['update_type'];
         $versiondata['game_id']=$data['game_id']??0;
         $versiondata['game_code']=$data['game_code'];
         $versiondata['description']=$data['description'];
         $versiondata['is_force']=$data['is_force'];
         $versiondata['allowance']=$data['allowance'];
         $versiondata['update_url']=$data['update_url'];
         $versiondata['version_manifest']=$data['version_manifest'];
         $versiondata['manifest_url']=$data['manifest_url'];
         $versiondata['search_path']=$data['search_path'];
         $versiondata['size']=$data['size']??0;
         try{
             $version_id=DB::table('version')->where('version_id','=',$version_id)->update($versiondata);
             $releasedata['allow_channel']=$data['allow_channel'];
             $releasedata['deny_channel']=$data['deny_channel']??'';
             $releasedata['allow_version']=$data['allow_version'];
             $releasedata['deny_version']=$data['deny_version']??'';
             $releasedata['release_time']=$data['release_time'];
             DB::table('version_release')->where('release_id','=',$release_id)->update($releasedata);
             DB::commit();
             return true;
         }catch (\Exception $e){
             Log::info($e);
             DB::rollback();
             return false;
         }
     }

     public function del($release_id,$version_id){
         DB::beginTransaction();
         try{
             DB::table('version')->where('version_id','=',$version_id)->delete();
             DB::table('version_release')->where('release_id','=',$release_id)->delete();
             DB::commit();
             return true;
         }catch (\Exception $exception){
             Log::info($exception);
             DB::rollback();
             return false;
         }
     }

     public function findupdate_type($update_type){
        $result=DB::table('game_updates')->select(
            'update_id',
            'version'
        )->where('update_type','=',$update_type)->get();
        return $result;
     }
     
     public function findupdate_public($version_id,$public_type){
         $versiondata = [];
         $versiondata['is_public'] = $public_type;
         $result = DB::table('version')->where('version_id','=',$version_id)->update($versiondata);
         return $result;
     }
}