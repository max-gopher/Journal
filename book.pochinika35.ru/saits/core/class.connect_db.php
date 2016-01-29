<?php
class connect_db {
    $host="localhost";
    $user_db="oleg";
    $pass_db="10112012";
    $name_db="newboozilla";
    $login = $_SESSION['login'];
    $connect_db = new mysqli("$host", "$user_db", "$pass_db", "$name_db");
    if ($connect_db->connect_errno){
	   echo 'Не удалось подключиться к базе.';
	   exit();
    }
    $connect_db->query("SET NAMES UTF8");
}
?>