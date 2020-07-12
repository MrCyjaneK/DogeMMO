<?php
die("This is not supported in v3. Please submit PR if you will make it working");
chdir("forDevs");
echo "[INFO] Creating config file\n";
if (file_exists("../config.json")) {
    echo "[NOTICE] File exist (But who cares? I'll write it anyway)\n";
}
$config = [
    "version" => "development"
];
file_put_contents("../config.json",json_encode($config,JSON_PRETTY_PRINT));
echo "[NOTE] In case of db problems: 'apt install php-sqlite3'\n";
//echo "[NOTE] Now you can start web server by using: 'php -S 127.0.0.1:8080'\n";
if (!file_exists("phpDocumentor.phar")) {
    echo "[INFO] Downloading phpDocumentor.phar\n";
    copy("http://phpdoc.org/phpDocumentor.phar","phpDocumentor.phar");
    echo "[INFO] OK!\n";
}
echo "[INFO] Updating docs\n";
chdir("..");
shell_exec('rm -rf "docs"; php forDevs/phpDocumentor.phar -t docs -d ./inc --cache-folder="cache" > /dev/null; rm -rf cache');
echo "[NOTE] Starting docs server at 'localhost:42069'\n";
shell_exec('killall screen 2>&1  > /dev/null');
chdir("docs");
shell_exec('screen -dm php -S localhost:42069');
echo "[NOTE] Game server started at 'localhost:8080'\n";
chdir("..");
shell_exec('screen -dm php -S localhost:8080');
