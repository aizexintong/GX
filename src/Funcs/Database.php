<?php

require_once __GX_AUTOLOAD_DIR__;

use Aura\Sql\ExtendedPdo;

function SQL()
{
    if (!file_exists(__GX_CONFIG_DIR__)) {
        header("Location: ./install.php");
        exit;
    }
    $config = parse_ini_file(__GX_CONFIG_DIR__, true);
    if (isset($config["Database"]["type"])) {
        $type = $config["Database"]["type"];
        if ($type === "Sqlite3") {
            // 连接到 SQLite 数据库
            return new ExtendedPdo("sqlite:" . __GX_DATA_DIR__ . "/" . $config["Database"]["dbname"]);
        } elseif ($type === "Mysql") {
            // 连接到 MySQL 数据库
            return new ExtendedPdo(
                'mysql:host=' . $config["Database"]["host"] . ';dbname=' . $config["Database"]["dbname"],
                $config["Database"]["username"],
                $config["Database"]["password"]
            );
        } else {
            header("Location: ./install.php");
        }
    } else {
        echo "未指定数据库类型";
    }
    return 0;
}
