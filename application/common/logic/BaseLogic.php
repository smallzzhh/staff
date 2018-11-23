<?php
/**
 * Created by PhpStorm.
 * User: dongmingcui
 * Date: 2017/11/9
 * Time: 下午2:42
 */

namespace app\common\logic;

use app\common\traits\Api;
use think\Controller;

abstract class BaseLogic extends Controller
{
    use Api;
    public function logAdminAdd($uid,$content){
        
    }

    //简单验证手机号
    public function is_number($num){
        $num = is_numeric($num) ? $num : 1; //验证是否字符串
        $num = strlen($num) === 11 ? $num : '';
        if(empty($num)){
            return false;
        }
        if(substr($num,0,1) != 1){
            return false;
        }
        $char = substr($num,1,1);
        $strarr = array(3,4,5,6,7,8,9);

        if(in_array($char,$strarr)){
            return true;
        }
        return false;
    }
}