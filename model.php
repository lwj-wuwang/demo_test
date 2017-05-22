<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 16:26
 */

class table{
    function __construct($host,$username,$password,$database){
        mysqli_connect($host,$username,$password,$database);
    }

    function insert($table,$data){
        if(empty($table) || empty($data)){
            return false;
        }
        foreach($data as $key=>$val){

        }
        $SQL = "INSERT INTO ". $table ;
    }
}