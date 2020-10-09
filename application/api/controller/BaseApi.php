<?php

namespace app\api\controller;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use think\Config;
use think\Controller;
use think\Exception\HttpResponseException;
use think\Request;
use think\Response;

class BaseApi extends Controller
{
    protected $noLogin = [
        'member/login',
        'member/register',
        'goods/getlist',
        'goods/getinfo',
        'category/index',
    ];

    protected $header = [];

    public function __construct()
    {
        parent::__construct();

        $this->header['Access-Control-Allow-Origin'] = '*';
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
        $STATUS = config('config.STATUS');
        try {
            $jwt = JWT::decode($jwt, 'itcast', ['HS256']);
            $_GET['userid'] = $jwt->userid;
            $_POST['userid'] = $jwt->userid;
        } catch (SignatureInvalidException $e) {
            $this->response($STATUS['USER_ERROR']['code'], $STATUS['USER_ERROR']['msg']);
        } catch (ExpiredException $e) {
            $this->response($STATUS['USER_EXPIR']['code'], $STATUS['USER_EXPIR']['msg']);
        } catch (Exception $e) {
            $this->response($STATUS['USER_JWT_ERROR']['code'], $STATUS['USER_JWT_ERROR']['msg']);
        }
    }

    protected function response($code = '200', $msg = 'success', $data = [], $token = '', $page = [])
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
        ];

        if ($data) {
            $res['data'] = $data;
        }

        if ($token) {
            $res['token'] = $token;
        }

        if ($page) {
            $res['page'] = $page;
        }

        $type = config('default_ajax_return');
        $response = Response::create($res, $type)->header($this->header);
        throw new HttpResponseException($response);
    }
}
