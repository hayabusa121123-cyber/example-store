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
        <div class="user">
            <?php if ($user_cd): ?>
                <button id="username"><?php echo $user_nm . " さん"; ?> </button>
                <nav id="usermenu" class="menu-select menu-hidden">
                    <ul>
                        <?php if ($_SESSION['admin_flg'] === '1'): ?>
                            <li><a href='admin/top.php'>管理</a></li>
                        <?php endif; ?>
                        <li><a href="mypage/mypage.php">会員情報確認</a></li>
                        <li><a href="mypage/password.php">パスワード変更</a></li>
                        <li><a href="logout.php">ログアウト</a></li>
                    </ul>
                </nav>
            <?php else: ?>
                <a href="login.php" class="button"> ログイン </a>
                <a href="register/registerForm.php" class="button"> 新規登録 </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    // メッセージが設定されている場合にモーダルを呼び出す
    if ($message_content) {
        require_once 'message.php';
    }
    ?>
    <div class="main">
        <div class="title">
            <span class="text_title"> 一覧aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa </span>
        </div>
        <div class="products">
            ここに商品aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
        </div>
        <div class="products">
            ここに商品aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
        </div>
        <div class="products">
            ここに商品aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
        </div>
        <div class="products">
            ここに商品aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
        </div>
        <div class="products">
            ここに商品aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
        </div>
        <div class="products">
            ここに商品aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
        </div>
    </div>
</body>

<script src="/js/main.js"></script>

</html>