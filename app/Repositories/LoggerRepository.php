<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class LoggerRepository
{
    public function info($param){

        $where=[];
        if($param['uname']){
            $where['b.username']=$param['uname'];
        }

        if($param['stime'] && $param['etime']){
            $result=DB::table('logger as a')
                ->join('admin as b','b.id','=','a.uid')
                ->select('a.id','b.username','a.mark','a.addtime')
                ->whereBetween('a.addtime',[$param['stime'],$param['etime']])
                ->where($where)
                ->orderBy('a.id','desc')
                ->paginate(10);
        }else{
            $result=DB::table('logger as a')
                ->join('admin as b','b.id','=','a.uid')
                ->select('a.id','b.username','a.mark','a.addtime')
                ->where($where)
                ->orderBy('a.id','desc')
                ->paginate(10);
        }
        return $result;
    }


}