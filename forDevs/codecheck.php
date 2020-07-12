<?php
$errors = [];
//echo "[INFO] Check for syntax errors.\n";
include "forDevs/_codecheck/syntaxCheck.php";
//echo "[INFO] Check for bad code.\n";
include "forDevs/_codecheck/badCode.php";

echo "\n=============\n";
foreach ($errors as $number => $error) {
    echo "#".$number.". ".$error."\n";
}
if ($errors == []) {
    echo "OK\n";
}
?>
