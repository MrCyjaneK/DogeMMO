<?php
$token = '690195901'; // For 'historical' reasons
try {
$db = NEW PDO("sqlite:".getcwd()."/forDevs/db.dev.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
//$db->query("SET NAMES 'utf8mb4'");
// Exist in production, cause error in sqlite.
} catch (Exception $e) {
    print_r($e);
}
// Seriously, I don't know why is this function here.. but it's in db.production.php so DON'T remove it.
function n($n) {
  return number_format($n, 8, '.', '');
}
// TEST:
//$q = $db->prepare('SELECT * FROM `users`');
//$q->execute();
//print_r($q->fetchAll());
?>