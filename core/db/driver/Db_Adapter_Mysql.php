<?php

/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/19
 * Time: 7:50
 */
namespace core\db\driver;
use core\db\Db_Adapter;
use core\lib\Conf;
use core\lib\LogFactory;
use core\lib\Model;

class Db_Adapter_Mysql extends  Db_Adapter
{
    static $pdo=null;
    /**
     *获取数据库连接对象PDO
     */
    static function connect(){

        if(is_null(self::$pdo)) {
            try{
                $db_config = Conf::all('db');
                $dsn="mysql:host=".$db_config['host'].";dbname=".$db_config['database'];
                $pdo=new \PDO($dsn, $db_config['username'], $db_config['pwd'], array(\PDO::ATTR_PERSISTENT=>true));
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $pdo->exec("SET NAMES 'utf8'");
                self::$pdo=$pdo;

                return $pdo;
            }catch(\PDOException $e){
               throw new \Exception($e->getMessage());
            }
        }else{
            return self::$pdo;
        }
    }

    /**
     * 执行SQL语句的方法
     * @param	string	$sql		用户查询的SQL语句
     * @param	string	$method		SQL语句的类型（select,find,total,insert,update,other）
     * @param	array	$data		为prepare方法中的?参数绑定值
     * @return	mixed			根据不同的SQL语句返回值
     */
    function query($sql, $method,$data=array()){


        $this->setNull(); //初使化sql

        $value=$this->escape_string_array($data);

        $marr=explode("::", $method);
        $method=strtolower(array_pop($marr));
        if(strtolower($method)==trim("total")){
            $sql=preg_replace('/select.*?from/i','SELECT count(*) as count FROM',$sql);
        }

        try{
            if(Conf::get('config','debug')==true){
                $this->sql_log = $this->sql($sql,$value);
                $this->sql_log();
            }
            $pdo=self::connect();
            $stmt=$pdo->prepare($sql);  //准备好一个语句
            $result=$stmt->execute($value);   //执行一个准备好的语句

            switch($method){
                case "select":  //查所有满足条件的
                    $data=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                    return $data;
                    break;
                case "find":    //只要一条记录的
                    $data=$stmt->fetch(\PDO::FETCH_ASSOC);

                    return $data;
                    break;
                case "total":  //返回总记录数
                    $row=$stmt->fetch(\PDO::FETCH_NUM);


                    return $row[0];
                    break;
                case "insert":  //插入数据 返回最后插入的ID
                return $pdo->lastInsertId();
                    break;
                case "delete":
                case "update":        //update

                    return $stmt->rowCount();
                    break;
                default:
                    return $result;
            }

        }catch(\PDOException $e){
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * 自动获取表结构
     */
    function setTable($tabName){
        //$cachefile=PROJECT_PATH."runtime/data/".$tabName.".php";
        $this->tabName=$tabName; //加前缀的表名


            try{
                $pdo=self::connect();
                $stmt=$pdo->prepare("desc {$this->tabName}");
                $stmt->execute();
                $fields=array();
                while($row=$stmt->fetch(\PDO::FETCH_ASSOC)){
                    if($row["Key"]=="PRI"){
                        $fields["pri"]=strtolower($row["Field"]);
                    }else{
                        $fields[]=strtolower($row["Field"]);
                    }
                }
                //如果表中没有主键，则将第一列当作主键
                if(!array_key_exists("pri", $fields)){
                    $fields["pri"]=array_shift($fields);
                }


                $this->fieldList=$fields;
            }catch(\PDOException $e){
              throw new \PDOException($e->getMessage());
            }
        }
    /**
     * 事务开始
     */
    public function beginTransaction() {
        $pdo=self::connect();
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $pdo->beginTransaction();
    }

    /**
     * 事务提交
     */
    public function commit() {
        $pdo=self::connect();
        $pdo->commit();
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
    }

    /**
     * 事务回滚
     */
    public function rollBack() {
        $pdo=self::connect();
        $pdo->rollBack();
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
    }
    /*
     * 获取数据库使用大小
     * @return	string		返回转换后单位的尺寸
     */
    public function dbSize() {
        $sql = "SHOW TABLE STATUS FROM " . DBNAME;
        if(defined("TABPREFIX")) {
            $sql .= " LIKE '".TABPREFIX."%'";
        }
        $pdo=self::connect();
        $stmt=$pdo->prepare($sql);  //准备好一个语句
        $stmt->execute();   //执行一个准备好的语句
        $size = 0;
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            $size += $row["Data_length"] + $row["Index_length"];
        return tosize($size);
    }
    /*
     * 数据库的版本
     * @return	string		返回数据库系统的版本
     */
    function dbVersion() {
        $pdo=self::connect();
        return $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    public function sql_log(){
        $log = LogFactory::factory();
        $log->log($this->sql_log,'sql');
    }
    public function fetchall($tabName,$field='*',$arr='',$sort = '', $limit = ''){
        $condition = $arr;
        if(is_array($arr)){
            $where = array();
            foreach ($arr as $k=>$v){
                $where[]= $k.'='.$v;
            }
            $condition = ' where '.join(' and ',$where);
        }
        $sort && $sort = ' ORDER BY '.$sort;
        $limit && $limit = ' LIMIT '.$limit;
        $sql  = 'select '.$field.' from '.$this->table($tabName).' '.$condition.$sort.$limit;
        return $this->query($sql,'select');

    }
    public function fetch($table, $field, $condition = '', $sort = ''){
        $where = $condition;
        if(is_array($condition)){
            foreach ($condition as $key => $value) {
                $join[] = $key.' = \''.$value.'\'';
            }
            $where = ' where '.join(' and',$join);
        }

        $sort && $sort = ' ORDER BY '.$sort;
        $sql  = 'select '.$field.' from '.$this->table($table).$where.$sort;
        return $this->query($sql,'find');
    }
    function lastinsertid(){
            self::$pdo->lastInsertId();
    }

}