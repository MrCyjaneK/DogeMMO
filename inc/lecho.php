<?php
function lecho($toecho, $what = '????', $level = 0) {
    for ($i = 0; $i < $level; $i++) {
        $toecho = '    '.$toecho;
    }
    echo "[$what]$toecho\n";
}
?>
