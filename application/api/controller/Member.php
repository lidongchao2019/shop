<?php

namespace app\api\controller;

use Firebase\JWT\JWT;
use think\Config;
use think\Controller;
use think\Db;

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
        $STUTAS = config('config.STATUS');

        if (!$username || !$password) {
            $this->response($STUTAS['PARAM_MISSING']['code'], $STUTAS['PARAM_MISSING']['msg']);
        }
        $time = date('Y-m-d H:i:s');
        $where = array('username' => $username);
        $res = Db('member')->where($where)->find();
        if ($res) {
            if ($res['password'] == $password) {
                Db('member')->where($where)->update(['l_date' => $time]);
                $data = array(
                    'userid' => $res['id'],
                    'username' => $res['username'],
                    'exp' => time() + 7200,
                );
                $jwt = JWT::encode($data, 'itcast');
                $this->response($STUTAS['SUCCESS']['code'], $STUTAS['SUCCESS']['msg'], null, $jwt);
            } else {
                $this->response($STUTAS['USER_PWD_ERROR']['code'],$STUTAS['USER_PWD_ERROR']['msg']);
            }

        } else {
            $this->response($STUTAS['USER_NOT_EXIST']['code'], $STUTAS['USER_NOT_EXIST']['msg']);
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
        //接收参数
        $username = input('post.username');
        $password = input('post.password');
        $email = input('post.email');
        $STUTAS = config('config.STATUS');

        //判断参数是否有效
        if (!$username || !$password || !$email) {
            $this->response($STUTAS['PARAM_MISSING']['code'], $STUTAS['PARAM_MISSING']['msg']);
        }

        //判断用户名是否存在
        $res = Db('member')->where('username', $username)->find();
        if ($res) {
            $this->response($STUTAS['USER_EXIST']['code'], $STUTAS['USER_EXIST']['msg']);
        }
        $time = date('Y-m-d H:i:s');
        $data = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'r_date' => $time,
        );

        //插入数据
        $res = Db('member')->insert($data);
        $config = array();
        if ($res) {
            $config = $STUTAS['SUCCESS'];
        } else {
            $config = $STUTAS['FAIL'];
        }
        $this->response($config['code'], $config['msg']);
    }
}
