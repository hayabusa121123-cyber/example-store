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

// モーダル表示用
$message_content = null;

if (isset($_SESSION['status_message'])) {
    // 1. メッセージを取得
    $message_content = $_SESSION['status_message'];
    // 2. セッションから削除（再表示を防ぐ）
    unset($_SESSION['status_message']);
}

if (isset($_SESSION['update_error'])) {
    $validation_errors = $_SESSION['update_error'];
    unset($_SESSION['update_error']);
}

?>

<html>

<head>
    <link rel="stylesheet" href="/css/mypage/edit.css">
    <title>会員情報変更</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="main">
        <div class="error_msg">
            <?php if (isset($_SESSION['update_error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_SESSION['update_error']); ?></p>
                <?php unset($_SESSION['update_error']); // メッセージを一度表示したら消去 
                ?>
            <?php endif; ?>
        </div>
        <div class="title">
            <span class="text_title"> 会員情報編集 </span>
        </div>
        <?php
        // メッセージが設定されている場合にモーダルを呼び出す
        if ($message_content) {
            require_once 'message.php';
        }
        ?>
        <div class="user_info">
            <form method="POST" action="check.php" onsubmit="return confirm('更新しますか？')">
                <div class="name">
                    <label for="name" class="text_update_info"> 名前 </label><br>
                    <input type="text" class="form_area" name="name" id="name" maxlength="10" value="<?php echo htmlspecialchars($user['user_nm']); ?>">
                    <p class="error"> <?php echo isset($validation_errors["name"]) ? $validation_errors["name"] : null ?></p>
                </div>
                <div class="mail">
                    <label for="mail" class="text_update_info"> メールアドレス </label><br>
                    <input type="email" class="form_area" name="mail" id="mail" value="<?php echo htmlspecialchars($user['user_mail']); ?>">
                    <p class="error"> <?php echo isset($validation_errors["mail"]) ? $validation_errors["mail"] : null ?></p>
                </div>
                <div class="tel">
                    <label for="tel" class="text_update_info"> 電話番号(ハイフンなし) </label><br>
                    <input type="number" class="form_area" name="tel" id="tel" value="<?php echo htmlspecialchars($user['user_tel']); ?>">
                    <p class="error"> <?php echo isset($validation_errors["tel"]) ? $validation_errors["tel"] : null ?></p>
                </div>
                <div class="submit_btn">
                    <button type="button" class="button back" onclick="history.back()"> 戻る </button>
                    <button type="submit" class="button"> 変更 </button>
                </div>
            </form>
        </div>
    </div>
    <form id="final_update_form" action="update_complete.php" method="POST" style="display:none;"></form>
</body>


<script>
    <?php
    /*
    // セッションにモーダル表示フラグが立っているかチェック
    if (isset($_SESSION['show_confirm_modal']) && $_SESSION['show_confirm_modal'] === true) {

        // 状態メッセージもセッションから取得
        $message = $_SESSION['status_message'] ?? '更新しますか？';

        // フラグをすぐに解除
        unset($_SESSION['show_confirm_modal']);
        unset($_SESSION['status_message']); // メッセージも一度表示したら消去

        // JavaScriptコードを出力
        echo "var confirmMessage = '" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "';\n";
        echo "
        // ブラウザ標準の確認ダイアログを使用
        if (confirm(confirmMessage)) {
            // OKが押された場合: 次の処理（例：最終的な更新処理を呼び出す hidden フォームを送信）
            document.getElementById('final_update_form').submit(); 
        } else {
            // キャンセルが押された場合: 何もしないか、セッションデータをクリアする
            // 必要に応じて、キャンセル処理のためのリクエストをサーバーに送る
        }
    ";
    }
    */
    ?>
</script>

</html>