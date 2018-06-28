<?php

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class AgentRepository
{
     public function info($param){
         //where
         $where=[];
         $orWhere = [];
         if($param['mobile']){
             $where['Telephone']=$param['mobile'];
         }
         if($param['uid']){
             $where['UserId']=$param['uid'];
             $jointUserId = '';
         }
         if($param['AdminName']){
            $where['AdminName']=$param['AdminName'];
         }
         if($param['Status']){
            $where['Status']=$param['Status'];
         }
         if($param['AgentName']){
            $where['AgentName']=$param['AgentName'];
         }

        if(isset($where['UserId'])){
            $jointUserId = DB::table('agent_third_auth')
             ->select('AgentId')
             ->where('UserId','=',$where['UserId'])
             ->first();
            //根据下级搜索反查上级数据
            if($jointUserId){
                $jointUserId = $jointUserId->AgentId;
                $agentUserId = DB::table('agents')
                    ->select('UserId')
                    ->where('AgentId','=',$jointUserId)
                    ->first();
                $where['UserId'] = $agentUserId->UserId;
            }
        }

        //下级代理数量&&下级会员数量
        $preAgentSql = "(select COUNT(*) from agents as b where b.ParentId = a.AgentId) as num";
        $agentPlayerSql = "(select COUNT(*) from agent_third_auth as c where c.AgentId = a.AgentId) as players";
        if( $param['stime'] && $param['etime']){
        $results = DB::table('agents as a')
            ->select('AgentId','AgentName','LastLoginTime','UserId','Telephone','Level','Status','CreateAt','AdminName',DB::raw($preAgentSql),DB::raw($agentPlayerSql))
            ->whereBetween('CreateAt',[$param['stime'],$param['etime']])
            ->where($where)
            ->orWhere($orWhere)
            ->orderby('AgentId','desc')
            ->paginate(10);
        }else{
        $results = DB::table('agents as a')
            ->select('AgentId','AgentName','LastLoginTime','UserId','Telephone','Level','Status','CreateAt','AdminName',DB::raw($preAgentSql),DB::raw($agentPlayerSql))
            ->where($where)
            ->orWhere($orWhere)
            ->orderby('AgentId','desc')
            ->paginate(10);
        }

         return $results;
     }

    public function levelInfo($param){

        //where
        $where['a.ParentId']=$param['pid'];

        if($param['uid']){
            $where['a.UserId']=$param['uid'];
        }

        if( $param['stime'] && $param['etime']){

            $results = DB::table('agents as a')
                ->leftjoin('user_game as b', 'a.UserId', '=', 'b.UserId')
                ->select('a.AgentId', 'a.UserId',DB::raw('SUM(b.Round) as num'),'a.CreateAt','a.UpdateAt')
                ->where($where)
                ->whereBetween('a.CreateAt',[$param['stime'],$param['etime']])
                ->groupby('a.UserId')
                ->orderby('a.AgentId','desc')
                ->paginate(10);
        }else{

            $results = DB::table('agents as a')
                ->leftjoin('user_game as b', 'a.UserId', '=', 'b.UserId')
                ->select('a.AgentId', 'a.UserId',DB::raw('SUM(b.Round) as num'),'a.CreateAt','a.UpdateAt')
                ->where($where)
                ->groupby('a.UserId')
                ->orderby('a.AgentId','desc')
                ->paginate(10);
        }
        return $results;
    }

    //计算出下级玩家：根据third_autu表进行统计，再通过接口拼装，得到对局数
    public function userInfo($param){
        //where
        $where['AgentId']=$param['aid'];
        if($param['uid']){
            $where['UserId']=$param['uid'];
        }
        if( $param['stime'] && $param['etime']){
            $results = DB::table('agent_third_auth')->where($where)
                ->whereBetween('CreateTime',[$param['stime'],$param['etime']])
                // ->groupby('UserId')
                ->orderby('UserId','desc')
                ->paginate(10);
        }else{
            $results = DB::table('agent_third_auth')->where($where)
                // ->groupby('UserId')
                ->orderby('UserId','desc')
                ->paginate(10);
        }
        foreach ($results as $key => $value) {
            $gameInfoData = Self::gameInfoData($value->UserId);
            $results[$key]->LastLoginTime = (isset($gameInfoData['user']['user']) ? $gameInfoData['user']['user'] : '') ? ( isset($gameInfoData['user']['user']['last_login_time']) ? date('Y-m-d H:i:s',$gameInfoData['user']['user']['last_login_time']) : '' ) : '';
            $results[$key]->num = '';
        }
        return $results;
    }

    //提成明细
    public function agentDetail($param){
        //where
        $where['AgentId'] = $param['aid'];
        if($param['type']){
            $where['type'] = $param['type'];
        }
        if( $param['stime'] && $param['etime']){
            $param['stime'] = strtotime($param['stime']);
            $param['etime'] = strtotime($param['etime']);

            $results = DB::table('agent_blance_log')->where($where)
                ->whereBetween('time',[$param['stime'],$param['etime']])
                ->orderby('time','desc')
                // ->toSql();
                ->paginate(10); 
        }else{
            $results = DB::table('agent_blance_log')->where($where)
                ->orderby('time','desc')
                ->paginate(10);
        }

        foreach ($results as $key => $value) {
            $connectDatabase = explode('money', $value->table);
            if(count($connectDatabase)==2){
                $connectDatabase = 'mysql_data_center_money';
                $resutl = DB::connection($connectDatabase)->table($value->table)
                ->where('incr_id','=',$value->incr_id)
                ->first();
                $results[$key]->game_type = '';
                if($resutl){ 
                    $results[$key]->game_type = getGameTypeTag($resutl->game_type,$resutl->table_type);
                }
            }else{
                $results[$key]->game_type = '购买房卡';
            }
        } 
        return $results;
    }

    //总提成(金币、元宝)
    public function commission($param){
        $sumCommissionRmb = $this->sumCommission($param,1);//元宝
        $sumCommissionCoin = $this->sumCommission($param,2);//金币

        $commission['sumCommissionRmb'] = $sumCommissionRmb;
        $commission['sumCommissionCoin'] = $sumCommissionCoin; 

        $agents = DB::table('agents')->where('AgentId','=',$param['aid'])->first();  
        $commission['BalanceCoin'] = $agents->BalanceCoin; 
        $commission['Balance'] = $agents->Balance; 
        return $commission;
    }

    public function sumCommission($param,$type=''){
        //where
        $where['AgentId'] = $param['aid'];
        if($type){
            $where['type'] = $type;
        }
        if( $param['stime'] && $param['etime']){
            $param['stime'] = strtotime($param['stime']);
            $param['etime'] = strtotime($param['etime']);

            $query = DB::table('agent_blance_log')->where($where)
                ->whereBetween('time',[$param['stime'],$param['etime']]);
        }else{
            $query = DB::table('agent_blance_log')->where($where);
        }
        return $query->sum('amount');
    }

    /**
    *   查询会员信息接口
    *   @param $uid [用户ID]
    *   url_sub GameUserInfoUsers and GameUserInfoBase
    */
    public function gameInfoData($uid=''){
        $results['base'] = array();
        $results['user'] = array();
        if(!$uid){
            return null;
        }
        //校验游戏ID start
        $urlBase=DB::table('url_config')->where('url_sub','GameUserInfoBase')->first();
        $urlUser=DB::table('url_config')->where('url_sub','GameUserInfoUsers')->first();
        $client=new Client(['verify'=>false]);
        //base数据
        $responseBase = $client->request('POST', $urlBase->url, [
            'form_params' => [
                'uid' => $uid
            ]
        ]);
        //user数据
        $responseUser = $client->request('POST', $urlUser->url, [
            'form_params' => [
                'uid' => $uid
            ]
        ]);
        if ($responseBase->getStatusCode()==200 && $responseUser->getStatusCode()==200)
        {
            $resultBase=$responseBase->getBody()->getContents(); 
            $resultBase=json_decode($resultBase,true);

            $resultUser=$responseUser->getBody()->getContents(); 
            $resultUser=json_decode($resultUser,true);
            if($resultBase['code']==0){
                $results['base'] = $resultBase;
            }
            if($resultUser['code']==0){
                $results['user'] = $resultUser;
            }
        }
        return $results;
    }

}