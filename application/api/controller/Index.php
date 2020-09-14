<?php

namespace app\api\controller;

use Exception;
use think\Controller;
use think\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;

class Index extends Controller
{

    public function index()
    {
        $key = md5('itcast');
        $time = time();
        $expire = $time + 14400;
        $token = array(
            "user_id" => 40,
            'user_name' => 'zhangsan'
            //"exp" => $expire
        );
        $jwt = JWT::encode($token, $key);
        echo $jwt;
    }

    public function test()
    {
        $str="yJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyaWQiOjMxOSwidXNlcm5hbWUiOiJcdTY3NGVcdTRlMWNcdThkODUiLCJleHAiOjE1OTY3MDY4OTN9.JATm1tRCFzy6Uq7mgtLR1J4KgH6rE2BVok2GN7C-WIw";
        try {
            $decoded = JWT::decode($str, 'itcast', ['HS256']);
            if ($decoded) {
                echo 1;
            }
        } catch (SignatureInvalidException $e) {
            echo 2;
        } catch (ExpiredException $e) {
            echo 3;
        }catch(Exception $e){
            echo 4;
        }
    }
}
