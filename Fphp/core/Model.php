<?php

/**
 *
 * @author     : wujunze
 * @link       : https://wujunze.com
 * @since      : 2017/5/4 下午9:23
 * @filesource : Model.php
 * @brief      : 数据层基类
 */


class Model
{

    protected $_model;
    protected $_table;
    protected static $_dbConf = [];

    private $db;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        // 连接数据库
        $this->db = Db::getDbInstance(self::$_dbConf['host'], self::$_dbConf['username'], self::$_dbConf['password'],
            self::$_dbConf['dbname'], self::$_dbConf['charset']);
        // 获取数据库表名
        if (!$this->_table) {
            // 获取模型类名称
            $this->_model = get_class($this);

            // 数据库表名与类名一致
            $this->_table = strtolower($this->_model);
        }
    }

    /**
     * @param $config
     */
    public static function setDbConfig($config)
    {
        self::$_dbConf = $config;

    }

    /**
     * 魔术方法使Model支持SQL中的一些操作
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->db->$method($args);
    }
}
