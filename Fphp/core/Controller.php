<?php

/**
 *
 * @author     : wujunze
 * @link       : https://wujunze.com
 * @since      : 2017/5/4 下午9:23
 * @filesource : Controller.php
 * @brief      : 控制器基类
 */


class Controller
{

    protected $_controller;
    protected $_action;
    protected $_view;

    /**
     * Controller constructor.
     * @param $controller
     * @param $action
     */
    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View();
    }

    /**
     * 设置模板变量
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->_view->set($key, $value);

    }

    /**
     * 渲染视图
     */
    public function render()
    {
         $this->_view->render();
    }
}