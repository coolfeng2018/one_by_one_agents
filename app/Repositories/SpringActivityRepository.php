<?php
/**
 * Created by PhpStorm.
 * User: LegendX
 * Date: 2018/2/5
 * Time: 20:41
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class SpringActivityRepository
{
    public function getContactByUser($userId)
    {
        return DB::table('spring_activity')->where('UserId','=',$userId)->first();
    }
}