<?php
// 1. セッションを開始する
session_start();

// 2. セッションを破棄する
// クライアント側のセッションID（Cookie）を削除する
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッション変数を全て解除する
$_SESSION = array();

$_SESSION['status_message'] = 'ログアウトしました。';
session_regenerate_id(true);

// 3. ログアウト後のページへリダイレクトする
header('Location: main.php');
exit; // リダイレクト後は必ず exit/die で処理を終了
