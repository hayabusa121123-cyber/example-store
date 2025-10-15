<?php
session_start();
$user_cd = isset($_SESSION['user_cd']) ? $_SESSION['user_cd'] : null;
$user_nm = isset($_SESSION['user_nm']) ? $_SESSION['user_nm'] : null;

// メッセージ表示用
$message_content = null;

if (isset($_SESSION['status_message'])) {
    // 1. メッセージを取得
    $message_content = $_SESSION['status_message'];
    // 2. セッションから削除（再表示を防ぐ）
    unset($_SESSION['status_message']);
}
?>

<html>

<head>
    <link rel="stylesheet" href="/css/main.css">
    <title>メインページ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="header">
        <?php if ($user_cd): ?>
            <p><?php echo $user_nm . "さん"; ?> </p>
            <a href="mypage/mypage.php">会員情報確認</a>
            <a href="logout.php">ログアウト</a>
        <?php else: ?>
            <a href="login.php" class="button"> ログイン </a>
            <a href="register/registerForm.php" class="button"> 新規登録 </a>
        <?php endif; ?>
    </div>
    <?php
    // メッセージが設定されている場合にモーダルを呼び出す
    if ($message_content) {
        require_once 'message.php';
    }
    ?>
    <div class="main">
        <div class="title">
            <span class="text_title"> 一覧 </span>
        </div>
        <div class="products">
            ここに商品
        </div>
    </div>
</body>

</html>