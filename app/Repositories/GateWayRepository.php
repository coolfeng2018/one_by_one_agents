<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 11:56
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GateWayRepository
{
    public  function list(){
        $result=DB::table('gateway_info')->select(
            'gateway_info.GateWay_ID',
            'gateway_info.GameWayName',
            'gateway_info.Game_List',
            'gateway_info.Role_Num',
            'gateway_info.SortID',
            'gateway_info.IsLock',
            'gateway_info.IP',
            'gateway_info.prot'
        )->orderBy('GateWay_ID','desc')->paginate(10);
        return $result;
    }

    public  function find($gateway_id){
        $result=DB::table('gateway_info')->select(
            'gateway_info.GateWay_ID',
            'gateway_info.GameWayName',
            'gateway_info.Game_List',
            'gateway_info.Role_Num',
            'gateway_info.SortID',
            'gateway_info.IsLock',
            'gateway_info.IP',
            'gateway_info.prot'
        )->where('gateway_info.GateWay_ID','=',$gateway_id)->first();
        $result->server_id=DB::table('gateways')->select(
            'server_id'
        )->where('gateway_id','=',$result->GateWay_ID)->get();
        return $result;
    }

    public  function add($iparr,$data){
        DB::beginTransaction();
        try{
            $gateway_id_arr='';

            foreach ($iparr as $v){
                $obj=explode(':',$v);
                $gateway_id=DB::table('gateway_info')->insertGetId([
                    'GameWayName'=>$data['GameWayName'],
                    'Game_List'=>'1,2,3',
                    'Role_Num'=>$data['Role_Num'],
                    'SortID'=>$data['SortID'],
                    'IsLock'=>$data['IsLock'],
                    'IP'=>(string)$obj[0],
                    'prot'=>(int)$obj[1]
                ]);
                DB::table('gateways')->insert([
                        'gateway_id'=>$gateway_id,
                        'server_id'=>$data['server_id']
                    ]);
                $gateway_id_arr=$gateway_id_arr.$gateway_id.',';
            }
            DB::commit();
            return $gateway_id_arr;
        }catch (\Exception $e){
            Log::info($e);
            DB::rollback();
            return false;
        }
    }

    public function edit($data,$gateway_id){
        DB::beginTransaction();
        try{
            DB::table('gateways')->where('gateway_id','=',$gateway_id)->delete();

            DB::table('gateway_info')->where('GateWay_ID','=',$gateway_id)->update([
                'GameWayName'=>$data['GameWayName'],
                'Game_List'=>'1,2,3',
                'Role_Num'=>$data['Role_Num'],
                'SortID'=>$data['SortID'],
                'IsLock'=>$data['IsLock'],
                'IP'=>$data['IP'],
                'prot'=>$data['prot']
            ]);
            DB::table('gateways')->insert([
                'gateway_id'=>$gateway_id,
                'server_id'=>$data['server_id']
            ]);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function del($gateway_id){
        DB::beginTransaction();
        try{
            DB::table('gateways')->where('gateway_id','=',$gateway_id)->delete();
            DB::table('gateway_info')->where('gateway_id','=',$gateway_id)->delete();
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function batchupdatestatus($id,$status){
        if(DB::update('update gateway_info set IsLock='.$status.' WHERE GateWay_ID in ('.$id.')')){
            return true;
        }else{
            return false;
        }
    }

    public function batchupdateSortID($id,$SortID){
        if(DB::update('update gateway_info set SortID='.$SortID.' WHERE GateWay_ID in ('.$id.')')){
            return true;
        }else{
            return false;
        }
    }
    
     public function gatewayList(){
        $sql="select b.server_id,a.IP,a.prot,a.SortID from gateway_info as a 
                join gateways as b on a.GateWay_ID=b.gateway_id where a.IsLock=1";
        $tmp=DB::select($sql);
        return $tmp;
    }
}