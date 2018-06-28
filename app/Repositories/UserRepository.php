<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function info($param){
        $where=[];
        if($param['uid']){
            $where['b.UserId']=$param['uid'];
        }

        if($param['name']){
            $where['a.NickName']=$param['name'];
        }
        $result=DB::table('users as a')
            ->leftjoin('role_info as b','a.UserId','=','b.UserId')
            ->select('a.UserId','a.LoginType','a.MobilePlatform','a.Mobile','a.Gender','a.NickName','a.AvatarUrl','a.Status','a.RegisterTime','a.LastLoginTime','a.MachineCode','a.ServiceType')
            ->where('b.IsRobot','=',0)
            ->where($where)
            ->orderBy('a.UserId','desc')
            ->paginate(10);

        return $result;
    }
    
    public function getUser($param) {
        $where=[];
        if($param['uid']){
            $where['uid']=$param['uid'];
        }
        
        $result = DB::table('dc_log_user.users as a')
                ->select('*')
                ->where($where)
                ->first();

        return $result;
    }
}