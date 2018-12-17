<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2018/12/14
 * Time: 上午10:55
 */

namespace app\index\controller;


class Pointstables
{
    protected $num = 10; // 默认分表个数

    /** 获取表名
     * @param $id
     * @return int
     */
    public function getTable($id){
        return $id % $this->num;
    }





}