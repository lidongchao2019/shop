<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

class Goods extends BaseApi
{
    /**
     * 显示商品列表，支持分页与商品搜索
     *
     * @param string search 搜索词
     * @param int page 页码
     */

    public function getList()
    {
        $search = $this->request->request('search');
        $page = $this->request->request('page');
        if (!$page) {
            $page = 1;
        }
        $pageSize = 4;
        $list = Db('Goods')->where('name', 'like', "%$search%")->page($page, $pageSize)->order('id')->select();
        if (!$list) {
            $this->response(0, '没有数据');
        }
        $count = Db('Goods')->where('name', 'like', "%$search%")->count();
        $pageCount = ceil($count / $pageSize);
        $data = array(
            //'code' => 1,
            //'msg' => '查询成功',
            'pageCount' => $pageCount,
            'pageNo' => $page,
            'pageSize' => $pageSize,
            'total' => $count,
            'data' => $list
        );
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        // header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        //return json($data)->header($this->header);
        $this->response(1, '查询成功', $data);
    }

    /**
     * 显示商品详情
     * @param int 商品ID
     */

    public function getInfo()
    {
        $id = $this->request->request('id');
        if (!$id) {
            $this->response(3, '参数不全，请检查');
        }
        $info = Db('Goods')->find($id);
        if ($info) {
            $this->response(1, '查询成功', $info);
        } else {
            $this->response(0, '没有数据');
        }
    }
}
