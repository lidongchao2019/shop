<?php
return [
    'STATUS' => [
        'SUCCESS' => ['code' => 2000, 'msg' => '操作成功'],
        'FAIL' => ['code' => 4000, 'msg' => '操作失败'],

        'PARAM_MISSING' => ['code' => 4101, 'msg' => '缺少参数'],
        'PARAM_NULL' => ['code' => 4102, 'msg' => '参数值不能为空'],
        'PARAM_INVALID' => ['code' => 4103, 'msg' => '参数值无效'],
        'PARAM_GOODSID_INVALID' => ['code' => 4104, 'msg' => '商品ID无效'],
        'PARAM_NUM_INVALID' => ['code' => 4105, 'msg' => '商品数量无效'],
        'PARAM_PAGE_INVALID' => ['code' => 4106, 'msg' => '页码参数无效'],

        'USER_EXIST' => ['code' => 4201, 'msg' => '用户名已存在'],
        'USER_NOT_EXIST' => ['code' => 4202, 'msg' => '用户名不存在'],
        'USER_PWD_ERROR' => ['code' => 4203, 'msg' => '密码不正确'],
        'USER_EXPIR' => ['code' => 4204, 'msg' => '登录时间过期，请重新登录'],
        'USER_ERROR' => ['code' => 4205, 'msg' => '登录信息篡改，请重新登录'],
        'USER_JWT_ERROR' => ['code' => 4206, 'msg' => '用户身份错误，请重新登录'],

        'RESULT_NULL' => ['code' => 4301, 'msg' => '没有数据'],
        'RESULT_CART_NULL' => ['code' => 4302, 'msg' => '购物车中没有任何商品'],
        'RESULT_CART_EXITS' => ['code' => 4303, 'msg' => '该商品已添加至您购物车中'],
        'RESULT_CART_NOT_EXITS' => ['code' => 4304, 'msg' => '购物车中没有该商品'],

    ],

    'JWT_SECRET' => 'itheima',
    'JWT_EXPIR' => '2h',
];
