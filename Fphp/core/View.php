<?php

/**
 *
 * @author     : wujunze
 * @link       : https://wujunze.com
 * @since      : 2017/5/4 下午9:24
 * @filesource : View.php
 * @brief      : 视图基类
 */

class View
{
    protected $variables = [];
    protected $_controller;
    protected $_action;

    /**
     * View constructor.
     * @param $controller
     * @param $action
     */
    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    /**
     * 设置模板变量
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->variables[$key] = $value;
    }


    /**
     *渲染视图
     */
    public function render()
    {
        extract($this->variables);

        $defaultHeader = APP_ROOT . 'app/views/header.php';
        $defaultFooter = APP_ROOT . 'app/views/footer.php';


        $controllerHeader = APP_ROOT . 'application/views/' . $this->_controller . '/header.php';
        $controllerFooter = APP_ROOT . 'application/views/' . $this->_controller . '/footer.php';
        $controllerLayout = APP_ROOT . 'application/views/' . $this->_controller . '/' . $this->_action . '.php';

        // 页头文件
        if (file_exists($controllerHeader)) {
            include($controllerHeader);
        } else {
            include($defaultHeader);
        }
        include($controllerLayout);

        // 页脚文件
        if (file_exists($controllerFooter)) {
            include($controllerFooter);
        } else {
            include($defaultFooter);
        }
    }
}