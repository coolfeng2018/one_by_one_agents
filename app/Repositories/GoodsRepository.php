<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 21:18
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class GoodsRepository
{
    public function info($param){

        $where=[];

        if( $param['os'] !=0){
            switch ($param['os']){
                case 1:
                    $where['Platform'] = 0;
                    break;
                case 2:
                    $where['Platform'] = 1;
                    break;
                case 3:
                    $where['Platform'] = 2;
                    break;
            }
        }
        if( $param['kind'] !=0){
            $where['CategoryId']=$param['kind'];
        }
        if( $param['type'] !=0){
            $where['GoodsType']=$param['type'];
        }

        $result= DB::table('goods')
            ->where($where)
            ->orderBy('GoodsId', 'desc')
            ->paginate(10);

        return $result;
    }


}