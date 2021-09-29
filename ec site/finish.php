<?php

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/finish_model.php';

// 変数の初期化
$sql     = '';
$data    = array();
$err_msg = array();
$result_msg = '';
$sum_price = 0;
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

  // user_idの取得
  $user_id = $_SESSION['user_id'];

  //
  // 商品情報の取得
  //
  try {
    $result = get_cart_item_list($dbh, $user_id);
    if ($result) {
      $data = entity_assoc_array($result);
      // ショッピングカートにある商品の合計を表示する。
      $sum_price = get_sum_price($data);
    } else {
      $err_msg[] = '商品はありません。';
    }
  } catch  (PDOException $e) {
    $err_msg[] = '削除に失敗'.$e->getMessage();
  }

  //
  // カート情報の削除
  //
  try {
    delete_cart($dbh, $user_id);
  } catch  (PDOException $e) {
    $err_msg[] = 'カート削除に失敗'.$e->getMessage();
  }

  //
  // 在庫数の更新
  //
  if (count($err_msg) === 0) {
    try {
      $date = date('Y-m-d H:i:s');
      upadte_multiple_item_stock($dbh, $data, $date);
    } catch  (PDOException $e) {
      $err_msg[] = '在庫数の更新に失敗'.$e->getMessage();
    }
  }
}

// テンプレートファイル読み込み
include_once './view/finish_view.php';

?>
