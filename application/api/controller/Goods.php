<?php

namespace app\api\controller;

use think\Config;
use think\Controller;
use think\Db;
use think\Request;

class Goods extends BaseApi
{
    /**
     * 显示商品列表，支持分页与商品搜索
     *
     * @param string search 搜索词
     * @param int page 页码
     */

    public function getlist()
    {

        $search = input('get.search');
        $page = input('get.page');
        $STATUS = config('config.STATUS');
        if (!$page) {
            $page = 1;
        }

        if (!$search) {
            $search = '';
        }

        $pageSize = 4;
        $list = Db('Goods')->where('name', 'like', "%$search%")->page($page, $pageSize)->order('id')->select();
        if (!$list) {
            $this->response($STATUS['RESULT_NULL']['code'], $STATUS['RESULT_NULL']['msg']);
        }
        $count = Db('Goods')->where('name', 'like', "%$search%")->count();
        $pageCount = ceil($count / $pageSize);
        $data = $list;
        $page = array(
            'pageCount' => $pageCount,
            'pageNo' => (int) $page,
            'pageSize' => $pageSize,
            'total' => $count,
        );
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        // header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        //return json($data)->header($this->header);
        $this->response($STATUS['SUCCESS']['code'], $STATUS['SUCCESS']['msg'], $data, '', $page);
    }

    /**
     * 显示商品详情
     * @param int 商品ID
     */

    public function getinfo()
    {
        $id = input('get.id');
        $STATUS = config('config.STATUS');
        if (!$id) {
            $this->response($STATUS['PARAM_MISSING']['code'],$STATUS['PARAM_MISSING']['msg']);
        }
        $info = Db('Goods')->find($id);
        if ($info) {
            $this->response($STATUS['SUCCESS']['code'], $STATUS['SUCCESS']['msg'], $info);
        } else {
            $this->response($STATUS['RESULT_NULL']['code'], $STATUS['RESULT_NULL']['msg']);
        }
    }
}
