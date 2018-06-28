<?php
namespace App\Lib;

!defined('ACT_TYPE_NEW') && define('ACT_TYPE_NEW', 1);
!defined('ACT_TYPE_GET') && define('ACT_TYPE_GET', 2);
!defined('ACT_TYPE_MOD') && define('ACT_TYPE_MOD', 3);

//注册
!defined('GAMECOIN_REGISTER') && define('GAMECOIN_REGISTER', 0x0008);
//金币
!defined('GAMECOIN_SYS_ADD') && define('GAMECOIN_SYS_ADD', 0x000A);
!defined('GAMECOIN_SYS_MINUS') && define('GAMECOIN_SYS_MINUS', 0x000B);
//房卡
!defined('ROOMCARD_SYS_ADD') && define('ROOMCARD_SYS_ADD', 0x0046);
!defined('ROOMCARD_SYS_MINUS') && define('ROOMCARD_SYS_MINUS', 0x0047);
//钻石
!defined('DIAMOND_SYS_ADD') && define('DIAMOND_SYS_ADD', 0x0064);
!defined('DIAMOND_SYS_MINUS') && define('DIAMOND_SYS_MINUS', 0x0065);

class MoneyClient {
    private $fd;
    private $ip;
    private $port;


    private function send($buf, $len) {
        $num = socket_write($this->fd, $buf, $len);

        if ($num === false) {
            $errno = socket_last_error($this->fd);
            $error = socket_strerror($errno);
            $log = sprintf("socket_write fail errno:%d error:%s", $errno, $error);
            $this->writelog($log);
        }
        return $num;
    }

    private function recv(&$buf, $len = 1024) {
        $buf = socket_read($this->fd, $len);

        if ($buf === false) {
            $errno = socket_last_error($this->fd);
            $error = socket_strerror($errno);
            $log = sprintf("socket_read fail errno:%d error:%s", $errno, $error);
            $this->writelog($log);
            return false;
        }
        return $buf;
    }

    private function sendpkg($req) {
        $j = json_encode($req);
        $bodylen = strlen($j);

        $sendbuf = "";
        $sendbuf .= pack('i', $bodylen);
        $sendbuf .= $j;

        $this->send($sendbuf, 4 + $bodylen);

        $recv_bodylen = "";
        $recv_bodylen = $this->recv($recv_bodylen, 4);
        $b_len_arr = unpack('i', $recv_bodylen);
        $b_len = $b_len_arr[1];
        $res_j = "";
        $rsp_j = $this->recv($rsp_j, $b_len);

        if (is_string($rsp_j))
            return json_decode($rsp_j, true);
        return array();
    }

    public function __construct() {

        $this->fd   = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->fd === false) {
            $errno = socket_last_error($this->fd);
            $error = socket_strerror($errno);
            $log = sprintf("socket_create fail errno:%d error:%s", $errno, $error);
            $this->writelog($log);
            return;
        }

        // set this socket as block mode
        socket_set_block($this->fd);
    }

    public function __destruct() {
        if (is_resource($this->fd))
            socket_close($this->fd);

        $this->fd = false;
    }

    public function writelog($log) {
        echo $log . "\n";
    }

    public function connect($ip, $port) {
        $this->ip 	= $ip;
        $this->port = $port;

        $ret = socket_connect($this->fd, $this->ip, $this->port);
        if ($ret) {
            return true;
        }


        $errno = socket_last_error($this->fd);
        $error = socket_strerror($errno);
        $log = sprintf("socket_connect error,ip:%s port:%d,errno:%d error:%s", $ip, $port, $errno, $error);
        $this->writelog($log);

        return false;
    }

    public function close() {
        if (is_resource($this->fd))
            socket_close($this->fd);

        $this->fd = false;
    }

    public function getUser($uid) {
        if (empty($uid))
            return false;

        $req = array('act'=>ACT_TYPE_GET, 'uid'=>$uid);
        $retval = $this->sendpkg($req);
        if (empty($retval))
            return false;

        unset($retval['card']);
        unset($retval['money']);
        unset($retval['diamond']);
        unset($retval['reason']);
        return $retval;
    }

    public function newUser($uid, $reason, array $arr) {
        $money 		= isset($arr['money']) 		? $arr['money'] 	: 0;
        $card  		= isset($arr['card'])  		? $arr['card']  	: 0;
        $diamond 	= isset($arr['diamond']) 	? $arr['diamond'] 	: 0;


        if (!is_integer($uid) || !is_integer($reason) || !is_integer($money) || !is_integer($card) || !is_integer($diamond)) {
            return false;
        }
        $req = array('act'=>ACT_TYPE_NEW, 'uid'=>$uid, 'money'=>$money, 'card'=>$card, 'diamond'=>$diamond, 'reason'=>GAMECOIN_SYS_ADD);

        $retval = $this->sendpkg($req);
        if (empty($retval))
            return false;

        unset($retval['card']);
        unset($retval['money']);
        unset($retval['diamond']);
        unset($retval['reason']);
        return $retval;
    }

    public function modUser($uid, $reason, array $arr) {

        $money 		= isset($arr['money']) 		? $arr['money'] 	: 0;
        $card  		= isset($arr['card'])  		? $arr['card']  	: 0;
        $diamond 	= isset($arr['diamond']) 	? $arr['diamond'] 	: 0;

        if (!is_integer($uid) || !is_integer($reason) || !is_integer($money) || !is_integer($card) || !is_integer($diamond)) {
            return false;
        }

        $req = array('act'=>ACT_TYPE_MOD, 'uid'=>$uid, 'money'=>$money, 'card'=>$card, 'diamond'=>$diamond, 'reason'=>$reason);

        $retval = $this->sendpkg($req);
        if (empty($retval)){
            return false;
        }

        unset($retval['card']);
        unset($retval['money']);
        unset($retval['diamond']);
        unset($retval['reason']);
        return $retval;
    }

}



//$moneyclient = new MoneyClient;
//$moneyclient->connect("127.0.0.1", 9988);

//新增
//$data=$moneyclient->newUser(10000289020002, 111, array('money'=>1, 'card'=>1, 'diamond'=>1));
//成功 result:1 newcard,newmoney为玩家最新房卡数和金币数
// $data =["act"=>1,"newcard"=>,"newdiamond"=>,"newmoney"=>,"result"=>1,"uid"=>10000289020002]
// 失败(该uid用户已经注册) result:0
// $data = ["act"=>1,"result"=>0,"uid"=>"10000289020001000"]


//查询
//$data = $moneyclient->getUser(10000466);
// 成功 result:1 newcard,newmoney为玩家最新房卡数和金币数
// $data =["act"=>2,"newcard"=>,"newdiamond"=>,"newmoney"=>,"result"=>1,"uid"=>10000289020001]
// 失败(没有该用户) result:0
// $data = ["act"=>2,"result"=>0,"uid"=>"10000289020001000"]

//修改
//$data=$moneyclient->modUser(10000289020002, 112, array('money'=>1, 'card'=>2, 'diamond'=>2));
//成功 result:1 newcard,newmoney为玩家最新房卡数和金币数
// $data =["act"=>3,"newcard"=>,"newdiamond"=>,"newmoney"=>,"result"=>1,"uid"=>10000289020002]
// 失败(该uid用户已经注册) result:0
// $data = ["act"=>3,"result"=>0,"uid"=>"10000289020002"]

//$moneyclient->close();