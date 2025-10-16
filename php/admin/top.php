<?php
session_start();
require_once '../Database.php';

// 戻す
if ($_SESSION['admin_flg'] !== '1') {
    header('Location: ../main.php');
    exit;
}

?>

<head>
    <link rel="stylesheet" href="#">
    <title>管理画面トップ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <div class="header">
        <div class="user">
            管理画面です
        </div>
    </div>
    <div class="main">
        <div class="title">
            <span class="text_title"> 一覧aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa </span>
        </div>
        <a href="user.php" class="button"> ユーザー管理 </a>
        <a href="#" class="button"> 商品管理 </a>
    </div>
</body>

<script src="/js/main.js"></script>

</html>