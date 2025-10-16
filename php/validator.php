<?php
require_once '../Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
} catch (Exception $e) {
    $_SESSION['status_message'] = "システムエラーが発生しました。";
    header('Location: /php/main.php');
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
        } elseif (mb_strlen($name, 'UTF-8') > 10) {
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
                header('Location: /php/main.php');
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
        } elseif (!preg_match('/^.{10,11}$/', $tel)) {
            $errors = '正しい桁数で入力してください。';
        }

        return $errors;
    }

    // パスワード
    private static function validateUserPassword($password)
    {
        $errors = '';

        if (empty($password)) {
            $errors = 'パスワードを入力してください。';
        } elseif (!preg_match('/^.{8,20}$/', $password)) {
            $errors = 'パスワードは8文字以上20文字以内で設定してください。';
        } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/', $password)) {
            $errors = '正しい形式で入力してください。';
        }

        return $errors;
    }

    // パスワード(登録用)
    private static function validateRegistUserPassword($password, $password_check)
    {
        $errors = '';

        $error_password = self::validateUserPassword($password);

        if (isset($error_password)) {
            $errors = $error_password;
        } elseif ($password !== $password_check) {
            $errors = '再入力パスワードが一致しません。';
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
            header('Location: /php/main.php');
            exit;
        }
    }

    // パスワード更新用のバリデーション
    public function validateUpdateUserPassword($data)
    {
        try {
            $sql = "
                SELECT 
                    CONCAT('', user_pwd) AS user_pwd
                FROM
                    mst_user
                WHERE user_cd = ?
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$_SESSION['user_cd']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $error = [];

            $password_old = trim($data['password_old'] ?? '');
            $password_old_check = $user['user_pwd'];

            if (!password_verify($password_old, $password_old_check)){
                $error['error'] = '現在のパスワードが一致しません。';
            } else {
            $password = $data['password'];
            $password_check = $data['password_check'];

            // 共通のチェック関数を呼び出す
            $error['error'] = self::validateRegistUserPassword($password, $password_check);
            }

            $errors = array_filter($error);

            return $errors;
        } catch (Exception $e) {
            $_SESSION['status_message'] = "システムエラーが発生しました。";
            header('Location: /php/main.php');
            exit;
        }
    }

    // ユーザー登録確認ページ用
    public function validateRegistUser($data)
    {
        try {
            $error = [];

            $name = $data['name'];
            $mail = $data['mail'];
            $tel = $data['tel'];
            $password = $data['password'];
            $password_check = $data['password_check'];


            // 共通のチェック関数を呼び出す
            $error['name'] = self::validateRegistUserName($name);
            $error['mail'] = $this->validateRegistUserMail($mail);
            $error['tel'] = self::validateRegistUserTel($tel);
            $error['password'] = self::validateRegistUserPassword($password, $password_check);

            $errors = array_filter($error);

            return $errors;
        } catch (Exception $e) {
            $_SESSION['status_message'] = "システムエラーが発生しました。";
            header('Location: /php/main.php');
            exit;
        }
    }

    // 新規登録完了用
    public function validateRegistUserCompleat($input_data, $input_data_check)
    {
        try {
            $error = [];

            $name = $input_data['name'];
            $mail = $input_data['mail'];
            $tel = $input_data['tel'];
            $password = $input_data['password'];
            $name_check = $input_data_check['name'];
            $mail_check = $input_data_check['mail'];
            $tel_check = $input_data_check['tel'];
            $password_check = $input_data_check['password'];

            if ($name !== $name_check || $mail !== $mail_check || $tel !== $tel_check || $password !== $password_check) {
                $error['error'] = '不正な操作が確認されました。';
            } else {
                // 共通のチェック関数を呼び出す
                $error['name'] = self::validateRegistUserName($name);
                $error['mail'] = $this->validateRegistUserMail($mail);
                $error['tel'] = self::validateRegistUserTel($tel);
                $error['password'] = self::validateUserPassword($password);
            }
            $errors = array_filter($error);

            return $errors;
        } catch (Exception $e) {
            $_SESSION['status_message'] = "バリデーションチェックでエラーが発生しました。";
            header('Location: /php/main.php');
            exit;
        }
    }
}
