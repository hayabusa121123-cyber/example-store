<?php
session_start();
require_once '../Database.php';

if (!isset($_SESSION['input_data'])) {
    $_SESSION['status_message'] = '不正なアクセスです。';
    header('Location: ../main.php');
    exit;
}

$input_data = $_SESSION['input_data'];
unset($_SESSION['input_data']);

try {
    $pdo = Database::getInstance()->getConnection();

    $stmt = $pdo->prepare("UPDATE mst_user SET user_nm = :user_nm, user_mail = :mail, user_tel = :tel WHERE user_cd = :user_cd");
    $stmt->execute([':user_nm' => $input_data['name'], ':mail' => $input_data['mail'], ':tel' => $input_data['tel'], ':user_cd' => $_SESSION['user_cd']]);

    $_SESSION['status_message'] = '更新しました。';
    // セッションIDを再生成し、セッション固定化攻撃を防ぐ
    session_regenerate_id(true);

    // リダイレクト
    header('Location: mypage.php');
    exit;
} catch (Exception $e) {
    $_SESSION['status_message'] = "システムエラーが発生しました。";
    header('Location: ../main.php');
    exit;
}
