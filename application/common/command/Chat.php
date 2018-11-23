<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2018/5/31
 * Time: 下午5:39
 */

namespace app\common\command;


use app\common\model\MessageModel;
use app\common\model\SocketModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Exception;

class Chat extends Command
{
    private $service;
    private $socketModel = null;
    private $messageModel = null;
    private static $fd = null;
    protected function configure()
    {
        $this->setName('Chat')->setDescription('启动聊天进程成功');
    }
    function __construct($name = null)
    {
        parent::__construct($name);
        $this->socketModel = new SocketModel();
        $this->messageModel = new MessageModel();
    }

    protected function execute(Input $input, Output $output)
    {
        $this->service = new \swoole_websocket_server("127.0.0.1", 9502);
        $this->service->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode' => 1
        ));
        $this->service->on('Open', array($this, 'onOpen'));
        $this->service->on('Message', array($this, 'onMessage'));
        $this->service->on('Close', array($this, 'onClose'));
        $this->service->start();
    }
    public function onOpen($server, $req)
    {
    }

    public function onMessage($server, $frame)
    {
        $pData = json_decode($frame->data);
        if (!empty($pData->content)) {
            try{
                $receive_fd = $this->socketModel->getFd($pData->receive_id);//获取绑定的fd
                $message = [
                    'send_uid'=>$pData->send_id,
                    'rece_uid'=>$pData->receive_id,
                    'message'=>$pData->content,
                    'create_int'=>time(),
                ];
                $this->messageModel->insert($message);//保存消息
                $server->push($receive_fd, json_encode([$message])); //推送到接收者     注意，推送的数据类型必须是数组对象
            }catch (Exception $exception){
                dump($exception->getMessage());
            }
        } else {
            try{
                $this->socketModel->where(['uid'=>$pData->send_id])->delete();  //首次接入清除绑定
                $info = [
                    'uid'=>$pData->send_id,
                    'fd'=>$frame->fd
                ];
                $res = $this->socketModel->insert($info);  //绑定fd
                if ($res) {
                    $data = $this->messageModel->loadMessage($pData->send_id, $pData->receive_id); //加载历史记录
                } else {
                    $data = array("content" => "无法绑定fd");
                }
                $server->push($frame->fd, json_encode($data)); //推送到发送者
            }catch (Exception $exception){
                $data = array("content" => "无法绑定fd");
                $server->push($frame->fd, json_encode($data)); //推送到发送者
            }
        }
    }
    public function onClose($server, $fd)
    {
        $this->socketModel->where(['fd'=>$fd])->delete();
        echo "断开连接: " . $fd;
    }


}