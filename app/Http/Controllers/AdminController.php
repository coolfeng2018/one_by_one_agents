<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends BaseController
{
    public function index(){
        if(session('admin')){
            return redirect('/index/index');
        }
        return view('Agent.index');
    }

    /**
     * [check 校验登录]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function check(Request $request){
        $where['Telephone']=$request->user;
        $password=md5($request->pwds);
        //用户信息
        $results = DB::table('agents')->where($where)->first();
        $info=$this->objectToArray($results);
        if($info){ 
            if($info['Status']!=1){
                return response()->json(['status' =>0, 'msg' => '账号已被禁用,请联系客服!']);
            }
            if($info['Password']!=$password)
            {
                return response()->json(['status' =>0, 'msg' => '密码错误!']);
            }
            $info['range'] = 100;//最小提取金额
            //保存admin session信息
            session(['admin'=>$info]);
            return response()->json(['status' =>1, 'msg' => 'ok','url'=>'/index/index']);
        }else{
            return response()->json(['status' =>0, 'msg' => '账号错误']);
        }
    }

    /**
     * [out 退出]
     * @return [type] [description]
     */
    public function out(){
        Session::forget('admin');
        Session::forget('out');
        return redirect('/');
    }

    /**
     * [objectToArray description]
     * @param  [type] $obj [description]
     * @return [type]      [description]
     */
    protected function objectToArray($obj){
        $arr=json_decode(json_encode($obj), true);
        return $arr;
    }

    /**
     * [changePwd 修改密码]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function changePwd(Request $request){ 
        $info = session('admin');
        $pwdOld = $request->pwdOld;
        $newPwd = $request->newPwd;
        $confirmPwd = $request->confirmPwd;
        if($newPwd!=$confirmPwd){
            return response()->json(['status' => 0, 'msg' => '密码不一致!']);
        }
        $result = DB::table('agents')->where('AgentId','=',$info['AgentId'])->first();
        if(md5($pwdOld)!=$result->Password){
            return response()->json(['status' => 0, 'msg' => '密码错误!']);
        }
        DB::beginTransaction();
        try{
            DB::connection()->enableQueryLog();
            $reuslt = DB::table('agents')->where('AgentId','=',$info['AgentId'])->update(['Password' => md5($newPwd)]);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            Log::info($request->all());
            Log::info($exception->getTraceAsString());
            return response()->json(['status' => 0, 'msg' => '修改密码失败,请重试!']);
        }
        return response()->json(['status' =>1 , 'msg' => '修改成功!']);
    }

}
