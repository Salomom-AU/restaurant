<?php 

$user = "root";
$password = "";
$server = "localhost";
$db = "restaurant";
$connect = new mysqli($server, $user, $password, $db);
if($connect->connect_error){
    die("Connection failed: " . $connect->connect_error);
}

?>