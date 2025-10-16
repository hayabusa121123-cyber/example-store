<?php
session_start();
require_once 'Database.php';

// POSTリクエストでなければ、ログインページへ戻す
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['login_error'] = "不正";
    header('Location: /login.php');
    exit;
}

try {
    $pdo = Database::getInstance()->getConnection();
} catch (Exception $e) {
    $_SESSION['login_error'] = "システムエラーが発生しました。";
    header('Location: login.php');
    exit;
}
$input_mail = $_POST['mail'] ?? '';
$input_password = trim($_POST['password'] ?? '');

if (empty($input_mail) || empty($input_password)) {
    $_SESSION['login_error'] = "メールアドレスとパスワードを入力してください。";
    header('Location: login.php');
    exit;
} else {
    try {
        $stmt = $pdo->prepare("SELECT user_cd, CONCAT('', user_pwd) AS user_pwd, user_nm, admin_flg FROM mst_user WHERE user_mail = :mail");
        $stmt->execute([':mail' => $input_mail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $db_hash = $user['user_pwd'];

        if ($user && password_verify($input_password, $db_hash)) {

            // ログイン成功
            $_SESSION['user_cd'] = $user['user_cd'];
            $_SESSION['user_nm'] = $user['user_nm'];
            $_SESSION['admin_flg'] = $user['admin_flg'];

            $_SESSION['status_message'] = 'ログインしました。';

            // セッションIDを再生成し、セッション固定化攻撃を防ぐ
            session_regenerate_id(true);

            // リダイレクト
            header('Location: main.php');
            exit;
        } else {
            // ユーザーが存在しない、またはパスワードが間違っている場合
            // ※セキュリティのため、どちらが原因か具体的に示さないのが一般的
            $_SESSION['login_error'] = "メールアドレスまたはパスワードが間違っています。";
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "ログイン処理に失敗しました。";
        error_log("Login DB Error: " . $e->getMessage());
        header('Location: login.php');
        exit;
    }
}
