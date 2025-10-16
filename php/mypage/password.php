<?php
session_start();

if (!isset($_SESSION['user_cd'])) {
    // 非会員はmainに戻す
    header('Location: ../main.php');
    exit; // リダイレクト後は必ず exit/die で処理を終了
}

// メッセージ表示用
if (isset($_SESSION['update_error'])) {
    $validation_errors = $_SESSION['update_error'];
    unset($_SESSION['update_error']);
}

?>

<html>

<head>
    <link rel="stylesheet" href="/css/mypage/password.css">
    <title>パスワード変更</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="main">
        <div class="error_msg">
            <p class="error"> <?php echo isset($validation_errors["error"]) ? $validation_errors["error"] : null ?></p>
        </div>
        <div class="title">
            <span class="text_title"> パスワード変更 </span>
        </div>
        <div class="user_info">
            <form method="POST" action="updateUserPassword.php" onsubmit="return confirm('更新しますか？')">
                <div class="password_old">
                    <label for="password_old" class="text_register_info"> 現在のパスワード </label><br>
                    <input type="password" class="form_area" name="password_old" id="password_old" value=""><br>
                </div>
                <div class="password">
                    <label for="password" class="text_register_info"> 新しいパスワード </label><br>
                    <p class="terms">(8～20文字、数字・小文字・大文字それぞれ1文字以上)</p>
                    <input type="password" class="form_area" name="password" id="password" value=""><br>
                    <label for="password_check" class="text_register_info"> 確認のため再入力 </label><br>
                    <input type="password" class="form_area" name="password_check" id="password_check" value="">
                </div>
                <div class="submit_btn">
                    <button type="button" class="button back" onclick="history.back()"> 戻る </button>
                    <button type="submit" class="button"> 次へ </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>