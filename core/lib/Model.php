<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/2/25
 * Time: 14:40
 */

namespace core\lib;

use core\db\sqlFactory;
use core\lib;
use Medoo\Medoo;
use PDO;

class Model
{
    private $db;
    public function __construct()
    {
        $conf = Conf::all('db');
       // dump($conf);
        $config = array(
            'database_type' => $conf['db_type'],
            'database_name' => $conf['database'],
            'server' => $conf['host'],
            'username' => $conf['username'],
            'password' => $conf['pwd'],
        );
        if (!isset($this->db)){
            $this->db = new Medoo($config);
        }
       // dump($this->db);
    }

    public function query($sql){
        $res = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
       return $res;
    }
    public function select($table, $join, $columns = null, $where = null){
        return $this->db->select($table, $join, $columns , $where = null);
    }
    public function insert($table, $data){
        return $this->db->insert($table,$data);
    }
    public function update($table, $data, $where){
        return $this->db->update($table,$data);
    }
    public function delete($table, $where){
        return $this->db->delete($table,$where);
    }

    public function get($table, $join, $columns, $where){
        return $this->db->get($table, $join, $columns, $where);
    }
    public function has($table, $where){
        return $this->db->has($table, $where);
    }
    public function count($table, $where){
        return $this->db->count($table, $where);
    }
    public function max($table, $join, $column, $where){
        return $this->db->max($table, $join, $column, $where);
    }
}