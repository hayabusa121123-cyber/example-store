<?php
session_start();
require_once '../Database.php';
require_once '../validator.php';

// POSTリクエストでなければ戻す
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['status_message'] = '不正なアクセスです。';
    header('Location: ../main.php');
    exit;
}

$input_data = $_POST;
$validator = new Validator($pdo);
// クラスの静的メソッドを呼び出す
$validation_errors = $validator->validateUpdateUserPassword($input_data);

if (!empty($validation_errors) && isset($validation_errors)) {
    $_SESSION['update_error'] = $validation_errors;
    header('Location: password.php');
    exit;
} else {
    try {
        $hashed_password = password_hash($input_data['password'], PASSWORD_DEFAULT);

        $pdo = Database::getInstance()->getConnection();

        $sql = "
        UPDATE mst_user SET 
            user_pwd = ?,
            last_update_user_cd = ?,
            last_update_user_name = ?,
            last_update_datetime = CURRENT_TIMESTAMP
        WHERE user_cd = ?
    ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $hashed_password,
            $_SESSION['user_cd'],
            $_SESSION['user_nm'],
            $_SESSION['user_cd']
        ]);

        $_SESSION['status_message'] = 'パスワードを変更しました。';
        // セッションIDを再生成し、セッション固定化攻撃を防ぐ
        session_regenerate_id(true);

        // リダイレクト
        header('Location: ../main.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['status_message'] = "システムエラーが発生しました。";
        header('Location: ../main.php');
        exit;
    }
}
