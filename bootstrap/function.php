<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use GuzzleHttp\Client;
use LookCloudsClient as SendMessage;

function p($arr){
    echo '<pre>';
    print_r($arr);
    echo '<pre>';
}
//交集差集
function arraypro($pre,$next){

    $mix=array_intersect_assoc($pre,$next);
    if(empty($mix)){
        $res=$pre;
    }else{
        $res=array_diff_assoc($pre,$mix);
    }
    return $res;
}
//日志
function myLog($string){

    $file='/storage/mylogs/'.date('Ymd').'.txt';

    if(!file_exists($file)){
        mkdir( $file,0777,true);
    }
    file_put_contents($file,$string);
}


//array2table
function get_indent($depth){
    $spaces = array();
    for($i=0;$i < $depth;++$i){
        array_push($spaces,' ');
    }
    return implode('',$spaces);
}
function convert_data($obj,$depth){
    if(is_array($obj)){
        $output = array("{\n");
        foreach($obj as $key=>$value){
            array_push($output,get_indent($depth + 1));
            array_push($output,"[");
            array_push($output,convert_data($key,NULL));
            array_push($output,"] = ");
            array_push($output,convert_data($value,$depth + 1));
            array_push($output,",\n");
        }
        array_push($output,get_indent($depth));
        array_push($output,"}");
        return implode("",$output);
    
    }elseif(is_bool($obj)){
        return $obj ? "true":"false";
    }elseif(is_float($obj)){
        return "$obj";
    }elseif(is_string($obj)){
        return"\"$obj\"";
    }elseif(is_int($obj)){
        return "$obj";
    }else{
        throw new Exception("unknown data type" . gettype($obj));
    }
}

//ping2Array
function convert_array($select,$reulst,$special=false,$onTop=false){
    $outData = array(); 
    $inData = array();
    $specialArray = array();
    foreach ($reulst as $key => $value) {
            if(count($select)==2&&$onTop==false){
                //特殊处理 ps:error_code,value
                if($special==true){
                    $outData[$value->{$select[0]}] = $value->{$select[1]};
                }else{
                    $childArray = array();
                    $childArray[$select[1]] = $value->{$select[1]};
                    //键值                             数组 k->v
                    $outData[$value->{$select[0]}] = $childArray;
                }
            }else{
                foreach ($select as $k => $v) {
                    if($k>0){
                        if($v=='award_list' || $v=='awards_list'){//任务管理、签到管理
                            $inData[$v] = add_key_num_array($value->$v,1,true);
                        }elseif($v=='grab_banker_times' && !empty($value->$v)){//房间管理
                            $inData[$v]= json_decode($value->$v,true);
                        }elseif($v=='price' || $v=='goods'){//商城管理
                            $data = json_decode($value->$v,true);
                            if(count($data)==1){
                                $inData[$v] = $data[0];
                            }
                        }else{
                            $inData[$v] = $value->$v;
                        }
                        
                    }
                }
                
                //第一项当作键值
                $outData[$value->{$select[0]}] = $inData;
            }
        
    } 
    //去除空元素
    $outData = $special ? $outData : filter_array($outData);
    // $outData = filter_array($outData);  
    return $outData;
}

//unset empty
function filter_array($arr, $values = ['', null, false,  '0',[]]) {
    foreach ($arr as $k => $v) {
        if (is_array($v) && count($v)>0) {
            $arr[$k] = filter_array($v, $values);
        }
        foreach ($values as $value) {
            if ($v === $value) {
                unset($arr[$k]);
                break;
            }
        }
    }
    return $arr;
}

/**
* @param 
* @data type of $data is json
* @num
*/
function add_key_num_array($data,$num=1,$returnArray=false) {
    if($data){
        $data = json_decode($data,true);
        $dataAwardList = Array();
        foreach ($data as $key => $value) {
                $dataAwardList[$key+$num] = $value;
        }
        if($returnArray){
            $data = $dataAwardList;
        }else{
            $data = json_encode($dataAwardList);
        }
        return $data;
    }
    return;
}


/**
* @param 
* @data type of $data is json
* @num
*/
function getGameTypeTag($gameType,$tableType) {
    $tag = '';
    $oriData = config('gametype.game_type');
    foreach ($oriData as $key => $value) {
        if($key==$gameType && isset($value['table_type'][$tableType])){
            $tag = $value['desc'].$value['table_type'][$tableType];
        }
    }
    return $tag;
}


/**
 * 取出需要的key_val
 * @param unknown $typeid
 * @return array();
 */
function getKeyVal($list) {
    $key_val = array();
    //过滤查询有效配置信息
    foreach($list as $k => $v) {
        if(isset($v->key_col) && isset($v->val_col)) {
            $key_val[$v->key_col] = $v->val_col;
        }
    }
    return $key_val;
}

/**
 * 将key拆分成数组
 * @param array $array
 * @return array:
 */
function easyToComplex($array){
    $data = array();
    if(!empty($array)) {
        foreach($array as $k=>$val) {
            $str = '';
            $key = explode('>', $k);
            foreach($key as $ke=>$v) {
                $str .= '[\''.$v.'\']';
                
            }
            eval("\$data".$str.'=\''.$val.'\';');
        }
    }
    return $data;
}
/**
 * 将数组转换成所需的字符串
 * @param unknown $arr
 * @param number $depth
 * @throws Exception
 * @return string
 */
function changeToStr($val,$depth = 0) {
    if(is_array($val)){
        $str = "{\n";//todo ''
        foreach($val as $key=>$value){
            $str .= getTab($depth+1);//缩进
            if(!is_int($key)) {
                $str .= "[".changeToStr($key,NULL)."] = ";//组装key
            }elseif (is_int($key)){//&& $depth==0 TODO
                $str .= "[".$key."] = ";//组装key
            }
            if(is_array($value)) {
                ksort($value);//按字典排序
                $str .= changeToStr($value,$depth+1);//数组层进递归
            }elseif(strpos($value,';')){
                $arr = explode(";",$value);
                //lua的数组都是从1开始的， 将从0开始的数组转为从1开始
                $value = array();
                foreach($arr as $key=>$val){
                    $value[$key+1] = $val;
                }
                $str .= changeToStr($value,$depth+1);//数组层进递归
            }else{
                
                $str .= changeToStr($value,NULL);//组装val的值
            }
            $str .= ",\n";
        }
        $str .= getTab($depth);
        $str .= "}";
        return $str;
    }elseif(is_bool($val)){
        return "$val";
    }elseif(is_float($val)){
        return "$val";
    }elseif(is_string($val)){
        //对于val_col里如 {-1000000000000000000, 9999999, 50}这样的元素进行 处理
        if(preg_match("/^\{[0-9\-][0-9\, \-]*[0-9\-]\}$/",$val)) {
            return "$val";
        }
        //对于浮点型进行处理
        if(preg_match("/^[0-9\.]+$/",$val)) {
            return "$val";
        }
        //对于布尔进行处理
        if($val == 'true'){
            return "true";
        }
        
        if($val == 'false'){
            return "false";
        }
        if(preg_match("/^\d*$/",$val)) {
            $val = (int)$val;
            return "$val";
        }
      
        
        return"\"$val\"";
    }elseif(is_int($val)){
        return "$val";
    }else{
        throw new Exception("unknown data type" . gettype($val));
    }
}

/**
 * 获取缩进距离
 * @param unknown $depth
 * @return string
 */
function getTab($depth){
    $str = '';
    if(!is_null($depth)) {
        for($i=0;$i < $depth;++$i){
            $str .= "\t";
        }
    }
    return $str;
}

/**
 * 数组分页方法 接口调用
 * @param  array $item "$item = array_slice($data, ($current_page-1)*$perPage, $perPage);" 切分的数据
 * @param  integer $total 总条数
 * @param  integer $perPage 显示页数
 * @param  integer $current_page 当前页
 * @return [type]
 */
function getPageApi($item = [],$total = 1,$perPage = 10,$current_page = 1){
    $paginator = new LengthAwarePaginator($item, $total, $perPage, $current_page, [
        'path' => Paginator::resolveCurrentPath(),
        'pageName' => 'page',
    ]);
    return $paginator;
}

//道具列表
function getProp($id=''){
    $data = [
        1000 => [
            "name" => "RMB",
            "description" => "人民币",
            "icon" => "active/MTUyMDMxMjk0NDI3Nzk1NDM.jpeg",
        ],
        1001 => [
            "name" => "金币",
            "description" => "金币",
            "icon" => "active/MTUxNzU1OTAxNTEwODU5OTQ.png",
        ],
        1002 => [
            "name" => "钻石",
            "description" => "钻石",
            "icon" => "active/MTUxNzgzMzY4OTg4NzY0ODg.png",
        ],
        1003 => [
            "name" => "元宝",
            "description" => "元宝",
            "icon" => "Reward_icon_diamond.png",
        ],
    ];
    if(!$id){
        return;
    }
    if(array_key_exists($id, $data)){
        return $data[$id]['name'];
    }
    return;
}

/**
     * [sendAllEmail 发送邮件]
     * @param  Request $request [object] 对象数组,注意格式
     * stdClass Object
        (
            [title] => 测试1  //邮件标题
            [content] => 测试1 //邮件标题
            [mail_type] => 2 //邮件类型，目前只支持指定玩家
            [range] => 1,20,2,10012 //收件人id，支持一封郵件多人收取
            [attach_list] => [{"id":1001,"count":100},{"id":1002,"count":100}] //可以为空
            [op_user] => admin //发件人昵称
            [coins] => 0 //派发金币 非必填
        )
     * @return [type]           [json]
     */
function sendEmail($request){
    $spe_key = 'e948afae5761018e7af958e0a8bd675a';

    $headers = [];
    $headers['uid'] = $request->range;
    $headers['time'] = time();
    $headers['sign'] = md5($headers['uid'].$headers['time'].$spe_key); 

    $url = env('PORJECT_ONE_BY_ONE_API').'/api/v1/server_api/send_mail';
    $client = new Client();

    $form = [];
    $form['title'] = $request->title;
    $form['content'] = $request->content;
    $form['mail_type'] = $request->mail_type ? $request->mail_type : 2;//1全服(目前不支持) 2指定玩家
    $form['op_user'] = $request->op_user;
    $form['range'] = $request->range;
    if(isset($request->coins)){
        $form['coins'] = $request->coins;
    }

    $responseUsers = $client->request('POST', $url, [
        'json' => $form,
        'headers' => $headers,
        'connect_timeout' => 2
    ]);

    if ($responseUsers->getStatusCode()==200)
    {
        $resultUsers=$responseUsers->getBody()->getContents();
        
        $resultUsers=json_decode($resultUsers,true);
        if($resultUsers['code']==200){
            return true;
        }
    }
    return false;
}


/**
 * curl模拟GET/POST发送请求
 * @param string $url 请求的链接
 * @param array $data 请求的参数
 * @param string $timeout 请求超时时间
 * @return mixed
 * @since 1.0.1
 */
function curl($url, $data = array(), $timeout = 5) { 
    $ch = curl_init();
    if (!empty($data) && $data) {
        if(is_array($data)){
            $formdata = http_build_query($data);
        } else {
            $formdata = $data;
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formdata);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    $result = curl_exec($ch); 
    curl_close($ch);
    return $result;
}


function agentRelation(& $list, $pid = 0) {
    $manages = array();
    foreach ($list as $row) {
        if ($row['pid'] == $pid) {
            $manages[$row['id']] = $row;
            $children = agentRelation($list, $row['id']);
            $children && $manages[$row['id']]['children'] = $children;
        }
    }
    return $manages;
}


/**
 * [object_array 对象转数组]
 * @param  [type] $array [description]
 * @return [type]        [description]
 */
function object_array($array) {  
   if(is_object($array)) {  
       $array = (array)$array;  
   } if(is_array($array)) {  
       foreach($array as $key=>$value) {  
           $array[$key] = object_array($value);  
       }  
   }  
   return $array;  
}  

/**
 * [get_tree_child 查找某一分类的所有子类]
 * @param  [type] $data [description]
 * @param  [type] $parentId  [description] ParentId
 * @return [type]       [description]
 */
function get_tree_child($data, $parentId) {
    $data = object_array($data); 
    $result = array();
    $parentIds = array($parentId);
    do {
        $cids = array();
        $flag = false;
        foreach($parentIds as $parentId) {
            for($i = count($data) - 1; $i >=0 ; $i--) {
                $node = $data[$i];
                if($node['ParentId'] == $parentId) {
                    array_splice($data, $i , 1);
                    $result[] = $node['AgentId'];
                    $cids[] = $node['AgentId'];
                    $flag = true;
                }
            }
        }
        $parentIds = $cids;
    } while($flag === true);
    return $result;
}

/**
 * [get_uid 返回uid]
 * @param  [type] $data [description]
 * @param  [type] $parentId  [description] ParentId
 * @return [type]       [description]
 */
function get_uid($data,$list) {
    $result = [];
    if($list && $data){
        $data = object_array($data); 
        $list = object_array($list); 
        foreach ($list as $key => $value) {
            if(in_array($value['AgentId'], $data)){
                $result[] = $value['UserId'];
            }
        }
    }
    return $result;
}


/**
 * [get_tree_parent 查找某一分类的所有父类]
 * @param  [type] $data [description]
 * @param  [type] $agentId   [description]
 * @return [type]       [description]
 */
function get_tree_parent($data, $agentId) {
    $data = object_array($data); 
    $result = array();
    $obj = array();
    foreach($data as $node) {
        $obj[$node['AgentId']] = $node;
    }    

    $value = isset($obj[$agentId]) ? $obj[$agentId] : null;    
    while($value) {
        $agentId = null;
        foreach($data as $node) {
            if($node['AgentId'] == $value['ParentId']) {
                $agentId = $node['AgentId'];
                $result[] = $node['AgentId'];
                break;
            }
        }
        if($agentId === null) {
            $result[] = $value['ParentId'];
        }
        $value = isset($obj[$agentId]) ? $obj[$agentId] : null;
    }
    unset($obj);
    if($result){
        foreach ($result as $key => $value) {
            if(!$value){
                unset($result[$key]);
            }
        }
    }
    return $result;
}


function headerValidation()
{
    $header = [];
    $timeliness = '1800';//过期时间
    $spe_key = 'e948afae5761018e7af958e0a8bd675a';
    $header['time'] = request()->header('time');
    $header['uid'] = request()->header('uid');
    $header['sign'] = request()->header('sign');

    Log::debug($header);
    //验证头信息
    foreach ($header as $key => $value) {
        if(empty($value)){
            return '您输入的信息不完整，请核对后重新提交。';
        }
    }

    //时效性验证
    if(time()-$header['time']>$timeliness){
        return '签名超时.';
    }

    //验证签名
    $sign = md5($header['uid'].$header['time'].$spe_key);
    // Log::debug('uid:'.$header['uid']);
    // Log::debug('time:'.$header['time']);
    // Log::debug('key:'.$spe_key);
    // Log::debug('sign:'.$sign);
    // Log::debug('no-sign:'.$header['uid'].$header['time'].$spe_key);
    
    // echo $sign;exit;
    if($sign!=$header['sign']){
        return '签名验证失败.';
    }
    return false;
}

/**
 * [widthDrawRange 提取范围]
 * @return [type] [description]
 */
function widthDrawRange(){
    return 50;
}

/**
 * 获取游戏服用户信息直接查mongo
 * @return array
 */
function getMonUserInfo($uid = 0){
    $manager = new \MongoDB\Driver\Manager(env('MONGOAPI'));// 10.0.0.4:27017
    $filter = ['uid' => ['$eq' => (int)$uid]];
    // 查询数据
    $query = new \MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery('yange_data.base', $query);
    
    $base = [];
    foreach ($cursor as $document) {
        $rs =  json_decode( json_encode( $document),true);
        $base = $rs;
    }
    $cursor2 = $manager->executeQuery('yange_data.users', $query);
    
    $users = [];
    foreach ($cursor2 as $document) {
        $rs =  json_decode( json_encode( $document),true);
        $users = $rs;
    }
    return $data=array_merge($base,$users);
    
}

/**
 * 查询用户昵称
 * @param number $uid
 */
function getUserNick($uid = 0) {
    $nick = '';
    $res = getMonUserInfo($uid);
    if(isset($res['uid']) && !empty($res['uid'])) {  
        $nick = isset($res['name']) ? $res['name'] : '--';
    }
    return $nick;
}

/**
 * [sendMessageWidthdraw 提现收益短信通知]
 * @return [type] [description]
 */
function sendMessageWidthdraw($mobile,$templateId="4452",$content){
    $lookCloudsClient = new SendMessage();
    $matchTemplateResult = $lookCloudsClient->sendSmsByTemplate($mobile,$templateId,$content); 
    Log::debug($matchTemplateResult);
    if($matchTemplateResult->result==1){
        return true;
    }
    return false;
}