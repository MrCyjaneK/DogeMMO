<?php
include getcwd() . '/inc/config.php';
date_default_timezone_set('Europe/Warsaw');
$info = "";
$today = new DateTime('now');
$tomorrow = new DateTime('tomorrow');
$difference = $today->diff($tomorrow);
$values = array(
    'hour' => $difference->format('%h'),
    'minute' => $difference->format('%i'),
    'second' => $difference->format('%s')
);
$left = "";
foreach ($values as $word => $val) {
    if ($val) {
        $left .=
            sprintf(ngettext('%d ' . $word, '%d ' . $word . 's', $val), $val) .
            ' ';
    }
}
$info .= $left;
echo str_replace("\n", "<br />", $info);
?>
