<?php
session_start();

if (!isset($_SESSION['user_cd'])) {
    // 非会員はmainに戻す
    header('Location: ../main.php');
    exit; // リダイレクト後は必ず exit/die で処理を終了
}

require_once '../Database.php';

try {
    $pdo = Database::getInstance()->getConnection();

    $stmt = $pdo->prepare("SELECT user_nm, user_mail, user_tel FROM mst_user WHERE user_cd = :cd");
    $stmt->execute([':cd' => $_SESSION['user_cd']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['status_message'] = "システムエラーが発生しました。";
    header('Location: main.php');
    exit;
}

if (isset($_SESSION['status_message'])) {
    // 1. メッセージを取得
    $message_content = $_SESSION['status_message'];
    // 2. セッションから削除（再表示を防ぐ）
    unset($_SESSION['status_message']);
}
?>

<html>

<head>
    <link rel="stylesheet" href="/css/mypage/mypage.css">
    <title>会員情報確認</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="main">
        <div class="error_msg">

        </div>
        <div class="title">
            <span class="text_title"> 会員情報確認 </span>
        </div>
        <?php
        // メッセージが設定されている場合にモーダルを呼び出す
        if ($message_content) {
            require_once '../message.php';
        }
        ?>
        <div class="user_info">
            <form method="POST" action="edit.php">
                <div class="name">
                    <p class="text_update_info"> 名前 </p><br>
                    <p class="form_area" name="name" id="name"><?php echo htmlspecialchars($user['user_nm']); ?></p>
                </div>
                <div class="mail">
                    <p class="text_update_info"> メールアドレス </p><br>
                    <p class="form_area" name="mail" id="mail"><?php echo htmlspecialchars($user['user_mail']); ?></p>
                </div>
                <div class="tel">
                    <p class="text_update_info"> 電話番号 </p><br>
                    <p class="form_area" name="tel" id="tel"><?php echo htmlspecialchars($user['user_tel']); ?></p>
                </div>
                <div class="submit_btn">
                    <button type="button" class="button back" onclick="history.back()"> 戻る </button>
                    <button type="submit" class="button"> 編集 </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>