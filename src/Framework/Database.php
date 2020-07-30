<?php

namespace Framework;

class Database
{
    private static $connection;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private static function load(string $file): array
    {
        $file = __DIR__ . '/../../config/' . $file . '.ini';
        if (file_exists($file)) {
            $data = parse_ini_file($file);
        } else {
            throw new \Exception('Error: database configuration file not found');
        }
        return $data;
    }

    private static function make(array $data): \PDO
    {
        $driver = isset($data['driver']) ? $data['driver'] : NULL;
        $user   = isset($data['username']) ? $data['username'] : NULL;
        $passwd = isset($data['passwd']) ? $data['passwd'] : NULL;
        $dbname = isset($data['dbname']) ? $data['dbname'] : NULL;
        $server = isset($data['server']) ? $data['server'] : NULL;
        $port   = isset($data['port']) ? $data['port'] : NULL;

        if (!is_null($driver)) {
            switch (strtoupper($driver)) {
                case 'MYSQL':
                    $port = isset($port) ? $port : 3306;
                    return new \PDO("mysql:host={$server};port={$port};dbname={$dbname}", $user, $passwd);
                    break;
                case 'MSSQL':
                    $port = isset($port) ? $port : 1433;
                    return new \PDO("mssql:host={$server},{$port};dbname={$dbname}", $user, $passwd);
                    break;
                case 'PGSQL':
                    $port = isset($port) ? $port : 5432;
                    return new \PDO("pgsql:dbname={$dbname}; user={$user}; password={$passwd}, host={$server};port={$port}");
                    break;
                case 'SQLITE':
                    return new \PDO("sqlite:{$dbname}");
                    break;
                case 'OCI8':
                    return new \PDO("oci:dbname={$dbname}", $user, $passwd);
                    break;
                case 'FIREBIRD':
                    return new \PDO("firebird:dbname={$dbname}", $user, $passwd);
                    break;
            }
        } else {
            throw new \Exception('Error: date dbname type not reported');
        }
    }

    public static function getInstance(string $file): \PDO
    {
        if (self::$connection == NULL) {
            // Receber os data do file
            self::$connection = self::make(self::load($file));
            self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$connection->exec("set names utf8");
        }
        return self::$connection;
    }

    public static function beginTransaction(): bool
    {
        return self::$connection->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::$connection->commit();
    }

    public static function rollback(): bool
    {
        return self::$connection->rollback();
    }
}
