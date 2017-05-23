<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 16:26
 */
require_once "./config.php";
class table{
    function __construct(){
        mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD);
        mysql_query("set names 'utf8'");
        mysql_select_db(DB_NAME);
    }

    function insert($table,$data){
        if(empty($table) || empty($data)){
            return false;
        }

        $keys   = "'" . join("','",array_keys($data)) . "'";
        $vals   = "'" . join("','",$data ) . "'";
        $SQL    = "INSERT INTO ". $table ."({$keys}) VALUES({$vals})";
        $result = mysql_query($SQL);
        return $result;
    }

    function update($table,$data,$where){
        if(empty($table) || empty($data)){
            return false;
        }
        $char = '';
        foreach($data as $key =>$val){
            if(empty($char)){
                $char   .= "'" . $key . "'='" . $val . "'";
            }else{
                $char   .= ",'" .$key . "'='" . $val . "'";
            }
        }
        $SQL = "UPDATE ".$table . " SET " . $char ." WHERE " . $where;
        $result  = mysql_query($SQL);
        return $result;
    }

    function delete($table,$where){
        if(empty($table) || empty($data)){
            return false;
        }

        $SQL     = "DELETE {$table} WHERE {$where}";
        $result  = mysql_query($SQL);
        return $result;
    }
}