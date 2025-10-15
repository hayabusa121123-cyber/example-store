<?php
session_start();

// POSTリクエストでなければ戻す
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ../main.php');
    exit;
}

require_once '../validator.php';

$input_data = $_POST;
$_SESSION['input_data'] = $input_data;

$validator = new Validator($pdo);
// クラスの静的メソッドを呼び出す
$validation_errors = $validator->validateRegistUser($input_data);

if (!empty($validation_errors) && isset($validation_errors)) {
    $_SESSION['register_error'] = $validation_errors;
    header('Location: registerForm.php');
    exit;
}
// CSRFトークンの生成
if (empty($_SESSION['csrf_token'])) {
    // 64文字のセキュアなランダム文字列を生成
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// フォーム埋め込み用に、変数にコピーしておく
$csrf_token = $_SESSION['csrf_token'];

?>

<html>

<head>
    <link rel="stylesheet" href="/css/register/check.css">
    <title>登録情報確認</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="main">
        <div class="error_msg">

        </div>
        <div class="title">
            <span class="text_title"> 登録情報確認 </span>
        </div>
        <div class="user_info">
            <form method="POST" action="registUserData.php">
                <div class="name">
                    <p class="text_register_info"> 名前 </p><br>
                    <p class="form_area" name="name" id="name"><?php echo htmlspecialchars($input_data['name']); ?></p>
                </div>
                <div class="mail">
                    <p class="text_register_info"> メールアドレス </p><br>
                    <p class="form_area" name="mail" id="mail"><?php echo htmlspecialchars($input_data['mail']); ?></p>
                </div>
                <div class="tel">
                    <p class="text_register_info"> 電話番号 </p><br>
                    <p class="form_area" name="tel" id="tel"><?php echo htmlspecialchars($input_data['tel']); ?></p>
                </div>
                <div class="password">
                    <p class="text_register_info"> パスワード </p><br>
                    <input disabled type="password" class="form_area" value="<?php echo htmlspecialchars($input_data['password']); ?>" readonly>
                </div>

                <input type="hidden" class="form_area" name="name" id="name" maxlength="10" value="<?php echo htmlspecialchars($input_data['name']); ?>">
                <input type="hidden" class="form_area" name="mail" id="mail" value="<?php echo htmlspecialchars($input_data['mail']); ?>">
                <input type="hidden" class="form_area" name="tel" id="tel" value="<?php echo htmlspecialchars($input_data['tel']); ?>">
                <input type="hidden" class="form_area" name="password" id="password" value="<?php echo htmlspecialchars($input_data['password']); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="submit_btn">
                    <button type="button" class="button back" onclick="history.back()"> 戻る </button>
                    <button type="submit" class="button"> 登録 </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>