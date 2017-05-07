<?php

/**
 *
 * @author     : wujunze
 * @link       : https://wujunze.com
 * @since      : 2017/5/4 下午9:26
 * @filesource : Db.php
 * @brief      : 简单的封装MySQL数据库操作
 */
namespace Fphp\lib;

class Db
{
    /**
     *
     * 数据库资源实例  用于保存当前实例化后对象
     * @var null
     */
    private static $_instance = null;

    /**
     * 数据库资源句柄
     * @var PDO
     */
    private $_dbHandle;
    protected $_result;
    private $filter = '';

    /**
     * Db constructor.
     * @param $host
     * @param $username
     * @param $password
     * @param $dbname
     * @param $charset
     */
    private function __construct($host, $username, $password, $dbname, $charset)
    {
        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", $host, $dbname, $charset);
            $option = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
            $this->_dbHandle = new PDO($dsn, $username, $password, $option);
        } catch (PDOException $e) {
            exit('错误: ' . $e->getMessage());
        }

    }

    /**
     * 获取数据库对象实例
     *
     * @param $host
     * @param $username
     * @param $password
     * @param $dbname
     * @param $charset
     * @return Db|null
     */
    public static function getDbInstance($host, $username, $password, $dbname, $charset)
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self($host, $username, $password, $dbname, $charset);
        }
        return self::$_instance;
    }

    /**
     * where条件
     * @param array $where
     * @return $this
     */
    public function where($where = array())
    {
        if (isset($where)) {
            $this->filter .= ' WHERE ';
            $this->filter .= implode(' ', $where);
        }

        return $this;
    }

    /**
     * order by 条件
     * @param array $order
     * @return $this
     */
    public function order($order = array())
    {
        if (isset($order)) {
            $this->filter .= ' ORDER BY ';
            $this->filter .= implode(',', $order);
        }

        return $this;
    }

    /**
     *
     * 查询所有
     * @return mixed
     */
    public function selectAll()
    {
        $sql = sprintf("select * from `%s` %s", $this->_table, $this->filter);
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->fetchAll();
    }

    /**
     * 根据条件 (id) 查询
     *
     * @param $id
     * @return mixed
     */
    public function select($id)
    {
        $sql = sprintf("select * from `%s` where `id` = '%s'", $this->_table, $id);
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->fetch();
    }

    /**
     * 根据条件 (id) 删除
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        $sql = sprintf("delete from `%s` where `id` = '%s'", $this->_table, $id);
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->rowCount();
    }

    /**
     *
     * 自定义SQL查询，返回影响的行数
     * @param $sql
     * @return mixed
     */
    public function query($sql)
    {
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->rowCount();
    }

    /**
     *
     * 新增数据
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $sql = sprintf("insert into `%s` %s", $this->_table, $this->formatInsert($data));

        return $this->query($sql);
    }

    /**
     * 修改数据
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $sql = sprintf("update `%s` set %s where `id` = '%s'", $this->_table, $this->formatUpdate($data), $id);

        return $this->query($sql);
    }

    /**
     *
     * 将数组转换成插入格式的sql语句
     * @param $data
     * @return string
     */
    private function formatInsert($data)
    {
        $fields = array();
        $values = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s`", $key);
            $values[] = sprintf("'%s'", $value);
        }

        $field = implode(',', $fields);
        $value = implode(',', $values);

        return sprintf("(%s) values (%s)", $field, $value);
    }

    /**
     * 将数组转换成更新格式的sql语句
     * @param $data
     * @return string
     */
    private function formatUpdate($data)
    {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s` = '%s'", $key, $value);
        }

        return implode(',', $fields);
    }

    /**
     * 声明成私有方法，禁止克隆对象
     */
    private function __clone()
    {
    }

    /**
     * 声明成私有方法，禁止重建对象
     */
    private function __wakeup()
    {
    }
}