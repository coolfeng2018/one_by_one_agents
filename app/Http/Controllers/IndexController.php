<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    public function index(){
        $source_type = [1,2];//1会员 2代理
        $info = session('admin');
        $address = explode('|', $info['Address']);
        $info['province'] = isset($address[0]) ? $address[0] : '';
        $info['city'] = isset($address[1]) ? $address[1] : '';
        $info['area'] = isset($address[2]) ? $address[2] : '';
        $info['agent_url'] = env('PORJECT_ONE_BY_ONE_API').'/api/v1/onebyone/get_bind_agent?agent_id='.$info['AgentId'];

        $commissionAgentsAtToday = 0;
        $commissionPlayerAtToday = 0;

        $commissionAgents = 0;
        $commissionPlayer = 0;

        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

        $list = DB::table('agents')->orderBy('CreateAt','desc')->where('status','=',1)->get()->toArray();
        $listChildAgents = get_uid(get_tree_child($list, $info['AgentId']),$list);

        //今日下级代理收益
        $commissionAgentsAtToday = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',2)
            ->where('l.AgentId','=',$info['AgentId'])
            ->whereBetween('a.c_time', [$beginToday, $endToday])
            ->sum('amount');

        //下级代理收益
        $commissionAgents = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',2)
            ->where('l.AgentId','=',$info['AgentId'])
            ->sum('amount');

        //今日下级玩家收益
        $commissionPlayerAtToday = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',1)
            ->where('l.AgentId','=',$info['AgentId'])
            ->whereBetween('a.c_time', [$beginToday, $endToday])
            ->sum('amount'); 

        //下级玩家收益
        $commissionPlayer = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',1)
            ->where('l.AgentId','=',$info['AgentId'])
            ->sum('amount'); 

        //今日收益(今日下级代理收益+今日下级玩家收益)
        $commissionAtToday = ($commissionAgentsAtToday + $commissionPlayerAtToday);

        //累计收益(下级代理收益+下级玩家收益)
        $commission = ($commissionAgents + $commissionPlayer);

        //今日新增会员
        $newUserAtToday = DB::table('agent_third_auth')
            ->where('AgentId','=',$info['AgentId'])
            ->whereDate('CreateTime','=',date('Y-m-d'))
            ->count();

        //总会员数
        $userCount = DB::table('agent_third_auth')
            ->where('AgentId',$info['AgentId'])
            ->count();

        //总代理数
        $agentCount = count(get_tree_child($list, $info['AgentId']));

        //今日新增代理
        $newAgentCountAtToday = DB::table('agents')
                ->whereIn('AgentId',get_tree_child($list, $info['AgentId']))
                ->whereDate('CreateAt','=',date('Y-m-d'))
                ->where('status','=',1)
                ->count();

        return view('Index.index',['info'=>$info,'newUserAtToday'=>$newUserAtToday,'userCount'=>$userCount,'newAgentCountAtToday'=>$newAgentCountAtToday,'agentCount'=>$agentCount,'commissionAtToday'=>$commissionAtToday,'commission'=>$commission]);
    }

    /**
     * [MySubordinates 我的下级]
     */
    public function mySubordinates(){
        $info = session('admin');
        $source_type = [1,2];//1会员 2代理
        $list = DB::table('agents')->orderBy('CreateAt','desc')->where('status','=',1)->get()->toArray();
        $childrenAgent = get_tree_child($list, $info['AgentId']);
        $agentsCount = count($childrenAgent);

        $playerCount = DB::table('agent_third_auth')->where('AgentId','=',$info['AgentId'])->count();
        //代理列表
        $agents = [];
        foreach ($list as $key => $value) {
            if(in_array($value->AgentId, $childrenAgent)){
                $agents[] = $value;
            }
        }
        //来自代理的流水
        $commissionAgentData = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',2)
            ->where('l.AgentId','=',$info['AgentId'])
            ->get();
        foreach ($commissionAgentData as $key => $value) {
            $newAgent = DB::table('agent_third_auth')->where('UserId','=',$value->uid)->first();
            if(!$newAgent){
                unset($commissionAgentData[$key]);
            }else{
                $commissionAgentData[$key]->agent_id = $newAgent->AgentId;
            }
        }
        if($agents){
            foreach ($agents as $key => $value) {
                $agentBonus = 0;
                foreach ($commissionAgentData as $k => $v) {
                    if($v->agent_id==$value->AgentId){
                        $agentBonus+=$v->amount;
                    }
                }
                $agents[$key]->income = $agentBonus;//代理收益
                $agents[$key]->Nickname = getUserNick($value->UserId) ? getUserNick($value->UserId) : '';//代理昵称
            }
        }
 
        //玩家列表
        $players = DB::table('agent_third_auth')->where('AgentId','=',$info['AgentId'])->orderBy('CreateTime','desc')->get();
        $playerData = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',1)
            ->where('l.AgentId','=',$info['AgentId'])
            ->get();
        foreach ($players as $key => $value) { 
            $isAgent = DB::table('agents')->where('UserId','=',$value->UserId)->first(); 
            $playerBonus = 0;
            foreach ($playerData as $k => $v) {
                if($v->source_user_id==$value->UserId){
                     $playerBonus+=$v->amount;
                }
            }
            $players[$key]->income = $playerBonus;//代理收益
            $players[$key]->isAgent = $isAgent ? true : false;//是否代理
            $players[$key]->agents = $isAgent ? $isAgent->AgentId : '';//代理信息
            $players[$key]->Nickname = getUserNick($value->UserId) ? getUserNick($value->UserId) : $value->Nickname;//代理昵称
        }
        return view('Index.subordinates',['agentsCount'=>$agentsCount,'playerCount'=>$playerCount,'info'=>$info,'agents'=>$agents,'players'=>$players]);
    }

    /**
     * [playerDetail 会员收益明细]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function playerDetail(Request $request){
        $info = session('admin');
        $userId = $request->userId;

        $page = isset($request['search']) ? $request['search'] : 1;
        $offset = ($page-1)*10;//页数
        $count = 10;//条数

        $players = DB::table('agent_third_auth')->where('UserId','=',$userId)->first(); 
        $playerData = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',1)
            ->where('l.AgentId','=',$info['AgentId'])
            ->where('l.source_user_id','=',$userId)
            ->get();

        //用于显示分页明细
        $playerDataSearch = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',1)
            ->where('l.AgentId','=',$info['AgentId'])
            ->where('l.source_user_id','=',$userId)
            ->skip($offset)->take($count)
            ->orderBy('a.c_time','desc')
            ->get();
        foreach ($playerDataSearch as $key => $value) {
              $playerDataSearch[$key]->c_time = date('Y-m-d H:i:s', $value->c_time);
        }

        if(isset($request['search'])){
            $result['queryPlayer'] = $playerDataSearch;
            return response()->json(['status' => 1, 'msg' => 'ok','result'=>$result]);
        }


        $playerBonus = 0;
        foreach ($playerData as $k => $v) {
            if($v->source_user_id==$players->UserId){
                 $playerBonus+=$v->amount;
            }
        }
        $players->playerBonus = $playerBonus;

        return view('Index.playerDetail',['playerData'=>$playerDataSearch,'players'=>$players,'userId'=>$userId]);
    }


    /**
     * [agentsDetail 代理收益明细]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function agentsDetail(Request $request){
        $info = session('admin');
        $agentId = $request->AgentId;

        $page = isset($request['search']) ? $request['search'] : 1;
        $offset = ($page-1)*10;//页数
        $count = 10;//条数

        $agents = DB::table('agents')->where('AgentId','=',$agentId)->first();
        $agents->Nickname = getUserNick($agents->UserId); 
        $agents->income = 0;
        $player = DB::table('agent_third_auth')->select('UserId')->where('AgentId','=',$agentId)->get()->toArray();
 
  
        //代理流水
        $commissionAgentData = '';
        if($player){
            $players = [];
            foreach ($player as $key => $value) {
                $players[] = $value->UserId;
            } 
            $commissionAgentData = DB::table('agent_blance_log as l')
                ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
                ->where('l.source_type','=',2)
                ->where('l.AgentId','=',$info['AgentId'])
                ->whereIn('l.source_user_id',$players)
                ->orderBy('a.c_time','desc')
                ->skip($offset)
                ->take($count)
                ->get();
            foreach ($commissionAgentData as $key => $value) {
                $commissionAgentData[$key]->c_time = date('Y-m-d H:i:s', $value->c_time);
            }

            $agents->income = DB::table('agent_blance_log as l')
                ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
                ->where('l.source_type','=',2)
                ->where('l.AgentId','=',$info['AgentId'])
                ->whereIn('l.source_user_id',$players)
                ->orderBy('a.c_time','desc')
                ->sum('amount');

        }

        if(isset($request['search'])){
            $result['queryAgent'] = $commissionAgentData;
            return response()->json(['status' => 1, 'msg' => 'ok','result'=>$result]);
        }

        return view('Index.agentsDetail',['commissionAgentData'=>$commissionAgentData,'agents'=>$agents]);
    }

    /**
     * [MySubordinates 添加代理]
     */
    public function addAgents(Request $request){
        $userId = $request->userId;
        if(!$userId){
            exit('error!');
        } 
        $info = session('admin');
        return view('Index.addAgent',['userId'=>$userId]);
    }

    /**
     * [MySubordinates 成为代理]
     * 解绑与之前绑定的下级会员关系
     *  成为上级代理会员
     */
    public function createAgents(Request $request){
        $info = session('admin');
        $request = $request->all();
        //校验
        $isUser = DB::table('agent_third_auth')->where('UserId','=',$request['UserId'])->first();
        $agents = DB::table('agents')->where('Telephone','=',$request['Telephone'])->first();
        $isAgents = DB::table('agents')->where('UserId','=',$request['UserId'])->first();
        if(!$isUser){
            exit('error');
        }elseif ($isAgents) {
            return response()->json(['status' => 0, 'msg' => '已成为代理!']);
        }

        if(!preg_match("/^1[34578]{1}\d{9}$/",$request['Telephone'])){  
            return response()->json(['status' => 0, 'msg' => '手机号码有误,请检查输入!']);
        }elseif($agents){
            return response()->json(['status' => 0, 'msg' => '手机号码已存在!']);
        }

        if(mb_strlen($request['AgentName'],'utf8')>7){
            return response()->json(['status' => 0, 'msg' => '昵称不能超过7位!']);
        }

        if(strlen($request['Password'])>6){
            return response()->json(['status' => 0, 'msg' => '请输入6位数密码!']);
        }

        $rateArray = explode('、', "5、10、15、20、25、30、35");
        if(!in_array($request['Ratio'], $rateArray)){
            return response()->json(['status' => 0, 'msg' => '分成比例只能设置为5%、10%、15%、20%、25%、30%、35%!']);
        }

        //计算总的分成比例不能超过40% 最多只能八个 以当前自身的比例，和下级的代理计算可以分配的比例
        if($info['Ratio']<$request['Ratio']){
            return response()->json(['status' => 0, 'msg' => '超过可支配的分成比例!']);
        }elseif($info['Ratio']-$request['Ratio']<5){
            return response()->json(['status' => 0, 'msg' => '必须保留5%的分成比例!']);
        }
        unset($request['_token']);
        unset($request['_url']);

        DB::beginTransaction();
        try
        {
            $request['AdminName'] = $info['AgentName'];
            $request['ParentId'] = $info['AgentId'];
            $request['Password'] = md5($request['Password']);
            $id = DB::table('agents')->insertGetId($request);
            if(!$id){
                throw new \Exception("设置代理失败,请重试!");
            }
            //更新代理分成比例
            // $update['Ratio'] = $info['Ratio']-$request['Ratio'];
            // DB::table('agents')->where('AgentId','=',$info['AgentId'])->update($update);
            //解绑之前代理关系
            // $updateAuth['CreateTime'] = date('Y-m-d H:i:s',time());
            // $updateAuth['AgentId'] = $id;
            // DB::table('agent_third_auth')->where('UserId','=',$request['UserId'])->update($updateAuth);
            DB::commit();
            $result = DB::table('agents')->where('AgentId','=',$info['AgentId'])->first();
            session(['admin'=>object_array($result)]);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            Log::info($request);
            Log::info($exception->getTraceAsString());
            return response()->json(['status' => 0, 'msg' => '设置代理失败,请重试!']);
        }
        return response()->json(['status' => 1, 'msg' => '设置代理成功!']);

    }

    /**
     * [MySubordinates 申请提现]
     */
    public function widthdraw(){
        $info = session('admin');
        //当前余额
        $result = DB::table('agents')->where('AgentId','=',$info['AgentId'])->first();
        //可提取余额
        $result->extractable = floor($result->Balance / widthDrawRange())*widthDrawRange();
        return view('Index.widthdraw',['info'=>$result]);
    }

    /**
     * [MySubordinates 修改提现信息]
     */
    public function widthdrawUpdate(Request $request){
        $info = session('admin');
        $request = $request->all();
        unset($request['_token']);
        unset($request['_url']);
        DB::beginTransaction();
        try
        {
            $result = DB::table('agents')->where('AgentId','=',$info['AgentId'])->update($request);
            if(!$result){
                throw new \Exception("修改失败,请重试!");
            }
            DB::commit();
            $result = DB::table('agents')->where('AgentId','=',$info['AgentId'])->first();
            session(['admin'=>object_array($result)]);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            Log::info($request);
            Log::info($exception->getTraceAsString());
            return response()->json(['status' => 0, 'msg' => '修改失败,请重试!']);
        }
        return response()->json(['status' => 1, 'msg' => '修改成功!']);
    }

    /**
     * [widthdrawAgent 申请提现]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function widthdrawAgent(Request $request){
        $info = session('admin');
        $request = $request->all();
 
        $paramString = 'widthdrawAmout,mode,AlipayCode,AlipayName,WithdrawWechatCode,CreditCode,CreditBank,CreditName';
        $paramName = [
            'widthdrawAmout' => '提取金额',
            'mode' => '提取方式',
            'AlipayCode' => '支付宝账号',
            'AlipayName' => '用户名',
            'WithdrawWechatCode' => '微信账号',
            'CreditCode' => '银行卡号',
            'CreditBank' => '银行',
            'CreditName' => '真实姓名',
        ];
        switch ($request['mode']) {
            case 'bank':
                $paramString = 'widthdrawAmout,mode,CreditCode,CreditBank,CreditName';
                $paramName = [
                    'widthdrawAmout' => '提取金额',
                    'mode' => '提取方式',
                    'CreditCode' => '银行卡号',
                    'CreditBank' => '银行',
                    'CreditName' => '真实姓名',
                ];
                break;

            case 'wechat':
                $paramString = 'widthdrawAmout,mode,WithdrawWechatCode';
                $paramName = [
                    'widthdrawAmout' => '提取金额',
                    'mode' => '提取方式',
                    'WithdrawWechatCode' => '微信账号'
                ];
                break;

            case 'alipay':
                $paramString = 'widthdrawAmout,mode,AlipayCode,AlipayName';
                $paramName = [
                    'widthdrawAmout' => '提取金额',
                    'mode' => '提取方式',
                    'AlipayCode' => '支付宝账号',
                    'AlipayName' => '用户名',
                ];
                break;

            
            default:
                # code...
                break;
        }

        $data = explode(',', $paramString); 
        foreach ($request as $key => $value) {
            if(!in_array($key, $data)){
                unset($request[$key]);
            }
        }
        unset($request['_token']);
        foreach ($request as $key => $value) {
            if(!in_array($key, $data) || empty($value)){
                return response()->json(['status' => 0, 'msg' => $paramName[$key].'不能为空,请检查输入!']);
            }
            if($key=='widthdrawAmout'){
                if(!preg_match("/^\d*$/",$value)){
                    return response()->json(['status' => 0, 'msg' => '请输入正确的金额!']);
                }
            }
        }

        $agents = DB::table('agents')->where('AgentId','=',$info['AgentId'])->first();
        if ((float)($request['widthdrawAmout'])<=0)
        {
            return response()->json(['status' => 0, 'msg' => '提现失败!']);
        }

        $rangeMone = floor($agents->Balance / widthDrawRange())*widthDrawRange();
        if((float)($request['widthdrawAmout']>$rangeMone)){
            return response()->json(['status' => 0, 'msg' => '超过可提余额范围!']);
        }

        $upData = [];
        switch ($request['mode']) {
            case 'bank':
                $withdrawChannel = 0;
                $withdrawInfo = "银行账号：".$request['CreditCode']."；开户银行：".$request['CreditBank']."；真实姓名：".$request['CreditName'];
                $upData['CreditCode'] = $request['CreditCode'];
                $upData['CreditBank'] = $request['CreditBank'];
                $upData['CreditName'] = $request['CreditName'];
                break;

            case 'alipay':
                $withdrawChannel = 1;
                $withdrawInfo = "支付宝账号：".$request['AlipayCode']."；用户名：".$request['AlipayName'];
                $upData['AlipayCode'] = $request['AlipayCode'];
                $upData['AlipayName'] = $request['AlipayName'];
                break;

            case 'wechat':
                $withdrawChannel = 2;
                $withdrawInfo = "微信账号：".$request['WithdrawWechatCode'];
                $upData['WithdrawWechatCode'] = $request['WithdrawWechatCode'];
                break;
            
            default:
                $withdrawChannel = 1;
                $withdrawInfo = "支付宝账号：".$request['AlipayCode']."；用户名：".$request['AlipayName'];
                $upData['AlipayCode'] = $request['AlipayCode'];
                $upData['AlipayName'] = $request['AlipayName'];
                break;
        }

        $amount = (float)$request['widthdrawAmout'];//提现金额
        //金额50的整数倍验证
        if(!is_int($request['widthdrawAmout']/widthDrawRange()) || $request['widthdrawAmout']<widthDrawRange()){
            return response()->json(['status' => 0, 'msg' => '每次可提都是50或50以上的整数!']);
        }
        $originBalance = $agents->Balance;//初始金额
        $agents->Balance=sprintf("%.3f",$agents->Balance-$amount);//提现后金额
        Log::debug($agents->Balance);
        if ($agents->Balance<0)
        {
            return response()->json(['status' => 0, 'msg' => '超过可提余额范围!']);
        }
        $withdraw = [];
        $withdraw['AgentId']=$agents->AgentId;
        $withdraw['Amount']=$amount;
        $withdraw['CurrentBalance']=$agents->Balance;
        $withdraw['WithdrawChannel']=$withdrawChannel;
        $withdraw['WithdrawInfo']=$withdrawInfo;
 
        DB::beginTransaction();
        try
        {
            $upData['Balance'] = $agents->Balance;
            $where['AgentId'] = $agents->AgentId; 
            $where['Balance'] = $originBalance;
            $result = DB::table('agents')->where($where)->where('Balance','>=',$amount)->update($upData);
            $resultWithdraw = DB::table('agent_withdraw')->insert($withdraw);
            if(!$result || !$resultWithdraw){
                throw new \Exception("更新失败");
            }
            DB::commit();
            // Redis::set($token,json_encode($agents));
            $info['Balance'] -=$amount;
            session(['admin'=>$info]);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            Log::info($upData);
            Log::info($withdraw);
            Log::info($exception->getTraceAsString());
            return response()->json(['status' => 0, 'msg' => '提现失败,请重试!']);
        }
        $rangeMone = $rangeMone-$amount;
        return response()->json(['status' => 1, 'msg' => '提取成功!', 'balance' => "{$upData['Balance']}", 'rangeMone' => "{$rangeMone}"]);

    }

    /**
     * [updateProfile 修改个人资料]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateProfile(Request $request){
        $info = session('admin'); 
        $request = $request->all();
        $request['Address'] = $request['province'].'|'.$request['city'].'|'.$request['area'];
        unset($request['_token']);
        unset($request['_url']);
        unset($request['province']);
        unset($request['city']);
        unset($request['area']); 

        $upData = [];
        $upData['RealName'] = isset($request['RealName']) ? $request['RealName'] : '';
        $upData['WechatCode'] = isset($request['WechatCode']) ? $request['WechatCode'] : '';
        $upData['QQCode'] = isset($request['QQCode']) ? $request['QQCode'] : '';
        $upData['Email'] = isset($request['Email']) ? $request['Email'] : '';
        $upData['Address'] = isset($request['Address']) ? $request['Address'] : '';

        DB::beginTransaction();
        try
        {
            $result = DB::table('agents')->where('AgentId','=',$info['AgentId'])->update($request);
            if(!$result){
                throw new \Exception("修改失败,请重试!");
            }
            DB::commit();
            $result = DB::table('agents')->where('AgentId','=',$info['AgentId'])->first();
            session(['admin'=>object_array($result)]);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            Log::info($request);
            Log::info($exception->getTraceAsString());
            return response()->json(['status' => 0, 'msg' => '修改失败,请重试!']);
        }
        return response()->json(['status' => 1, 'msg' => '修改成功!']);
    }

    /**
     * [widthdrawDetail 提现记录]
     * @return [type] [description]
     */
    public function widthdrawDetail(Request $request){
        $result = [];
        $info = session('admin');
        $page = $request->page ? $request->page : 1;
        $count = 20;
        $offset = ($page-1)*$count;
        DB::connection()->enableQueryLog();
        $result = DB::table('agent_withdraw')->where('AgentId',$info['AgentId'])
            ->orderBy('CreateAt','desc')
            ->skip($offset)
            ->take($count)
            ->get(); 
        DB::getQueryLog();
        if($request->paging && $request->page>1){
            return response()->json(['status' =>0, 'result' => $result, 'page' => $page]);
        }
        return view('Index.widthdrawDetail',['result'=>$result]);
    }

    /**
     * [profitDetail 收益明细]
     * @return [type] [description]
     */
    public function profitDetail(Request $request){
        $source_type = [1,2];//1会员 2代理
        $request = $request->all();

        $page = isset($request['search']) ? $request['search'] : 1;
        $offset = ($page-1)*10;//页数
        $count = 10;//条数

        $info = session('admin');
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        //今日代理收益
        $commissionAgentsAtToday = 0;
        //今日会员收益
        $commissionPlayerAtToday = 0;
        $info = session('admin');
        // DB::connection()->enableQueryLog();
        $commissionAgentsAtToday = DB::table('agent_blance_log as l')
                ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
                ->where('l.source_type','=',2)
                ->where('l.AgentId','=',$info['AgentId'])
                ->whereBetween('a.c_time', [$beginToday, $endToday])
                ->sum('amount');


        $commissionPlayerAtToday = DB::table('agent_blance_log as l')
                ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
                ->where('l.source_type','=',1)
                ->where('l.AgentId','=',$info['AgentId'])
                ->whereBetween('a.c_time', [$beginToday, $endToday])
                ->sum('amount');

        //今日收益(今日下级代理收益+今日下级玩家收益)
        $commissionAtToday = ($commissionAgentsAtToday + $commissionPlayerAtToday);
 
        if(isset($request['search'])){
            $startTime = $request['startTime'];
            $endTime = $request['endTime'];
            //代理流水
            $queryAgent = DB::table('agent_blance_log as l')->join('agent_bonus as a', 'l.incr_id', '=', 'a.id');
            $queryAgent= $queryAgent->where('l.source_type','=',2)->where('l.AgentId','=',$info['AgentId']);
            if(isset($request['UserId']) || $request['UserId']){
                $queryAgent=$queryAgent->where('l.source_user_id','=',$request['UserId']);
            }
            //会员流水
            $queryPlayer = DB::table('agent_blance_log as l')->join('agent_bonus as a', 'l.incr_id', '=', 'a.id');
            $queryPlayer = $queryPlayer->where('l.source_type','=',1)->where('l.AgentId','=',$info['AgentId']);
            if(isset($request['UserId']) || $request['UserId']){
                $queryPlayer=$queryPlayer->where('l.source_user_id','=',$request['UserId']);
            }

            if ($startTime)
            {
                $startTime = $startTime.' 00:00:00';
                $startTime = strtotime($startTime);
                $queryAgent = $queryAgent->where('a.c_time','>=',$startTime);
                $queryPlayer = $queryPlayer->where('a.c_time','>=',$startTime);
            }
            if ($endTime)
            {
                $endTime = $endTime.' 23:59:59';
                $endTime = strtotime($endTime);
                $queryAgent = $queryAgent->where('a.c_time','<=',$endTime);
                $queryPlayer = $queryPlayer->where('a.c_time','<=',$endTime);
            }

            $queryAgent = $queryAgent->orderByDesc('a.c_time')->skip($offset)->take($count)->get();
            $queryPlayer = $queryPlayer->orderByDesc('a.c_time')->skip($offset)->take($count)->get();
            $result = [];

            if($queryAgent){
                foreach ($queryAgent as $key => $value) {
                    $queryAgent[$key]->c_time = date('Y-m-d H:i:s',$value->c_time);
                    $queryAgent[$key]->Nickname = getUserNick($value->uid); 
                }
            } 
            if($queryPlayer){
                foreach ($queryPlayer as $key => $value) {
                    $queryPlayer[$key]->c_time = date('Y-m-d H:i:s',$value->c_time);
                    $queryPlayer[$key]->Nickname = getUserNick($value->uid); 
                }
            } 
            if($queryAgent){
                foreach ($queryAgent as $key => $value) {
                    $newAgent = DB::table('agent_third_auth')->where('UserId','=',$value->uid)->first();
                    $queryAgent[$key]->agent_id = $newAgent->AgentId;
                }
            }

            $result['queryAgent'] = $queryAgent;
            $result['queryPlayer'] = $queryPlayer;
            return response()->json(['status' => 1, 'msg' => 'ok','result'=>$result]);
        }

        //代理流水
        $commissionAgentData = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',2)
            ->where('l.AgentId','=',$info['AgentId'])
            ->orderBy('a.c_time','desc')
            ->skip($offset)
            ->take($count)
            ->get();


        foreach ($commissionAgentData as $key => $value) {
            $newAgent = DB::table('agent_third_auth')->where('UserId','=',$value->uid)->first();
            $commissionAgentData[$key]->agent_id = $newAgent->AgentId;
            $commissionAgentData[$key]->Nickname = getUserNick($value->uid); 
        }
        //会员流水
        $commissionPlayerData = DB::table('agent_blance_log as l')
            ->join('agent_bonus as a', 'l.incr_id', '=', 'a.id')
            ->where('l.source_type','=',1)
            ->where('l.AgentId','=',$info['AgentId'])
            ->orderBy('a.c_time','desc')
            ->skip($offset)
            ->take($count)
            ->get();
        foreach ($commissionPlayerData as $key => $value) {
            $commissionPlayerData[$key]->Nickname = getUserNick($value->uid); 
        }
        return view('Index.profitDetail',['commissionAtToday'=>$commissionAtToday,'commissionPlayerAtToday'=>$commissionPlayerAtToday,'commissionAgentData'=>$commissionAgentData,'commissionAgentsAtToday'=>$commissionAgentsAtToday,'commissionPlayerData'=>$commissionPlayerData]);
    }

    public function access(){
        return view('Index.access');
    }

    protected function objectToArray($obj){
        $arr=json_decode(json_encode($obj), true);
        return $arr;
    }
}
