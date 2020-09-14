<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use Firebase\JWT\JWT;

class Member extends BaseApi
{

    /**
     * 会员登录接口
     * @param string $username
     * @param string $password
     */

    public function login()
    {
        $username = input('post.username');
        $password = input('post.password');
        if (!$username || !$password) {
            $this->response(3, '参数不全，请检查');
        }
        $time = date('Y-m-d H:i:s');
        $where = array('username' => $username, 'password' => $password);
        $res = Db('member')->where($where)->find();
        if ($res) {
            Db('member')->where($where)->update(['l_date' => $time]);
            $data = array(
                'userid' => $res['id'],
                'username' => $res['username'],
                'exp' => time() + 7200
            );
            $jwt = JWT::encode($data, 'itcast');
            $this->response(1, '登录成功', null, $jwt);
        } else {
            $this->response(0, '没有这个会员');
        }
    }

    /**
     * 会员注册接口
     * @param string username
     * @param string password
     * @param string email
     */

    public function register()
    {
        $username = $this->request->request('username');
        $password = $this->request->request('password');
        $email = $this->request->request('email');
        if (!$username || !$password || !$email) {
            $this->response(3, '参数不全，请检查');
        }
        $res = Db('member')->where('username', $username)->find();
        if ($res) {
            $this->response(2, '用户名已存在');
        }
        $time = date('Y-m-d H:i:s');
        $data = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'r_date' => $time
        );
        $res = Db('member')->insert($data);
        if ($res) {
            $this->response(1, '注册成功');
        } else {
            $this->response(0, '注册失败');
        }
    }
}
