<?php
session_start();
require_once '../Database.php';

// POSTリクエストでなければ戻す
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ../main.php');
    exit;
}

try {
    $pdo = Database::getInstance()->getConnection();
} catch (Exception $e) {
    // 接続エラーが発生した場合の処理（データベース接続失敗）
    $_SESSION['status_message'] = "データベース接続エラーが発生しました。";
    header('Location: ../main.php');
    exit;
}

if (!isset($_SESSION['input_data'])) {
    $_SESSION['status_message'] = '不正なアクセスです。';
    header('Location: ../main.php');
    exit;
}

$input_data = $_POST;
$input_data_check = $_SESSION['input_data'];

$validation_errors = [];

$form_token = $input_data['csrf_token'] ?? null;
$session_token = $_SESSION['csrf_token'] ?? null;

// トークンを比較
if (!$form_token) {
    $validation_errors['error'] = '不正な操作が確認されました。（CSRFトークン無効）';
} elseif ($form_token !== $session_token) {
    $validation_errors['error'] = '不正な操作が確認されました。（CSRFトークン不一致）';
} else {
    require_once '../validator.php';

    $validator = new Validator($pdo);
    // クラスの静的メソッドを呼び出す
    $validation_errors = $validator->validateRegistUserCompleat($input_data, $input_data_check);
}

/**
 * 6桁の一意なユーザーコードを生成する
 *
 * @param PDO $pdo データベース接続オブジェクト
 * @return int 6桁の一意なユーザーコード
 */
function generateUniqueUserCode(PDO $pdo): int
{
    $min = 100000; // 最小値 
    $max = 999999; // 6桁の最大値 (999,999)
    $code = 0;

    // 無限ループを防ぐため、最大試行回数を設定（必須ではないが安全策）
    $max_attempts = 1000;

    for ($i = 0; $i < $max_attempts; $i++) {
        // 1. 6桁のランダムな数字を生成
        // mt_rand() は rand() よりも高速で品質の良い乱数を生成
        $code = mt_rand($min, $max);

        // 2. データベースで一意性をチェック（プリペアドステートメントを使用）
        $sql = "SELECT COUNT(*) FROM mst_user WHERE user_cd = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code]);
        $count = $stmt->fetchColumn();

        // 3. 一意であればコードを確定し、ループを抜ける
        if ($count == 0) {
            return $code;
        }

        // 注意: テーブルが一杯になった場合、ループが最大試行回数に達して失敗する
    }

    // 最大試行回数内に一意なコードが見つからなかった場合
    throw new Exception("Unique code generation failed after $max_attempts attempts.");
}

if (!empty($validation_errors)) {
    $_SESSION['register_error'] = $validation_errors;
    $_SESSION['input_data'] = $input_data;
    header('Location: registerForm.php');
    exit;
} else {
    // 登録処理を行う
    $hashed_password = password_hash($input_data['password'], PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();

        $unique_code = generateUniqueUserCode($pdo);

        $sql = "
        INSERT INTO mst_user (
            user_cd,
            user_nm,
            user_mail,
            user_tel,
            user_pwd,
            create_user_cd,
            create_user_name,
            create_datetime
        ) VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            CURRENT_TIMESTAMP
    )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $unique_code,
            $input_data['name'],
            $input_data['mail'],
            $input_data['tel'],
            $hashed_password,
            $unique_code,
            $input_data['name']
        ]);

        $pdo->commit();

        // セッションIDを再生成し、セッション固定化攻撃を防ぐ
        unset($_SESSION['csrf_token']);
        unset($_SESSION['input_data']);

        $_SESSION['user_cd'] = $unique_code;
        $_SESSION['user_nm'] = $input_data['name'];
        session_regenerate_id(true);

        header('Location: compleat.php');
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['status_message'] = "登録処理でエラーが発生しました。";
        header('Location: ../main.php');
        exit;
    }
}
