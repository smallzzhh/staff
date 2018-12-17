<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2018/12/17
 * Time: 下午1:29
 */

namespace app\index\controller;


use app\index\model\CoordinatesModel;
use think\Controller;

class Puser extends Controller
{
    public function index(){

        return view();

    }
    public function pu(){
        $param = $this->request->param();
        $puser = new CoordinatesModel();
        $data = [
            'phone'=>'13260324342',
            'lon'=>$param['lon'],
            'lat'=>$param['lat'],
        ];
        $puser->save($data);
    }
}