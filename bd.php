<?php

$server = "localhost"; 
$bd= "app"; 
$user="root";
$password="";

try{
    $conexion = new PDO("mysql:host=$server;dbname=$bd", $user, $password);
}catch(Exception $ex){
    echo $ex->getMessage();
}

?>