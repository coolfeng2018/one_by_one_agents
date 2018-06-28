<?php
/**
 * Created by PhpStorm.
 * User: LegendX
 * Date: 2018/1/31
 * Time: 17:59
 */

namespace App\Lib;


class VersionAdapter
{
    public static function string2Hex(string $versionString)
    {
        $versionStrArray=explode('.',$versionString);
        $versionInt=0;
        $length=count($versionStrArray);
        for ($i=0;$i<$length;$i++)
        {
            $versionInt+=intval($versionStrArray[$i])<<(8*($length-$i));
        }
        return $versionInt;
    }

    public static function hex2String(int $versionInt)
    {
        $versionStrArray=[];
        for ($i=0;$i<4;$i++)
        {
            array_push($versionStrArray,($versionInt&(255<<((4-$i-1)*8)))>>(4-$i-1)*8);
        }
        $versionString=implode('.',$versionStrArray);
        return $versionString;
    }
    
    public static function implodeVer($ver) {
        $verArr = explode('.', $ver);
        $retVal = 0;
        $step = [100000000, 100000];
        if (count($verArr) != 3) {
            return false;
        }
        for ($i=0; $i<3; $i++) {
            if ($i == 2) {
                $retVal += $verArr[$i];
            } else {
                $retVal += $verArr[$i]*$step[$i];
            }
        } 
        return $retVal;
    }
    
    public static function explodeVer($ver) {
        $p1 = $ver % 100000;
        $p2 = floor($ver/100000) % 1000;
        $p3 = floor($ver/100000000) % 1000;
        return implode('.', [$p3, $p2, $p1]);
    }
}