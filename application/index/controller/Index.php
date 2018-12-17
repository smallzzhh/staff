<?php
namespace app\index\controller;

use think\Db;
use think\Exception;

class Index
{
    protected $rule = [];

    function __construct()
    {
	phpinfo();
        $this->rule = [
            'type' => 'mod', // 分表方式
            'num'  => 2     // 分表数量
        ];

    }
    public function getIndex(){
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        return ['code'=>1,'data'=>[]];
    }
    public function getList(){
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        $data = [
            'head'=>[

                '你好','你好','你好',
            ],


        ];
        return ['code'=>1,'data'=>$data];
    }
    public function index(){
        try{
            $id = rand(1,99999999);
            $data = [
                'user_id'=>$id,
                'name'=>rand(100000,99999999),
                'sex'=>rand(100000,99999999),
            ];

            Db::name('member')
                ->partition(['user_id' => $id], "user_id", $this->rule)
                ->insert($data);
        }catch (Exception $exception){
            dump($exception->getMessage());
        }

        dump('1');
    }


    public function select(){
        $id = 1574198;
        $list = Db::name('member')
            ->partition(['user_id' => $id],"user_id", $this->rule)
            ->limit(10)
            ->select();
        dump($list);
    }

}
