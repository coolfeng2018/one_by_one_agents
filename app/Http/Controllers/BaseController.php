<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    public function saveLog($string){
        $arr['uid']=session('admin')['id'];
        $arr['mark']=$string;
        $results = DB::table('logger')->insertGetId($arr);
    }

    public function saveTxtLog($preArr,$data,$string,$id){
        $tmpPre=json_encode(arraypro($preArr,$data));
        $tmpNext=json_encode(arraypro($data,$preArr));
        $uid=session('admin')['id'];
        Log::info('管理员id----'.$uid.'更新'.$string.'id----'.$id.'修改前'.$tmpPre.'修改后'.$tmpNext);
    }
    public function saveTxtLogs($preArr,$data,$string,$id){
        $tmpPre=json_encode($preArr);
        $tmpNext=json_encode($data);
        $uid=session('admin')['id'];
        Log::info('管理员id----'.$uid.'更新'.$string.'id----'.$id.'修改前'.$tmpPre.'修改后'.$tmpNext);
    }
    public function saveAssetsLog($string,$id){
        $uid=session('admin')['id'];
        Log::info('管理员id----'.$uid.'更新用户id----'.$id.'资产'.$string);
    }
    
    /**
     * curl模拟GET/POST发送请求
     * @param string $url 请求的链接
     * @param array $data 请求的参数
     * @param string $timeout 请求超时时间
     * @return mixed
     * @since 1.0.1
     */
    public function curl($url, $data = array(), $timeout = 5) {       
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

}
