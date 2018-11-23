<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2018/11/23
 * Time: 上午11:19
 */

namespace app\common\model;


use think\Db;
use think\Exception;

class MessageModel extends BaseModel
{
    protected $table = 'message';
    /** 保存消息记录
     * @param $data
     * @return bool
     */
    public function add($data){
        try{
            $this->save($data);
            //Db::name('message')->insert($data);
            #file_put_contents(__DIR__.'11t2t.txt',$this->getLastSql(),FILE_APPEND);
        }catch (Exception $exception){
            dump($exception->getMessage());
            //return false;
        }
        return true;
    }

    /** 加载消息
     * @param $send_id
     * @param $receive_id
     * @param int $id
     * @return bool|false|\PDOStatement|string|\think\Collection
     */
    public function loadMessage($send_id, $receive_id,$id=0){
        try{
            $and = $id ? " and id=$id" : '';
            $where = "(send_uid=$send_id and rece_uid = $receive_id) or (rece_uid=$send_id and send_uid = $receive_id)".$and;
            $list = $this->where($where)->page(1,10)->order('id asc')->select();
        }catch (Exception $exception){
            return false;
        }
        return $list;
    }
}