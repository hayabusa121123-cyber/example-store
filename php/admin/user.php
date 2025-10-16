<?php
session_start();
require_once '../Database.php';
require_once 'search.php';

// 戻す
if ($_SESSION['admin_flg'] !== '1') {
    header('Location: ../main.php');
    exit;
}

$input_data = $_POST;
$search = new adminSearch($pdo);
// クラスの静的メソッドを呼び出す
$user = $search->searchUsers($input_data);

?>

<head>
    <link rel="stylesheet" href="#">
    <title>ユーザー管理</title>
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
        <div>
            <form method="POST" action="#">
                <div class="code">
                    <label for="code" class="text_update_info"> ユーザーコード </label><br>
                    <input type="number" class="form_area" name="code" id="code" value="<?php if (isset($input_data['code'])) {echo htmlspecialchars($input_data['code']);} ?>">
                </div>
                <div class="name">
                    <label for="name" class="text_update_info"> 名前 </label><br>
                    <input type="text" class="form_area" name="name" id="name" maxlength="10" value="<?php if (isset($input_data['name'])) {echo htmlspecialchars($input_data['name']);} ?>">
                </div>
                <div class="mail">
                    <label for="mail" class="text_update_info"> メールアドレス </label><br>
                    <input type="email" class="form_area" name="mail" id="mail" value="<?php if (isset($input_data['mail'])) {echo htmlspecialchars($input_data['mail']);} ?>">
                </div>
                <div class="tel">
                    <label for="tel" class="text_update_info"> 電話番号(ハイフンなし) </label><br>
                    <input type="number" class="form_area" name="tel" id="tel" value="<?php if (isset($input_data['tel'])) {echo htmlspecialchars($input_data['tel']);} ?>">
                </div>
                <div class="submit_btn">
                    <button type="submit" class="button"> 検索 </button>
                </div>
            </form>
        </div>
        <div class="title">
            <span class="text_title">
                <h2>ユーザー全件リスト</h2>
            </span>
        </div>
        <?php if (count($user) > 0): ?>
            <table border='1'>
                <tr>
                    <th>ユーザーコード</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                </tr>
                <?php foreach ($user as $user_data): ?>
                    <tr>
                        <td><?php echo $user_data['user_cd']; ?></td>
                        <td><?php echo $user_data['user_nm']; ?></td>
                        <td><?php echo $user_data['user_mail']; ?></td>
                        <td><?php echo $user_data['user_tel']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>登録されているユーザーはいません。</p>
        <?php endif; ?>
    </div>
</body>

<script src="/js/main.js"></script>

</html>