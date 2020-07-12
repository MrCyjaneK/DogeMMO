<?php
$t = [];
function gs($text, $language = 'undefined') {
    global $t;
    if ($language == 'undefined') {
        global $user;
        if (isset($user->langcode)) {
            $language = $user->langcode;
        } else {
            $language = 'en';
        }
    }
    $language = strtolower($language);
    if (!isset($t[$language])) {
        if (file_exists(getcwd() . "/translation/$language.json")) {
            $t[$language] = json_decode(file_get_contents(getcwd() . "/translation/$language.json"), true);
        } else {
            if ($language != 'en') return gs($text,'en');
            return $text;
        }
    }
    $key = hash('sha1', $text);
    if (isset($t[$language][$key])) return $t[$language][$key];
    $language = 'en';
    $t[$language][$key] = $text;
    //var_dump($text);
    //die();
    file_put_contents(getcwd() . "/translation/$language.json", json_encode($t[$language], JSON_PRETTY_PRINT));
    return gs($text, $language);
}

// Don't set $try in requests
function getString($textname, $language = "EN", $try = 0, $default = '++++ THIS STRING IS NOT TRANSLATED, <a href="/game.php?action=translate">CONTRIBUTE</a> ++++') {
    return gs($textname, $language);
    //$link = "/game.php?action=translate&textname=".urlencode($textname)."&language=".urlencode($language);
    //$default_text = '++++ THIS STRING IS NOT TRANSLATED, <a href="/game.php?action=translate">CONTRIBUTE</a> ++++ '.$default;
    //$textname = strtoupper($textname);
    //$language = strtoupper($language);
    //global $db;
    //if (!isset($db)) {
    //    include 'inc/db.php';
    //}
    //$query = $db->prepare("SELECT * FROM `translation` WHERE `textname`=:textname AND language=:language");
    //$query->bindParam(":textname",$textname);
    //$query->bindParam(":language",$language);
    //$query->execute();
    //$query = $query->fetchAll();
    //if ($query == []) {
    //    // Eh... No translation found, insert default and beg for contribution
    //    $insert = $db->prepare("INSERT INTO `translation`(`textname`, `language`, `text`) VALUES (:textname, :language, :text)");
    //    $insert->bindParam(":textname",$textname);
    //    $insert->bindParam(":language",$language);
    //    $insert->bindParam(":text",$default_text);
    //    $insert->execute();
    //    // ATTENTION, RECURSION
    //    if ($try > 5) {
    //        return "Critical non-fatal error occured in getString translation module";
    //    }
    //    return getString($textname, $language, $try+1);
    //}
    //$tl = $query[0]['text'];
    //if (substr($tl,0,13) == substr($default_text,0,13)) {
    //        return $tl." <a href=\"".$link."\">✏</a>";
    //}
    //return $tl;
}
