<?php
chdir("forDevs");
echo "[INFO] Creating config file\n";
if (file_exists("../config.json")) {
    echo "[NOTICE] config.json file exist!\n";
} else {
    $config = [
        "version" => "development"
    ];
    file_put_contents("../config.json",json_encode($config,JSON_PRETTY_PRINT));
    echo "Created config for development (is db working?) You may need to install 'php-sqlite3'\n"
}
echo "[NOTE] Make sure to check inc/config.php\n"
echo "[NOTE] To start game server use 'php -S localhost:8080'\n";
