<?php
session_start();

if (isset($_SESSION['user_cd'])) {
    // ログインユーザーがログインページに入れないように
    header('Location: main.php');
    exit; // リダイレクト後は必ず exit/die で処理を終了
}
?>

<html>

<head>
    <link rel="stylesheet" href="/css/login.css">
    <title>ログイン画面</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="main">
        <div class="error_msg">
            <?php if (isset($_SESSION['login_error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
                <?php unset($_SESSION['login_error']); // メッセージを一度表示したら消去 
                ?>
            <?php endif; ?>
        </div>
        <div class="title">
            <span class="text_title"> ログイン </span>
        </div>
        <div class="login">
            <form method="POST" action="auth.php">
                <div class="mail">
                    <label for="mail" class="text_login_info"> メールアドレス </label><br>
                    <input type="text" class="form_area" name="mail" id="mail" value="">
                </div>
                <div class="password">
                    <label for="password" class="text_login_info"> パスワード </label><br>
                    <input type="password" class="form_area" name="password" id="password" value="">
                </div>
                <div class="submit_btn">
                    <button type="button" class="button back" onclick="history.back()"> 戻る </button>
                    <button type="submit" class="button"> ログイン </button>
                </div>
            </form>
        </div>
        <div class="register_page">
            <a href="register/registerForm.php" class="button"> 新規登録 </a>
        </div>
    </div>
</body>

</html>