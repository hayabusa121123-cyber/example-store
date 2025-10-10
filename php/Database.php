<?php
class Database
{
    // 接続情報をプライベートプロパティとして保持
    private $host = 'localhost';
    private $db   = 'example_store';
    private $user = 'user123';
    private $pass = 'password';
    private $charset = 'utf8mb4';

    // 接続インスタンスを保持する静的プロパティ
    private static $instance = null;

    // PDOオブジェクトを保持するプロパティ
    private $pdo;

    // 外部からのインスタンス化を禁止
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        ];

        try {
            // 接続処理
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            // 接続失敗時は例外を投げる
            error_log("Database connection failed: " . $e->getMessage());
            die("Database connection error.");
        }
    }

    // 接続インスタンスを取得する唯一のメソッド
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // PDOオブジェクト（接続そのもの）を返すメソッド
    public function getConnection()
    {
        return $this->pdo;
    }
}
