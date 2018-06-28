<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class BaseRepository
{
    
    public function findBy($table, $select="*", $where="", $limit=null, $offset=0, $orderBy=null, $sort=null, $isPage=false, $pageSize=0)
    {
        $db = DB::table($table)->select($select);
        if ( ! empty($where) && is_array($where)){
            $this->bWhere($db, $where);
        }
        if ( ! empty($orderBy)) {
            $db->orderby($orderBy, ! empty($sort) ? $sort : 'DESC');
        }
        if ( ! empty($offset)) {
            $db->offset($offset);
        }
        if ( ! empty($limit)) {
            $db->limit($limit);
        }
        if ($isPage) {
            return $db->paginate($pageSize);
        }
        return $db->get();
    }
    
    /**
     * 添加数据
     * @param type $table 表名
     * @param type $data 添加的数据
     * @return boolean
     */
    public function addData($table, $data) {
        DB::beginTransaction();
        try{
            $id = DB::table($table)->insert($data);
            DB::commit();
            return $id;
        } catch (Exception $e) {
             Log::info($e);
             DB::rollback();
             return false;
        }
    }
    
    /**
     * 编辑数据
     * @param type $table 表名
     * @param type $data 修改的数据
     * @return boolean
     */
    public function editData($table, $data, $where) {
        DB::beginTransaction();
        try{
            $db = DB::table($table);
            if ( ! empty($where) && is_array($where)){
                $this->bWhere($db, $where);
                $db->update($data);
            }
            DB::commit();
            return true;
        } catch (Exception $ex) {
             Log::info($ex);
             DB::rollback();
             return false;
        }
    }
    
    /**
     * 条件拼接
     * @param type $db
     * @param type $where
     */
    public function bWhere(& $db, $where) {
        foreach ($where as $val) {
            if (count($val) == 2) {
                $db->where($val[0], "=", $val[1]);
            } elseif (count($val) == 3) {
                $db->where($val[0], $val[1], $val[2]);
            }
        }
    }
    
    /**
     * 删除数据
     * @param type $table
     * @param type $where
     * @return boolean
     */
    public function delData($table, $where){
        DB::beginTransaction();
        try{
            $db = DB::table($table);
            $this->bWhere($db, $where);
            $db->delete();
            DB::commit();
            return true;
        }catch (\Exception $e){
            Log::info($e);
            DB::rollback();
            return false;
        }
    }
}