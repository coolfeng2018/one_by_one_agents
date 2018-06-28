<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class CashRepository
{
    public function info($param){
        $where=[];
        if($param['uid']){
            $where['b.UserId']=$param['uid'];
        }

        if($param['type']>=0){
            $where['a.WithdrawChannel']=$param['type'];
        }

        if($param['stime'] && $param['etime']){
            $result=DB::table('agent_withdraw as a')
                ->join('agents as b','a.AgentId','=','b.AgentId')
                ->select('a.AgentId','a.AgentWithdrawId','b.UserId','b.Telephone','b.Level','a.Amount','a.CurrentBalance','a.Status','a.WithdrawChannel','a.CreateAt')
                ->whereBetween('a.CreateAt',[$param['stime'],$param['etime']])
                ->where($where)
                ->orderBy('a.AgentWithdrawId','desc')
                ->paginate(10);
        }else{
            $result=DB::table('agent_withdraw as a')
                ->join('agents as b','a.AgentId','=','b.AgentId')
                ->select('a.AgentId','a.AgentWithdrawId','b.UserId','b.Telephone','b.Level','a.Amount','a.CurrentBalance','a.Status','a.WithdrawChannel','a.CreateAt')
                ->where($where)
                ->orderBy('a.AgentWithdrawId','desc')
                ->paginate(10);
        }
        return $result;
    }


    public function findRecordData($aid,$page,$size){
        $num=$page*$size;
        $sql="select AgentCommissionId,CommissionType,SourceUserId,Amount,CommissionAmount,CreateAt from agent_commission where AgentId = $aid order by AgentCommissionId desc limit $size offset $num";
        $result=DB::select($sql);
        $res['data']=$result;
        $res['counts']=DB::table('agent_commission')->where('AgentId',$aid)->count();
        return $res;
    }

}