<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9
 * Time: 16:57
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankruptcyRepository
{
    public function list(){
        $reulst=DB::table('bankruptcy')->select(
            'bankruptcy_id',
            'bankruptcy_name',
            'below',
            'number',
            'amount'
        )->orderBy('bankruptcy_id','desc')->paginate(10);
        return $reulst;
    }

    public function find($bankruptcy_id){
        $reulst=DB::table('bankruptcy')->select(
            'bankruptcy_id',
            'bankruptcy_name',
            'below',
            'number',
            'amount'
        )->where('bankruptcy_id','=',$bankruptcy_id)->first();
        return $reulst;
    }

    public function add($data){

        DB::beginTransaction();
        try{
            DB::table('bankruptcy')->insert($data);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function edit($data,$bankruptcy_id){
        DB::beginTransaction();
        try{
            DB::table('bankruptcy')->where('bankruptcy_id','=',$bankruptcy_id)->update($data);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function del($bankruptcy_id){
        DB::beginTransaction();
        try{
            DB::table('bankruptcy')->where('bankruptcy_id','=',$bankruptcy_id)->delete();
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

}