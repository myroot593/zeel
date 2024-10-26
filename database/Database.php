<?php
namespace database;

use PDO;
use PDOException;

class Database
{
    protected $pdo;

    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'bukuinduk';
        $username = 'root';
        $password = '';

        try {
            $dsn = "mysql:host=$host;dbname=$dbname";
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
    public function secretkey()
    {
        return [
            'encryption_key' => 'gT9&jH7$zK2@xL8#',
        ];
    }
}
