<?php
/**
 *
 * @author     : wujunze
 * @link       : https://wujunze.com
 * @since      : 2017/5/4 下午8:25
 * @filesource : index.php
 * @brief      : 入口文件
 */

//定义项目的根目录
define('_ROOT', dirname(__DIR__));

//定义入口文件目录
define('RUN_ROOT', _ROOT . '/runroot/');

//定义框架目录
define('FR_ROOT' , _ROOT . '/Fphp/');

//定义配置文件根目录
define('CONF_ROOT', _ROOT . '/conf/');

//定义APP根目录
define('APP_ROOT' , _ROOT . '/app/');

//定义静态资源根目录
define('ST_ROOT' , _ROOT . '/static/');


// 开启调试模式
define('APP_DEBUG', true);

//加载配置文件
$conf = require_once CONF_ROOT . 'conf.php';

//加载框架核心文件
require_once  FR_ROOT . 'Fphp.php';

//run
(new Fphp($conf))->run();