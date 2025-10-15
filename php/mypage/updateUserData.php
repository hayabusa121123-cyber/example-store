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

    $sql = "
        UPDATE mst_user SET 
            user_nm = ?,
            user_mail = ?,
            user_tel = ?,
            last_update_user_cd = ?,
            last_update_user_name = ?,
            last_update_datetime = CURRENT_TIMESTAMP
        WHERE user_cd = ?
    ";

    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        $input_data['name'],
        $input_data['mail'],
        $input_data['tel'],
        $_SESSION['user_cd'],
        $_SESSION['user_nm'],
        $_SESSION['user_cd']
    ]);

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
