<?php

/**
 *
 * @author     : wujunze
 * @link       : https://wujunze.com
 * @since      : 2017/5/4 下午8:44
 * @filesource : Fphp.php
 * @brief      : 框架核心文件
 */

class Fphp
{
    /**
     * @var array 配置文件
     */
    protected $_conf = [];

    /**
     * Fphp constructor.
     * @param array $conf 配置
     */
    public function __construct($conf)
    {
        $this->_conf = $conf;
    }

    /**
     * run app
     */
    public function run()
    {
        spl_autoload_register(array($this, 'loadClass'));
        $this->setDebugENV();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->setDbConfig();
        $this->router();
    }

    /**
     * 框架路由
     */
    public function router()
    {

        $controllerName = $this->_conf['default']['Controller'];
        $actionName = $this->_conf['default']['Action'];
        $param = [];

        $url = $_SERVER['REQUEST_URI'];
        $position = strpos($url, '?');

        $url = $position === false ? $url : substr($url, 0, $position);
        // 删除前后的“/”
        $url = trim($url, '/');
        if ($url) {
            // 使用“/”分割字符串，并保存在数组中
            $urlArray = explode('/', $url);
            // 删除空的数组元素
            $urlArray = array_filter($urlArray);

            // 获取控制器名
            $controllerName = ucfirst($urlArray[0]);

            // 获取动作名
            array_shift($urlArray);
            $actionName = $urlArray ? $urlArray[0] : $actionName;

            // 获取URL参数
            array_shift($urlArray);
            $param = $urlArray ? $urlArray : array();
        }
        // 判断控制器和操作是否存在
        $controller = $controllerName;
        if (!class_exists($controller)) {
            exit($controller . '控制器不存在');
        }
        if (!method_exists($controller, $actionName)) {
            exit($actionName . '方法不存在');
        }

        $this->dispatch($controllerName, $actionName, $param);
    }

    /**
     * 框架调度器
     * @param string $controllerName 类名
     * @param string $actionName 方法名
     * @param array $param 参数
     */
    public function dispatch($controllerName, $actionName, $param)
    {
        $class = new $controllerName($controllerName, $actionName);
        call_user_func_array(array($class, $actionName), $param);
    }

    /**
     * 设置Debug环境变量
     */
    public function setDebugENV()
    {

        if (APP_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
        }

    }

    /**
     * 删除敏感字符
     *
     * @param array $value
     * @return array|string
     */
    private function _stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }

    /**
     * 过滤敏感字符
     */
    public function removeMagicQuotes()
    {
        if (get_magic_quotes_gpc()) {
            $_GET = isset($_GET) ? $this->_stripSlashesDeep($_GET) : '';
            $_POST = isset($_POST) ? $this->_stripSlashesDeep($_POST) : '';
            $_COOKIE = isset($_COOKIE) ? $this->_stripSlashesDeep($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? $this->_stripSlashesDeep($_SESSION) : '';
        }
    }

    /**
     *移除自定义全局变量
     */
    public function unregisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    /**
     * 设置数据库配置文件
     */
    public function setDbConfig()
    {
        if ($this->_conf['db']) {
            Model::setDbConfig($this->_conf['db']);
        }
    }

    /**
     * @param array $class 自动加载
     */
    public static function loadClass($class)
    {
        $frameworks = __DIR__ . '/' . $class . '.php';
        $controllers = APP_ROOT . 'app/controllers/' . $class . '.php';
        $models = APP_ROOT . 'app/models/' . $class . '.php';
        if (file_exists($frameworks)) {
            // 加载框架核心类
            include $frameworks;
        } elseif (file_exists($controllers)) {
            // 加载应用控制器类
            include $controllers;
        } elseif (file_exists($models)) {
            //加载应用模型类
            include $models;
        } else {
            // 错误代码
        }
    }


}