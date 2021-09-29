<?php

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/admin_user_model.php';

// 変数の初期化
$data       = array();   // 表示データ
$err_msg    = array();   // エラーメッセージ
$dbh        = null; // DBハンドル

// DBに接続します
try {
  $dbh = get_db_connect($DSN, $USER, $PASSWD);
} catch (PDOException $e) {
  $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
}

// DBを操作します
if ($dbh) {

  try {
    // ユーザ情報を取得します
    $result = get_user_list($dbh);
    if ($result) {
      $data = entity_assoc_array($result);
    } else {
      $err_msg[] = 'ユーザ情報が登録されていません。';
    }
  } catch  (PDOException $e) {
    $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
  }

}

// テンプレートファイル読み込み
include_once './view/admin_user_view.php';
?>
