<?php

/**
 *
 * @author     : wujunze
 * @link       : https://wujunze.com
 * @since      : 2017/5/7 下午5:23
 * @filesource : HomeData.php
 * @brief      :
 */


class Home extends Controller
{
    // 首页方法，测试框架自定义DB查询
    public function index()
    {
        $items = (new HomeData())->selectAll();
        $this->set('title', '全部条目');
        $this->set('items', $items);
        $this->render();
    }

    // 添加记录，测试框架DB记录创建（Create）
    public function add()
    {
        $data['item_name'] = $_POST['value'];
        $count = (new HomeData)->add($data);
        $this->set('title', '添加成功');
        $this->set('count', $count);
        $this->render();
    }

    // 查看记录，测试框架DB记录读取（Read）
    public function view($id = null)
    {
        $item = (new HomeData)->select($id);
        $this->set('title', '正在查看' . $item['item_name']);
        $this->set('item', $item);
        $this->render();
    }

    // 更新记录，测试框架DB记录更新（Update）
    public function update()
    {
        $data = array('id' => $_POST['id'], 'item_name' => $_POST['value']);
        $count = (new HomeData)->update($data['id'], $data);
        $this->set('title', '修改成功');
        $this->set('count', $count);
        $this->render();
    }

    // 删除记录，测试框架DB记录删除（Delete）
    public function delete($id = null)
    {
        $count = (new HomeData)->delete($id);
        $this->set('title', '删除成功');
        $this->set('count', $count);
        $this->render();
    }
}