<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 16:26
 */
include_once "/config.php";
class table{
    protected $_link;
    function __construct($config){
        $this->_link = mysql_connect($config['db_host'],$config['db_username'],$config['db_password']);//mysqli_connect($config['db_host'], $config['db_name'],$config['db_username'],$config['db_password']);
        mysql_query("set names 'utf8'",$this->_link);
        mysql_select_db($config['db_name'],$this->_link);
    }

    function insert($table,$data){
        if(empty($table) || empty($data)){
            return false;
        }

        $keys   = "`".join("`,`",array_keys($data))."`";
        $vals   = "'" . join("','",$data ) . "'";
        $SQL    = "INSERT INTO ". $table ."({$keys}) VALUES({$vals})";
//        file_put_contents("./file.txt", "sql_".date("Y-m-d H:i:s").print_r($SQL, TRUE), FILE_APPEND);
        mysql_query($SQL,$this->_link);
        $insert_id = mysql_insert_id($this->_link);
        return $insert_id;
    }

    function update($table,$data,$where){
        if(empty($table) || empty($data) || empty($where)){
            return false;
        }
        $char = '';
        foreach($data as $key =>$val){
            if(empty($char)){
                $char   .= "`" . $key . "`='" . $val . "'";
            }else{
                $char   .= ",`" .$key . "`='" . $val . "'";
            }
        }
        $SQL        = "UPDATE ".$table . " SET " . $char ." WHERE " . $where;
        $result     = mysql_query($SQL,$this->_link);
        return $result;
    }

    function delete($table,$where){
        if(empty($table) || empty($where)){
            return false;
        }

        $SQL     = "DELETE {$table} WHERE {$where}";
        $result  = mysql_query($SQL,$this->_link);
        return $result;
    }

    function getList($table,$field='*',$where='',$order='',$limit=''){
        if(empty($table)){
            return false;
        }
        $where  = empty($where)  ? ''   : ' AND '.$where;
        $order  = empty($order)  ? ''   : ' ORDER BY ' . $order;
        $limit  = empty($limit)  ? ''   :  $limit;
        $SQL    = "SELECT ".$field . " FROM " . $table ." WHERE 1=1 " . $where . $order . $limit;
        $query  = mysql_query($SQL,$this->_link);

        $rows   = array();
        while($row  = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }

        return $rows;

    }

    //查询总数
    function counts($table,$where=''){
        if(empty($table)){
            return false;
        }
        $where  = empty($where)  ? ''   : ' AND '.$where;
        $SQL    = "SELECT count(*) as num FROM " . $table ." WHERE 1=1 " . $where;
        $query  = mysql_query($SQL,$this->_link);

        $result = mysql_fetch_assoc($query);
        return $result['num'];

    }

    //分页
    function pageLimit($counts,$pagesize=10,$pageNo=1){
        if($counts == false){
            return false;
        }
        $countPage = ceil($counts / $pagesize);
        if($pageNo < 1){
            $pageNo = 1;
        }

        if($pageNo > $countPage){
            $pageNo = $countPage;
        }

        $start = ($pageNo - 1) * $pagesize;
        $sql = " LIMIT {$start},{$pagesize}";

        return $sql;

    }

    function getGroup($table,$where,$field='*'){
        if(empty($table) || empty($where)){
            return false;
        }

        $SQL    = "SELECT ".$field . " FROM " . $table ." WHERE 1=1 GROUP BY " . $where;
        $query  = mysql_query($SQL,$this->_link);
        $rows   = array();
        while($row  = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }

        return $rows;
    }
}