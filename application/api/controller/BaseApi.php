<?php

namespace app\api\controller;

use Exception;
use think\Controller;
use think\Response;
use think\Request;
use think\Exception\HttpResponseException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;

class BaseApi extends Controller
{
    protected $noLogin = [
        'member/login',
        'member/register',
        'goods/getlist',
        'goods/getinfo',
        'category/index'
    ];

    protected $header = [];

    public function __construct()
    {
        parent::__construct();

        $this->header['Access-Control-Allow-Origin']  = '*';
        $this->header['Access-Control-Allow-Headers'] = 'Origin, X-Requested-With, Content-Type, Accept, Authorization';
        $this->header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        // header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        $path = $this->request->controller(true) . '/' . $this->request->action();
        if (!in_array($path, $this->noLogin)) {
            $this->checkLogin();
        }
    }

    public function checkLogin()
    {
        $jwt = input('token');
        try {
            $jwt = JWT::decode($jwt, 'itcast', ['HS256']);
            $_GET['userid'] = $jwt->userid;
            $_POST['userid'] = $jwt->userid;
        } catch (SignatureInvalidException $e) {
            $this->response(2, '信息篡改，请重新登录');
        } catch (ExpiredException $e) {
            $this->response(3, '时间过期，请重新登录');
        } catch (Exception $e) {
            $this->response(4, '请重新登录');
        }
    }

    protected function response($code = '200', $msg = 'success', $data = [], $token = '')
    {
        $res = [
            'code' => $code,
            'msg' => $msg
        ];

        if ($data) {
            $res['data'] = $data;
        }

        if ($token) {
            $res['token'] = $token;
        }

        $type = config('default_ajax_return');
        $response = Response::create($res, $type)->header($this->header);
        throw  new HttpResponseException($response);
    }
}
