<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessController extends BaseController
{
    public function index(){
        $results = DB::table('access')->where('pid','=',0)->get();
        $res=$this->objectToArray($results);
        $newArr=[];
        foreach ($res as $k => $v){
            $lowLevel = DB::table('access')->where('pid','=',$v['id'])->get();
            $lowLevels=$this->objectToArray($lowLevel);
            array_push($newArr,$res[$k]);
            if($lowLevels){
                foreach ($lowLevels as $kk => $vv){
                    array_push($newArr,$lowLevels[$kk]);
                }
            }
        }
        return view('Access.index',['res'=>$newArr]);
    }

    public function add(Request $request){
        $pid=$request->pid;
        return view('Access.add',['pid'=>$pid]);
    }
    public function save(Request $request){

        $id=$request->id;
        $data['pid']=$request->pid;
        $data['name']=$request->name;
        $data['url']=$request->url;
        $data['type']=$request->type;
        $data['status']=$request->status;

        if($id){
            $preArr=DB::table('access')->select('pid','name','url','type','status')->where('id', $id)->first();
            $preArr=json_decode(json_encode($preArr), true);
            parent::saveTxtLog($preArr,$data,'权限节点',$id);

            $result=DB::table('access')->where('id', $id)->update($data);
            parent::saveLog('更新权限节点id--'.$id);
        }else{
            $result=DB::table('access')->insertGetId($data);
            parent::saveLog('添加权限节点id--'.$result);
        }
        if($result){
            exit(json_encode(['status'=>1,'msg'=>'ok','url'=>'/access/index']));
        }else{
            exit(json_encode(['status'=>0,'msg'=>'error']));
        }
    }
    public function update(Request $request){
        $id=$request->id;
        $results=DB::table('access')->where('id', $id)->get();
        return view('Access.update',['res'=>$results]);
    }
    public function delete(Request $request){
        $id=$request->id;
        $res=DB::table('access')->where('id', '=', $id)->delete();
        parent::saveLog('删除权限节点id--'.$id);

        return redirect('/access/index');
    }


    protected function objectToArray($obj){
        $arr=json_decode(json_encode($obj), true);
        return $arr;
    }

}
