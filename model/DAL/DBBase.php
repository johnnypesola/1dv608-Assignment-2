<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-17
 * Time: 15:56
 */

namespace model;


abstract class DBBase {

// Init variables
    private static $DB_TABLE_REGEX = '/[^a-z_\-0-9]/i';

    private static $DB_SETTINGS = [
        "type" => "mysql",
        "name" => "1DV608",
        "username" => "1dv608",
        "password" => "fHW5Sp8wzVySsJ5a",
        "charset" => "utf8",
        "host" => "localhost"
    ];

    protected static $db;
    private $dbTableName = "";

// Constructor
    public function __construct() {

        // Setup pdo
        $this->SetupDB();
    }

// Getters and Setters

/*
    protected function SetDBTableName($tableName) {

        // Check if table name is alphanumeric
        if(preg_match(self::$DB_TABLE_REGEX, $tableName)) {
            throw new \Exception("Database table name must be alphanumeric.");
        } else {
            $this->dbTableName = $tableName;
        }
    }

    protected function getDBTableName() {
        return $this->dbTableName;
    }
*/

// Private methods
    private static function SetupDB() {

        // setup DSN string
        $dsn = self::$DB_SETTINGS['type'] . ':dbname=' . self::$DB_SETTINGS['name'] . ';host=' . self::$DB_SETTINGS['host'];

        // Connect to an ODBC database using driver invocation
        self::$db = new \PDO(
            $dsn,
            self::$DB_SETTINGS['username'],
            self::$DB_SETTINGS['password']
        );

        //TODO: Remember to catch PDOException
    }

}

