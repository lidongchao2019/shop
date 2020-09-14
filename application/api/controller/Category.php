<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

class Category extends BaseApi
{
    public function sort($data, $pid = 0, $level = 1)
    {
        static $arr = array();
        foreach ($data as $key => $value) {
            if ($value['pid'] == $pid) {
                $value["level"] = $level;
                $arr[] = $value;
                $this->sort($data, $value['id'], $level + 1);
            }
        }
        return $arr;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data = array(
            array('id' => 5, 'name' => '中国', 'pid' => 0),
            array('id' => 6, 'name' => '北京', 'pid' => 5),
            array('id' => 7, 'name' => '纽约', 'pid' => 8),
            array('id' => 8, 'name' => '美国', 'pid' => 0),
            array('id' => 9, 'name' => '广东', 'pid' => 5),
            array('id' => 10, 'name' => '福建', 'pid' => 5),
            array('id' => 10, 'name' => '新泽西', 'pid' => 8)
        );
        $list = $this->sort($data);
        return json_encode($list);
    }

    public function getList()
    {
        $id = $this->request->request('id');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
