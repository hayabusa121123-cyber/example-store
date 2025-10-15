<?php
session_start();

if (isset($_SESSION['user_cd'])) {
    // ログインユーザーが入れないように
    header('Location: main.php');
    exit; // リダイレクト後は必ず exit/die で処理を終了
}

// メッセージ表示用
if (isset($_SESSION['register_error'])) {
    $validation_errors = $_SESSION['register_error'];
    $input_data = $_SESSION['input_data'];
    unset($_SESSION['register_error']);
    unset($_SESSION['input_data']);
}

?>

<html>

<head>
    <link rel="stylesheet" href="/css/register/registerForm.css">
    <title>新規会員登録</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="main">
        <div class="error_msg">
            <p class="error"> <?php echo isset($validation_errors["error"]) ? $validation_errors["error"] : null ?></p>
        </div>
        <div class="title">
            <span class="text_title"> 新規会員登録 </span>
        </div>
        <div class="user_info">
            <form method="POST" action="check.php">
                <div class="name">
                    <label for="name" class="text_register_info"> 名前 </label><br>
                    <input type="text" class="form_area" name="name" id="name" maxlength="10" value="<?php echo htmlspecialchars($input_data['name']); ?>">
                    <p class="error"> <?php echo isset($validation_errors["name"]) ? $validation_errors["name"] : null ?></p>
                </div>
                <div class="mail">
                    <label for="mail" class="text_register_info"> メールアドレス </label><br>
                    <input type="email" class="form_area" name="mail" id="mail" value="<?php echo htmlspecialchars($input_data['mail']); ?>">
                    <p class="error"> <?php echo isset($validation_errors["mail"]) ? $validation_errors["mail"] : null ?></p>
                </div>
                <div class="tel">
                    <label for="tel" class="text_register_info"> 電話番号(ハイフンなし) </label><br>
                    <input type="number" class="form_area" name="tel" id="tel" value="<?php echo htmlspecialchars($input_data['tel']); ?>">
                    <p class="error"> <?php echo isset($validation_errors["tel"]) ? $validation_errors["tel"] : null ?></p>
                </div>
                <div class="password">
                    <label for="password" class="text_register_info"> パスワード </label><br>
                    <p class="terms">(8～20文字、数字・小文字・大文字それぞれ1文字以上)</p>
                    <input type="password" class="form_area" name="password" id="password" value=""><br>
                    <label for="password_check" class="text_register_info"> 確認のため再入力 </label><br>
                    <input type="password" class="form_area" name="password_check" id="password_check" value="">
                    <p class="error"> <?php echo isset($validation_errors["password"]) ? $validation_errors["password"] : null ?></p>
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