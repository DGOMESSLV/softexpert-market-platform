<?php

namespace Src\Libs\Database;

use \PDO;

/**
 * Deal and handle wit PostgreSQL database connection in app.
 * 
 * @author Diego Gomes <dgs190plc@outlook.com>
 * @since 08/27/2023
 */
class DB
{
    /**
     * Stores PDO connection object.
     * 
     * @property \PDO
     */
    protected static PDO $connection;

    /**
     * Static methods constructors.
     * 
     * @return void
     */
    protected static function __staticConstruct(): void
    {        
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $database = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $extraConfig = [];

        if ($_ENV['APP_ENV'] === 'local') {
            $extraConfig[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }

        self::$connection = new PDO("pgsql:host={$host};port={$port};dbname={$database}", $user, $password, $extraConfig);
    }

    /**
     * Run select queries, on the passed mode.
     * 
     * @param string $mode ('many' or 'single') 
     * @param string $query
     * @param array $binds = []
     * 
     * @return object|array<int, object>|null
     */
    public static function select(string $mode, string $query, array $binds = []): object|array|null
    {
        self::__staticConstruct();

        $stmt = self::$connection->prepare($query);
        $stmt->execute($binds);

        $data = $mode === 'many' ? $stmt->fetchAll(PDO::FETCH_OBJ) : $stmt->fetch(PDO::FETCH_OBJ);

        return $data !== false ? $data : null;
    }

    /**
     * Run select query on single mode.
     * 
     * @param string $query
     * @param array $binds = []
     * 
     * @return object|null
     */
    public static function find(string $query, array $binds = []): object|null
    {
        return self::select('single', $query, $binds);
    }

    /**
     * Run select query on many mode.
     * 
     * @param string $query
     * @param array $binds = []
     * 
     * @return object|null
     */
    public static function findMany(string $query, array $binds = []): array
    {
        return self::select('many', $query, $binds);
    }

    /**
     * Run queries that changes the database, such as INSERT, DELETE, UPDATE, etc.
     * 
     * @param string $query
     * @param array $binds = []
     * 
     * @return mixed
     */
    public static function execute(string $query, array $binds = []): mixed
    {
        self::__staticConstruct();

        $stmt = self::$connection->prepare($query);
        $stmt->execute($binds);

        if (strpos(strtolower($query), 'insert') !== false) {
            return self::$connection->lastInsertId();
        }
    }
}