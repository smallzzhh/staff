<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2018/11/23
 * Time: 上午11:20
 */

namespace app\common\model;


use think\Db;
use think\Exception;

class SocketModel extends BaseModel
{
    protected $table = 'socket';

    /**获取绑定的socket编号
     * @param int $uid
     * @return mixed
     */
    public function getFd(int $uid){
        return $this->where(['uid'=>$uid])->value('fd');
    }

    /** 解绑
     * @param $fd
     * @param null $uid
     * @return bool
     */
    public function unBind($fd,$uid=null){

        try{
            Db::startTrans();
            if ($uid) {
                $this->where(['uid'=>$uid])->delete();
            } else {
                $this->where(['fd'=>$fd])->delete();
            }
        }catch (Exception $exception){
            Db::rollback();
            return false;
        }
        Db::commit();
        return true;
    }

    /** 绑定
     * @param array $data
     * @return bool
     */
    public function onBind(array $data){
        try{
            Db::startTrans();
            $this->save($data);
            //Db::name('socket')->insert($data);
        }catch (Exception $exception){
            Db::rollback();
            file_put_contents(__DIR__.'bind.txt',var_export($exception->getMessage(),true),FILE_APPEND);
            return false;
        }
        Db::commit();
        return true;
    }
}