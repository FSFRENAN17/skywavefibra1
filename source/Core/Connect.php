<?php

namespace Source\Core;

/**
 * Class Connect [ Singleton Pattern ]
 *
 * @package Source\Core
 */
class Connect
{
    /** @const array */
    private const OPTIONS = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_general_ci",
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL
    ];

    /** @var \PDO */
    private static $instance;

    /**
     * @return \PDO
     */
    public static function getInstance(): ?\PDO
    {
        if (empty(self::$instance)) {
            try {
                if (str_contains($_SERVER['HTTP_HOST'], "localhost")) {
                    $db = (object)[
                        "host" => CONF_DB_TEST["host"],
                        "user" => CONF_DB_TEST["user"],
                        "pass" => CONF_DB_TEST["pass"],
                        "database" => CONF_DB_TEST["database"]
                    ];
                } else {
                    $db = (object)[
                        "host" => CONF_DB_LIVE["host"],
                        "user" => CONF_DB_LIVE["user"],
                        "pass" => CONF_DB_LIVE["pass"],
                        "database" => CONF_DB_LIVE["database"]
                    ];
                }
                self::$instance = new \PDO(
                    "mysql:host=" . $db->host . ";dbname=" . $db->database,
                    $db->user,
                    $db->pass,
                    self::OPTIONS
                );
            } catch (\PDOException $exception) {
                redirect("/ops/problemas");
            }
        }

        return self::$instance;
    }

    /**
     * Connect constructor.
     */
    private function __construct() {}

    /**
     * Connect clone.
     */
    private function __clone() {}
}
