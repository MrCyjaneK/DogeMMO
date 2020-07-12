<?php
$blacklist = [
    [
        "code" => "/PHP_EOL/",
        "info" => "'PHP_EOL' Found in code, it's better to use \\n",
        "type" => "WARNING"
    ],
    [
        "code" => "/getString/",
        "info" => "'getString' found in code! Use gs instead."
    ],
    [
        "code" => "/shell_exec/",
        "info" => "'shell_exec' Found in code, and it shouldn't be there.",
        "type" => "ERROR"
    ],
    [
        "code" => "/new user\(.+,1\);/i",
        "info" => "'user(..., 1)' Found in code, and it shouldn't be there, use version 3 of auth.",
        "type" => "ERROR"
    ],
    [
        "code" => "/new user\(.+,2\);/i",
        "info" => "'user(..., 2)' Found in code, and it shouldn't be there, use version 3 of auth.",
        "type" => "WARNING"
    ],

    [
        "code" => "/eval/",
        "info" => "'eval' Found in code, and it shouldn't be there.",
        "type" => "ERROR"
    ],
    [
        "code" => "/base64_decode\(.+\)/",
        "info" => "'base64_decode found in code' Found in code, and it shouldn't be there.",
        "type" => "ERROR"
    ],
    [
        "code" => "/shell_exec/",
        "info" => "'shell_exec' Found in code, and it shouldn't be there.",
        "type" => "ERROR"
    ],
];
$skip = [
    "./forDevs/_codecheck/badCode.php",
    "./cloc.php",
    "./forDevs/db.dev.php",
    "./inc/db.production.php",
    "./inc/db.dev.php",
    "./index.php"
];
function checkcode($code, $name) {
    global $blacklist;
    global $errors;
    foreach ($blacklist as $incorrect) {
        $match = preg_match($incorrect['code'], $code);
        if ($match) {
            switch ($incorrect['type']) {
                case 'ERROR':
                    $errors[] = "Error in file: '$name':\n    > ".$incorrect['info'];
                    break;
                case 'WARNING':
                    echo  "Warning in file: '$name': \n    > ".$incorrect['info']."\n";
                    break;
            }
        }
    }
}

$it = new RecursiveDirectoryIterator(".");

// Loop through files
foreach(new RecursiveIteratorIterator($it) as $file) {
    if ($file->getExtension() == 'php') {
        if (in_array($file, $skip)) continue;
        checkcode(file_get_contents($file),$file);
    }
}
