<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DatacenterRepository
{
    public function getInfomation($param, $where) {
        $result = DB::table('dc_log_result.user_result')
            ->select('*')
            ->whereBetween('time',[$param['stime'],$param['etime']])
            ->where($where)
            ->orderBy('time','desc')
            ->paginate(10);
        return $result;
    }
    
    public function getChannel() {
        $result = DB::table('one_by_one.channel_list')
            ->select('code')
            ->get();
        $channel = [];
        foreach ($result as $val) {
            $channel[] = $val->code;
        }

        return $channel;
    }
    
}