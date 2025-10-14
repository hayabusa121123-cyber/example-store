<?php
require_once '../Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
} catch (Exception $e) {
    $_SESSION['status_message'] = "システムエラーが発生しました。";
    header('Location: main.php');
    exit;
}

class Validator
{
    private $pdo;
    private $errors = [];

    // コンストラクタ
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ユーザー名
    private static function validateRegistUserName($name)
    {
        $errors = '';

        if (empty($name)) {
            $errors = '名前は必須です。';
        } elseif (strlen($name) > 10) {
            $errors = '10文字以内で入力してください。';
        }

        return $errors;
    }

    // メールアドレス
    private function validateRegistUserMail($mail)
    {
        $errors = '';

        if (empty($mail)) {
            $errors = 'メールアドレスは必須です。';
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errors = "正しいメールアドレス形式を入力してください。";
        } else {
            try {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mst_user WHERE user_mail = :mail");
                $stmt->bindParam(':mail', $mail);
                $stmt->execute();

                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    $errors = 'このメールアドレスはすでに登録されています。';
                }
            } catch (Exception $e) {
                $_SESSION['status_message'] = "システムエラーが発生しました。";
                header('Location: main.php');
                exit;
            }
        }
        return $errors;
    }

    // 電話番号
    private static function validateRegistUserTel($tel)
    {
        $errors = '';

        if (empty($tel)) {
            return;
        } elseif (!is_numeric($tel)) {
            $errors = '正しい形式で入力してください。';
        } elseif (10 > strlen($tel) || strlen($tel) > 11) {
            $errors = '正しい桁数で入力してください。';
        }

        return $errors;
    }

    // ユーザー更新用のバリデーション
    public function validateUpdateUser($data)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT user_nm, user_mail, user_tel FROM mst_user WHERE user_cd = :cd");
            $stmt->execute([':cd' => $_SESSION['user_cd']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $error = [];

            $name = $data['name'];
            $mail = $data['mail'];
            $tel = $data['tel'];

            // 共通のチェック関数を呼び出す
            if ($name !== $user['user_nm']) {
                $error['name'] = self::validateRegistUserName($name);
            }
            if ($mail !== $user['user_mail']) {
                $error['mail'] = $this->validateRegistUserMail($mail);
            }
            if ($tel !== $user['user_tel']) {
                $error['tel'] = self::validateRegistUserTel($tel);
            }

            $errors = array_filter($error);

            return $errors;
        } catch (Exception $e) {
            $_SESSION['status_message'] = "システムエラーが発生しました。";
            header('Location: main.php');
            exit;
        }
    }
}
