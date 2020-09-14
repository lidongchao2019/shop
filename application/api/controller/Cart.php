<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

class Cart extends BaseApi
{
    /**
     * 添加商品至购物车中
     * @param int gid 商品ID
     * @param int price 商品单价
     */

    public function add()
    {
        $userid = $_GET['userid'];
        $gid = $this->request->request('gid');
        $price = $this->request->request('price');
        if (!$gid || !$price) {
            $this->response(3, '参数不全，请检查');
            return;
        }
        $num = 1;
        $where = array('gid' => $gid, 'mid' => $userid);
        $res = Db('cart')->where($where)->count();
        if ($res) {
            $this->response(2, '该商品已添加至您的购物车');
        }
        $data = array('mid' => $userid, 'gid' => $gid, 'num' => $num, 'price' => $price);
        $res = Db('cart')->insert($data);
        if ($res) {
            $this->response(1, '添加成功');
        } else {
            $this->response(0, '添加失败');
        }
    }

    /**
     * 查看购物车中的商品列表
     * @param
     */
    public function getList()
    {
        $userid = $_GET['userid'];
        $list = Db('cart')
            ->alias('c')
            ->join('goods g', 'c.gid=g.id')
            ->field('c.id,g.name,c.price,g.fileurl,c.num')
            ->where('c.mid', $userid)
            ->select();
        $msg = '共有' . count($list) . '条数据';
        $data = array();
        if ($list) {
            $this->response(1, $msg, $list);
        } else {
            $this->response(0, '没有商品在购物车中');
        }
    }

    /**
     * 移除购物车中的商品
     */
    public function remove()
    {
        $id = $this->request->request('id');
        if (!$id) {
            $this->response(3, '参数不全，请检查');
            return;
        }
        $res = Db('cart')->delete($id);
        if ($res) {
            $this->response(1, '删除成功');
        } else {
            $this->response(0, '删除失败');
        }
    }

    /**
     * 修改购物车中商品的数量
     */
    public function edit()
    {
        $id = $this->request->request('id');
        $num = $this->request->request('num');
        if (!$id || !$num) {
            $this->response(3, '参数不全，请检查');
            return;
        }
        $res = Db('cart')->where('id', $id)->update(array('num' => $num));
        if ($res) {
            $this->response(1, '修改成功');
        } else {
            $this->response(0, '修改失败');
        }
    }
}
