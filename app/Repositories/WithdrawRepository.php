<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class WithdrawRepository
{
    public function list($search){
        if($search['uid']==0&&$search['type']>=0){
            $data=DB::table('distribution_userwithdraw')->select(
                'distribution_userwithdraw.withdraw_id',
                'distribution_userwithdraw.duser_id',
                'distribution_userwithdraw.account',
                'distribution_userwithdraw.realname',
                'distribution_userwithdraw.amount as withdraw_amount',
                'distribution_userwithdraw.open_can',
                'distribution_userwithdraw.withdraw_type',
                'distribution_userwithdraw.status',
                'distribution_userwithdraw.addtime',
                'distribution_userwithdraw.updatatime',
                'distribution_user.gameuserid',
                'distribution_user.balance',
                'distribution_user.balance1',
                'distribution_user.balance2',
                'distribution_user.balance3',
                'distribution_user.amount'
            )->leftjoin(
                'distribution_user','distribution_user.distributionuser_id','=','distribution_userwithdraw.duser_id'
            )->where(
                'distribution_userwithdraw.withdraw_type','=',$search['type']
            )->whereBetween(
                'distribution_userwithdraw.addtime',[$search['starttime'],$search['endtime']]
            )->orderBy('addtime','desc')->paginate(10);
            return $data;
        }else if($search['uid']==0&&$search['type']<0){
            $data=DB::table('distribution_userwithdraw')->select(
                'distribution_userwithdraw.withdraw_id',
                'distribution_userwithdraw.duser_id',
                'distribution_userwithdraw.account',
                'distribution_userwithdraw.realname',
                'distribution_userwithdraw.amount as withdraw_amount',
                'distribution_userwithdraw.open_can',
                'distribution_userwithdraw.withdraw_type',
                'distribution_userwithdraw.status',
                'distribution_userwithdraw.addtime',
                'distribution_userwithdraw.updatatime',
                'distribution_user.gameuserid',
                'distribution_user.balance',
                'distribution_user.balance1',
                'distribution_user.balance2',
                'distribution_user.balance3',
                'distribution_user.amount'
            )->leftjoin(
                'distribution_user','distribution_user.distributionuser_id','=','distribution_userwithdraw.duser_id'
            )->whereBetween(
                'distribution_userwithdraw.addtime',[$search['starttime'],$search['endtime']]
            )->orderBy('addtime','desc')->paginate(10);
            return $data;
        }else if($search['uid']>0 && $search['type']>=0){
            $data=DB::table('distribution_userwithdraw')->select(
                'distribution_userwithdraw.withdraw_id',
                'distribution_userwithdraw.duser_id',
                'distribution_userwithdraw.account',
                'distribution_userwithdraw.realname',
                'distribution_userwithdraw.amount as withdraw_amount',
                'distribution_userwithdraw.open_can',
                'distribution_userwithdraw.withdraw_type',
                'distribution_userwithdraw.status',
                'distribution_userwithdraw.addtime',
                'distribution_userwithdraw.updatatime',
                'distribution_user.gameuserid',
                'distribution_user.balance',
                'distribution_user.balance1',
                'distribution_user.balance2',
                'distribution_user.balance3',
                'distribution_user.amount'
            )->leftjoin(
                'distribution_user','distribution_user.distributionuser_id','=','distribution_userwithdraw.duser_id'
            )->where(
                'distribution_userwithdraw.withdraw_type','=',$search['type']
            )->whereBetween(
                'distribution_userwithdraw.addtime',[$search['starttime'],$search['endtime']]
            )->where(
                'distribution_user.gameuserid','=',$search['uid']
            )->orderBy('addtime','desc')->paginate(10);
            return $data;
        }else if($search['uid']>0 && $search['type']<0){
            $data=DB::table('distribution_userwithdraw')->select(
                'distribution_userwithdraw.withdraw_id',
                'distribution_userwithdraw.duser_id',
                'distribution_userwithdraw.account',
                'distribution_userwithdraw.realname',
                'distribution_userwithdraw.amount as withdraw_amount',
                'distribution_userwithdraw.open_can',
                'distribution_userwithdraw.withdraw_type',
                'distribution_userwithdraw.status',
                'distribution_userwithdraw.addtime',
                'distribution_userwithdraw.updatatime',
                'distribution_user.gameuserid',
                'distribution_user.balance',
                'distribution_user.balance1',
                'distribution_user.balance2',
                'distribution_user.balance3',
                'distribution_user.amount'
            )->leftjoin(
                'distribution_user','distribution_user.distributionuser_id','=','distribution_userwithdraw.duser_id'
            )->whereBetween(
                'distribution_userwithdraw.addtime',[$search['starttime'],$search['endtime']]
            )->where(
                'distribution_user.gameuserid','=',$search['uid']
            )->orderBy('addtime','desc')->paginate(10);
            return $data;
        }

    }

    public function edit($withdraw_id,$status){
        DB::beginTransaction();
        try{
            if(DB::table('distribution_userwithdraw')->where('withdraw_id','=',$withdraw_id)->update(['status'=>$status])){
                $dist_userwithdraw=DB::table('distribution_userwithdraw')->select(
                    'duser_id',
                    'amount'
                )->where('withdraw_id','=',$withdraw_id)->first();
                if($status==1){
                    DB::table('distribution_user')->where('distributionuser_id','=',$dist_userwithdraw->duser_id)->decrement('freezing_amount',$dist_userwithdraw->amount);
                }else if($status==3){
                    DB::table('distribution_user')->where('distributionuser_id','=',$dist_userwithdraw->duser_id)->decrement('freezing_amount',$dist_userwithdraw->amount);
                    DB::table('distribution_user')->where('distributionuser_id','=',$dist_userwithdraw->duser_id)->increment('balance',$dist_userwithdraw->amount);
                }
                DB::commit();
                return true;
            }else{
                DB::rollback();
                return false;
            }
        }catch (\Exception $exception){
            DB::rollback();
            return false;
        }

    }

    public function findByIdmap($duid,$page,$szie){
        $startpage=$page*$szie;

        $result=DB::select('select dorder.order_id,dorder.distributionuser_id,dorder.gameuser_id,dorder.props,dorder.amount,dorder.num,dorder.registertime as addtime,duser.level,duser.registertime from distribution_orders as dorder 
left join distribution_user as duser on duser.distributionuser_id=dorder.distributionuser_id  where FIND_IN_SET(?,duser.idmap) Order By order_id LIMIT ? OFFSET ?',[$duid,$szie,$startpage]);
        return $result;
    }

    public function findByIdmapCount($duid){
        $count=DB::select('select count(*) as counts from distribution_orders as dorder left join distribution_user as duser on duser.distributionuser_id=dorder.distributionuser_id  where FIND_IN_SET(?,duser.idmap)',[$duid]);
        return $count[0]->counts;
    }

    public function myduserinfo($duid){
        $myduser=DB::table('distribution_user')->select(
            'level'
        )->where('distributionuser_id','=',$duid)->first();
        return $myduser;
    }
}