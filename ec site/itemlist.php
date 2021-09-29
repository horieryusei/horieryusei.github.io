<?php

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/itemlist_model.php';

// 変数の初期化
$sql     = '';
$data    = array();
$err_msg = array();
$sql_kind   = '';
$result_msg = '';
$dbh        = null; // DBハンドル

// セッション開始
session_start();

// ユーザがログインしているかどうかチェック
check_user_login();

// DBに接続します
try {
  $dbh = get_db_connect($DSN, $USER, $PASSWD);
} catch (PDOException $e) {
  $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
}

// DBを操作します
if ($dbh) {

  // 実行タイプを取得します
  $sql_kind = get_post_data("sql_kind");

  //
  // カートを入れる
  //
  if ($sql_kind === 'insert_cart') {

    // user_idの取得
    $user_id = $_SESSION['user_id'];
    // パラメータの取得
    $item_id = get_post_data('item_id');

    // 「カートに入れる」ボタンをクリックした場合、指定の商品をカートに入れる
    try {
      execute_cart_click($dbh, $item_id, $user_id);
      $result_msg = 'カートに追加しました。';
    } catch  (PDOException $e) {
      $err_msg[] = 'カート更新に失敗'.$e->getMessage();
    }
  }

  //
  // 商品情報の取得
  //
  try {
    $result = get_item_list_all_by_status($dbh);
    if ($result) {
      $data = entity_assoc_array($result);
    } else {
      $err_msg[] = '商品はありません。';
    }
  } catch (PDOException $e) {
    $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
  }

}

// テンプレートファイル読み込み
include_once './view/itemlist_view.php';

?>
