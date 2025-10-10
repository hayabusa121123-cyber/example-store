<?php
    $safe_message = htmlspecialchars($message_content ?? '処理が完了しました。');
?>

<link rel="stylesheet" href="/css/message.css">

<div id="statusModal" class="modal" style="display:none;">
    <div class="modal-content">
	<p><?php echo $safe_message; ?></p>
        <button class="close-btn">閉じる</button>
    </div>
</div>

<script src="/js/message.js"></script>
