<?php
$token = '690195901';
$db = NEW PDO("mysql:host=localhost;dbname=dogemmo", "dogemmo", "");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query("SET NAMES 'utf8mb4'");
function n($n) {
  return number_format($n, 8, '.', '');
}
?>
