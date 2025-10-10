document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('statusModal');
    const closeBtn = modal.querySelector('.close-btn');

    // ページロード時に即座にモーダルを表示
    modal.style.display = "grid";

    // 閉じるボタンがクリックされたら非表示
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // モーダルの外側をクリックしたら非表示
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
});
