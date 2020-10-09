<?php

namespace app\api\controller;

use think\Config;
use think\Controller;
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
        $gid = input('get.gid');
        $price = input('get.price');

        $STATUS = config('config.STATUS');

        if (!$gid || !$price) {
            $this->response($STATUS['PARAM_MISSING']['code'], $STATUS['PARAM_MISSING']['msg']);
            return;
        }
        $num = 1;
        $where = array('gid' => $gid, 'mid' => $userid);
        $res = Db('cart')->where($where)->count();
        if ($res) {
            $this->response($STATUS['RESULT_CART_EXITS']['code'], $STATUS['RESULT_CART_EXITS']['msg']);
        }
        $data = array('mid' => $userid, 'gid' => $gid, 'num' => $num, 'price' => $price);
        $res = Db('cart')->insert($data);
        if ($res) {
            $this->response($STATUS['SUCCESS']['code'], $STATUS['SUCCESS']['msg']);
        } else {
            $this->response($STATUS['FAIL']['code'], $STATUS['FAIL']['msg']);
        }
    }

    /**
     * 查看购物车中的商品列表
     * @param
     */
    public function getlist()
    {
        $userid = $_GET['userid'];
        $STATUS = config('config.STATUS');

        $list = Db('cart')
            ->alias('c')
            ->join('goods g', 'c.gid=g.id')
            ->field('c.id,g.name,c.price,g.fileurl,c.num')
            ->where('c.mid', $userid)
            ->select();
        $msg = '共有' . count($list) . '条数据';
        $data = array();
        if ($list) {
            $this->response($STATUS['SUCCESS']['code'], $msg, $list);
        } else {
            $this->response($STATUS['RESULT_CART_NULL']['code'], $STATUS['RESULT_CART_NULL']['msg']);
        }
    }

    /**
     * 移除购物车中的商品
     */
    public function remove()
    {
        $id = input('get.id');
        $userid = $_GET['userid'];
        $STATUS = config('config.STATUS');

        if (!$id) {
            $this->response($STATUS['PARAM_MISSING']['code'], $STATUS['PARAM_MISSING']['msg']);
            return;
        }
        $res = Db('cart')->where('mid', $userid)->delete($id);
        if ($res) {
            $this->response($STATUS['SUCCESS']['code'], $STATUS['SUCCESS']['msg']);
        } else {
            $this->response($STATUS['RESULT_CART_NOT_EXITS']['code'], $STATUS['RESULT_CART_NOT_EXITS']['msg']);
        }
    }

    /**
     * 修改购物车中商品的数量
     */
    public function edit()
    {
        $id = input('get.id');
        $num = input('get.num');
        $STATUS = config('config.STATUS');

        if (!$id || !$num) {
            $this->response($STATUS['PARAM_MISSING']['code'], $STATUS['PARAM_MISSING']['msg']);
            return;
        }
        $res = Db('cart')->where('id', $id)->update(array('num' => $num));
        if ($res) {
            $this->response($STATUS['SUCCESS']['code'], $STATUS['SUCCESS']['msg']);
        } else {
            $this->response($STATUS['RESULT_CART_NOT_EXITS']['code'], $STATUS['RESULT_CART_NOT_EXITS']['msg']);
        }
    }
}
