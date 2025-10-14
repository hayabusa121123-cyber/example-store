<?php
session_start();

require_once '../validator.php';

$input_data = $_POST;
$validator = new Validator($pdo);
// クラスの静的メソッドを呼び出す
$validation_errors = $validator->validateUpdateUser($input_data);

if (!empty($validation_errors) && isset($validation_errors)) {
    $_SESSION['update_error'] = $validation_errors;
    $_SESSION['input_data'] = $input_data;
    header('Location: edit.php');
    exit;
} else {
    $_SESSION['status_message'] = '更新しますか？';
    $_SESSION['input_data'] = $input_data;

    // $_SESSION['show_confirm_modal'] = true;

    header('Location: updateUserData.php');
    exit;
}

