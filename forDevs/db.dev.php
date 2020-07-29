<?php
$token = '690195901'; // For 'historical' reasons
try {
    if (!file_exists(getcwd()."/forDevs/db.dev.db")) {
        $db = NEW PDO("sqlite:".getcwd()."/forDevs/db.dev.db");
        foreach (explode(";\n",file_get_contents(getcwd()."/forDevs/db.scheme.sql")) as $q) {
            var_dump($q);
            var_dump($db->query($q));
        }
    }
    $db = NEW PDO("sqlite:".getcwd()."/forDevs/db.dev.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$db->query("SET NAMES 'utf8mb4'");
    // Exist in production, cause error in sqlite.
} catch (Exception $e) {
    print_r($e);
    die('fatal error in db.dev.php');
}
// TEST:
//$q = $db->prepare('SELECT * FROM `users`');
//$q->execute();
//print_r($q->fetchAll());
?>
