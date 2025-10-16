<?php
require_once '../Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
} catch (Exception $e) {
    $_SESSION['status_message'] = "システムエラーが発生しました。";
    header('Location: /php/main.php');
    exit;
}

class adminSearch
{
    private $pdo;
    private $errors = [];

    // コンストラクタ
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ユーザー一覧を取得
    public function searchUsers($data)
    {
        try {
            $code = $data['code'];
            $name = $data['name'];
            $mail = $data['mail'];
            $tel = $data['tel'];

            // 3. SQLの準備: 全件を取得するクエリ
            $sql = "
                SELECT 
                    user_cd,
                    user_nm,
                    user_mail,
                    user_tel
                FROM mst_user
                WHERE 1 = 1    
            ";

            $params = [];

            if (!empty($code)){
                $sql .= "AND user_cd = ?";
                $params[] = $code;
            }
            if (!empty($name)) {
                $sql .= "AND user_nm LIKE ?";
                $params[] = '%' . $name . '%';
            }
            if (!empty($mail)) {
                $sql .= "AND user_mail = ?";
                $params[] = $mail;
            }
            if (!empty($tel)) {
                $sql .= "AND user_tel = ?";
                $params[] = $tel;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            // 5. 結果セット全体を配列として取得 (全件表示)
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $users;

        } catch (Exception $e) {
            $_SESSION['status_message'] = "システムエラーが発生しました。";
            header('Location: /php/main.php');
            exit;
        }
    }
}