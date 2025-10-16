// 1. 要素の取得
const toggleButton = document.getElementById('username');
const menuContent = document.getElementById('usermenu');
const hiddenClass = 'menu-hidden';

// 2. ボタンにイベントリスナーを設定
toggleButton.addEventListener('click', function(event) {
  // 3. メニュー要素からクラスをトグル（切り替え）
  //    - クラスがあれば削除し、なければ追加する
  menuContent.classList.toggle(hiddenClass);
  event.stopPropagation();
})

window.addEventListener('click', function(event) {
    // 1. メニューが現在表示されているかチェック
    if (!menuContent.classList.contains(hiddenClass)) {
        if (!menuContent.contains(event.target)) {
            menuContent.classList.add(hiddenClass);
        }
    }
});
