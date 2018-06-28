<?php

namespace App\Lib;

class PromoCode
{
    function createCode($userId){
        static $sourceString = [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
            'a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'q', 'r',
            's', 't', 'u', 'v', 'w', 'x',
            'y', 'z'
        ];

        $num = $userId;
        $code = '';
        while ($num) {
            $mod = $num % 36;
            $num = (int)($num / 36);
            $code .= "{$sourceString[$mod]}";
        }

        //判断code的长度
        if(strlen($code)<8){
            $code=str_pad($code, 8, 0);
        }
        return $code;
    }
}
