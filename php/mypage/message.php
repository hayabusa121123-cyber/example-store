<?php
$message = htmlspecialchars($message_content ?? '処理が完了しました。');
?>

<link rel="stylesheet" href="/css/message.css">

<div id="statusModal" class="modal" style="display:none;">
    <div class="modal-content">
        <p><?php echo $message; ?></p>
        <?php if (isset($message)): ?>
            <form method="POST" action="edit.php">
                <input type="hidden" class="form_area" name="name" id="name" maxlength="10" value="<?php echo htmlspecialchars($_POST['name']); ?>">
                <input type="hidden" class="form_area" name="mail" id="mail" value="<?php echo htmlspecialchars($_POST['mail']); ?>">
                <input type="hidden" class="form_area" name="tel" id="tel" value="<?php echo htmlspecialchars($_POST['tel']); ?>">
                <button type="button" class="close-btn">キャンセル</button>
                <button type="submit">更新</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="/js/message.js"></script>