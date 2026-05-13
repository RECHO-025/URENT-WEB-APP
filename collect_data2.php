<?php 
 
# require 'session.php';
// Database connection instance 
$servername = "localhost";
$username = "eljotellug_jotell";
$password = "0702014626@Bjk";
$dbname = "eljotellug_jotell";

$landlordid = $_SESSION['login_landlordid']; 

$conn12 = new collect_data($servername, $username, $password, $dbname, $landlordid);
	
$conn2 = $conn12->connect_database($servername,$username,$password,$dbname);


?>
