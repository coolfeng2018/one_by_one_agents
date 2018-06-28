<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
// use Chumper\Zipper\Zipper;
use Chumper\Zipper\Facades\Zipper;
use Chumper\Zipper\ZipperServiceProvider;
class ConfigRepository
{
    public function todoConfig($type=''){
        $prePath = "downloads";
        $serPath = $prePath.DIRECTORY_SEPARATOR."server";
        $cusPath = $prePath.DIRECTORY_SEPARATOR."customer";
        //创建目录
        if(!file_exists($prePath)){
            if(!mkdir($prePath)){
                exit('Permission denied!');
            }
        }
        if(!file_exists($serPath)){
            if(!mkdir($serPath)){
                exit('Permission denied!');
            }
        }
        if(!file_exists($cusPath)){
            if(!mkdir($cusPath)){
                exit('Permission denied!');
            }
        }
        //取数据，生成文件
        $record = DB::table('user_behavior_record')->get()->toArray();
        foreach ($record as $key => $value) {
            //文件名
            $fileName = $value->table_name.".lua";
            if(in_array($value->table_name ,array('error_code','text','channel_version','task','game_list','horse_message','icon_library', 'item', 'newbie_award', 'share', 'signing', 'shop', 'friendroomdata'))) {
                //服务数组，客户数据组装 服务器的error_code.lua不进行导出
                $serverSelect = json_decode($value->server_record,true);
                $customerSelect = json_decode($value->customer_record,true);
                foreach ($serverSelect as $k => $v) {
                    $serverSelect[$k] = str_replace('server_', '', $v);
                }
                foreach ($customerSelect as $k => $v) {
                    $customerSelect[$k] = str_replace('customer_', '', $v);
                }
                $reulstSer=DB::table($value->table_name)->select($serverSelect)->get()->toArray();
                $reulstCus=DB::table($value->table_name)->select($customerSelect)->get()->toArray();
                switch ($value->table_name) {
                    case 'error_code':
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer,true),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus,true),0);
                        break;
                        //                     case 'value':
                        //                         $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer,true),0);
                        //                         $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus,true),0);
                        //                         break;
                    case 'icon_library':
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer,true),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus,true),0);
                        break;
                    case 'item':
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer,true),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus,true),0);
                        break;
                    case 'newbie_award':
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer,true),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus,true),0);
                        break;
                    case 'signing':
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer,false,true),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus,false,true),0);
                        break;
                    case 'shop':
                        foreach ($reulstSer as $k => $v) {
                            $reulstSer[$k]->icon_name = $v->icon_name ? config('suit.ImgRemoteUrl').$v->icon_name : '';
                        }
                        foreach ($reulstCus as $k => $v) {
                            $reulstCus[$k]->icon_name = $v->icon_name ? config('suit.ImgRemoteUrl').$v->icon_name : '';
                        }
                        $ss = convert_array($serverSelect,$reulstSer);
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus),0);
                        break;
                    case 'friendroomdata':
                        foreach ($reulstSer as $k => $v) {
                            $reulstSer[$k]->is_club = $v->is_club==1 ? true : false;
                        }
                        foreach ($reulstCus as $k => $v) {
                            $reulstCus[$k]->is_club = $v->is_club==1 ? true : false;
                        }
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus),0);
                        break;
                   /*  case 'roomdata':
                        foreach ($reulstSer as $k => $v) {
                            $reulstSer[$k]->open_robot = $v->open_robot==1 ? true : false;
                        }
                        foreach ($reulstCus as $k => $v) {
                            $reulstCus[$k]->open_robot = $v->open_robot==1 ? true : false;
                        }
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer,false,true),0);
                        // $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus),0);
                        break; */
                        
                    default:
                        $luaSer = "return ".convert_data(convert_array($serverSelect,$reulstSer),0);
                        $luaCus = "return ".convert_data(convert_array($customerSelect,$reulstCus),0);
                        break;
                }       
            }else{
                //in_array($value->table_name ,array('robot','value','roomdata','brnn_banker','card_type_general'))
                $typeid_obj = DB::table('gamecfg')->select('key_col')->whereRaw('typeid=2 and memo ="'.$value->table_name.'"')->first(); 
                $typeid = isset($typeid_obj->key_col) ? $typeid_obj->key_col : 0;
                if(!$typeid) {  
                    exit('sys wrong!');
                }
                $data =  DB::table('gamecfg')->whereRaw('o_status=2 and typeid ='.$typeid)->get();
                $key_val = getKeyVal($data); 
                $prev_cfg = easyToComplex($key_val);
                // ksort($prev_cfg);
                $luaSer = $luaCus = "return ".changeToStr($prev_cfg);
                
            } 
            //服务端文件
            if($fileName != 'error_code.lua'){
                $myfileSer = fopen($serPath.DIRECTORY_SEPARATOR.$fileName, "w") or die("Unable to open file ser!");
                fwrite($myfileSer, $luaSer);
                fclose($myfileSer);
            }
            //客户端文件
            $myfileCus = fopen($cusPath.DIRECTORY_SEPARATOR.$fileName, "w") or die("Unable to open file cus!");
            fwrite($myfileCus, $luaCus);
            fclose($myfileCus); 
        }
        $filesSer = glob('downloads/server/*');
        $filesCus = glob('downloads/customer/*');
        Zipper::make('downloads/server.zip')->add($filesSer)->close();
        Zipper::make('downloads/customer.zip')->add($filesCus)->close();
        if($type=='server'){
            $this->down('downloads/server.zip','server_'.date("YmdHis").'.zip');
        }elseif($type=='customer'){
            $this->down('downloads/customer.zip','customer_'.date("YmdHis").'.zip'); 
        }
    }

    function down($file_url,$new_name=''){  
        if(!isset($file_url)||trim($file_url)==''){  
            echo '500';  
        }  
        if(!file_exists($file_url)){ //检查文件是否存在  
            echo '404';  
        } 
        $file_name=basename($file_url);  
        $file_type=explode('.',$file_url);  
        $file_type=$file_type[count($file_type)-1];  
        $file_name=trim($new_name=='')?$file_name:urlencode($new_name);  
        $file_type=fopen($file_url,'r'); //打开文件  
        //输入文件标签 
        header("Content-type: application/octet-stream");  
        header("Accept-Ranges: bytes");  
        header("Accept-Length: ".filesize($file_url));  
        header("Content-Disposition: attachment; filename=".$file_name);  
        //输出文件内容  
        echo fread($file_type,filesize($file_url));  
        fclose($file_type);
    } 

    public function findRecordData(){
        $where['table_name'] = 'task';
        $result=Db::table('user_behavior_record')->select(
                'server_record',
                'customer_record'
                )->where($where)->first();
        return $result;
    }

    public function baseArray($param){
        $select = explode(',', $param['select']); 
        $reulst=DB::table('task')->select(
                $select
                )->get()->toArray(); 
        return convert_array($select,$reulst,false); 
    }

}
