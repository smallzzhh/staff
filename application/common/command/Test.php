<?php
/**
 * Created by PhpStorm.
 * User: dongmingcui
 * Date: 2017/11/15
 * Time: 上午10:40
 */

namespace app\common\command;

use app\common\model\MessageModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('Test')->setDescription('Here is the test ');
    }

    protected function execute(Input $input, Output $output)
    {
        $send_id = 1;
        $and = '';
        $receive_id = 2;
        $me = new MessageModel();
        $where = "(send_uid=$send_id and rece_uid = $receive_id) or (rece_uid=$send_id and send_uid = $receive_id)".$and;
        $list = $me->where($where)->page(1,10)->order('id desc')->select();
        $count = count($list)-1;
        foreach ($list as $k=>$v){
            $list[$k] = $v;
            $count --;
        }
        $output->writeln("TestCommand:");
    }
}