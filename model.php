<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 16:26
 */
//include_once "/config.php";
class table{
    protected $_link;
    function __construct(){
        $this->_link = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD);
        mysql_query("set names 'utf8'",$this->_link);
        mysql_select_db(DB_NAME,$this->_link);
    }

    function insert($table,$data){
        if(empty($table) || empty($data)){
            return false;
        }

        $keys   = join(",",array_keys($data));
        $vals   = "'" . join("','",$data ) . "'";
        $SQL    = "INSERT INTO ". $table ."({$keys}) VALUES({$vals})";
//        file_put_contents("./file.txt", "sql_".date("Y-m-d H:i:s").print_r($SQL, TRUE), FILE_APPEND);
        mysql_query($SQL,$this->_link);
        $insert_id = mysql_insert_id($this->_link);
        return $insert_id;
    }

    function update($table,$data,$where){
        if(empty($table) || empty($data)){
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
        $limit  = empty($limit)  ? ''   : ' LIMIT ' . $limit;
        $SQL    = "SELECT ".$field . " FROM " . $table ." WHERE 1=1 " . $where . $order . $limit;
        $query  = mysql_query($SQL,$this->_link);
        $rows   = mysql_fetch_assoc($query);
        /*while($row = mysql_fetch_assoc($query){
            $rows[] = $row;
        }*/
        return $rows;

    }
}