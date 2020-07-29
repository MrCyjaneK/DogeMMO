<?php
$host = 'localhost';
$user = 'dogemmo';
$pass = '1234567';
$name = 'dogemmo';
if (defined('MAKE_MYSQLI_PLEASE')) {
    $db = new mysqli($host,$user,$pass,$name);
} else {
    $db = NEW PDO("mysql:host=$host;dbname=$name", "$user", "$pass");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->query("SET NAMES 'utf8mb4'");
}
unset($host,$user,$pass,$name);
?>
