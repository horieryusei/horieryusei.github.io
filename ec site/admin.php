<?php

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/admin_model.php';

// 変数の初期化
$sql_kind   = '';   // 処理の種類
$result_msg = '';   // 更新時の表示メッセージ
$data       = array();   // 表示データ
$err_msg    = array();   // エラーメッセージ
$dbh        = null; // DBハンドル

$new_name   = '';
$new_price  = '';
$new_stock  = '';
$new_img    = 'no_image.png';
$new_status = '';

$update_stock = '';
$item_id     = '';

// 実行タイプを取得します
$sql_kind = get_post_data('sql_kind');

//
// パラメータのエラーチェックします
//
if ($sql_kind === 'insert') {     // 商品追加の場合

  // パラメータの取得
  $new_name = get_post_data('new_name');
  $new_name = trim_space($new_name);

  $new_price = get_post_data('new_price');
  $new_price = trim_space($new_price);

  $new_stock = get_post_data('new_stock');
  $new_stock = trim_space($new_stock);

  $new_status = get_post_data('new_status');

  // パラメータのエラーチェックします
  if ($new_name === '') {
    $err_msg[] = '名前を入力してください。';
  }

  if ($new_price === '') {
    $err_msg[] = '値段を入力してください。';
  } else if (check_number($new_price) !== TRUE ) {
    $err_msg[] = '値段は半角数字を入力してください';
  } else if ($new_price > 10000) {
    $err_msg[] = '値段は1万円以下にしてください';
  }

  if ($new_stock === '') {
    $err_msg[] = '個数を入力してください。';
  } else if (check_number($new_stock) !== TRUE ) {
    $err_msg[] = '個数は半角数字を入力してください';
  }

  if (preg_match('/\A[01]\z/', $new_status) !== 1 ) {
    $err_msg[] = '不正な処理です';
  }

  //  HTTP POST でファイルがアップロードされたかどうか確認
  if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {

    $new_img = $_FILES['new_img']['name'];

    // 画像の拡張子取得
    $extension = pathinfo($new_img, PATHINFO_EXTENSION);

    // 拡張子チェック
    if ($extension === 'jpg' || $extension == 'jpeg' || $extension == 'png') {

      // ユニークID生成し保存ファイルの名前を変更
      $new_img = md5(uniqid(mt_rand(), true)) . '.' . $extension;

      // 同名ファイルが存在するか確認
      if (is_file($IMG_DIR . $new_img) !== TRUE) {

        // ファイルを移動し保存
        if (move_uploaded_file($_FILES['new_img']['tmp_name'], $IMG_DIR . $new_img) !== TRUE) {
          $err_msg[] = 'ファイルアップロードに失敗しました';
        }

      // 生成したIDがかぶることは通常ないため、IDの再生成ではなく再アップロードを促すようにした
      } else {
        $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
      }

    } else {
      $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEG又はPNGのみ利用可能です。';
    }

  } else {
    $err_msg[] = 'ファイルを選択してください';
  }

} 
// 在庫情報の更新の場合
else if ($sql_kind === 'update') {

  // パラメータの取得
  $update_stock = get_post_data('update_stock');
  $update_stock = trim_space($update_stock);

  $item_id = get_post_data('item_id');

  // パラメータのエラーチェックします
  if ($update_stock === '') {
    $err_msg[] = '個数を入力してください。';
  } else if (check_number($update_stock) !== TRUE ) {
    $err_msg[] = '個数は半角数字を入力してください';
  }

  if (check_number($item_id) !== TRUE ) {
    $err_msg[] = '不正な処理です';
  }

} 
// スタータス情報の更新の場合
else if ($sql_kind === 'change') {

  // パラメータの取得
  $change_status = get_post_data('change_status');
  $item_id      = get_post_data('item_id');

  // パラメータのエラーチェックします
  if (preg_match('/\A[01]\z/', $change_status) !== 1 ) {
    $err_msg[] = '不正な処理です';
  }

  if (check_number($item_id) !== TRUE ) {
    $err_msg[] = '不正な処理です';
  }

} 
// 商品情報の削除の場合
else if ($sql_kind === 'delete') {

  $item_id     = get_post_data('item_id');

  // パラメータのエラーチェックします
  if (check_number($item_id) !== TRUE ) {
    $err_msg[] = '不正な処理です';
  }

}

// DBに接続します
try {
  $dbh = get_db_connect($DSN, $USER, $PASSWD);

} catch (PDOException $e) {
  $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
}

// DBを操作します
if ($dbh) {

  if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // 現在日時を取得します
    $date = date('Y-m-d H:i:s');

    //
    // 商品情報を新規登録します
    //
    if ($sql_kind === 'insert') {

      try {
        // トランザクション開始
        $dbh->beginTransaction();
        // DBに商品データの追加処理を実行します（SQLを実行します）
        insert_item($dbh, $new_name, $new_price, $new_img, $date, $new_status);

        // 在庫情報を登録する
        // INSERTされたデータのIDを取得
        $item_id = $dbh->lastInsertId('item_id');
        insert_item_stock($dbh, $item_id, $new_stock, $date);

        $result_msg =  '商品を追加しました。';
        $dbh->commit();

      } catch (PDOException $e) {
        $dbh->rollback();
        $err_msg[] = '商品追加の失敗 '.$e->getMessage();
      }
    }
    //
    // 在庫数を変更します
    //
    else if ($sql_kind === 'update') {

      try {
        upadte_item_stock($dbh, $item_id, $update_stock, $date);
        $result_msg = '在庫を変更しました。';
      } catch  (PDOException $e) {
        $err_msg[] = '在庫変更失敗1 '.$e->getMessage();
      }

    }
    //
    // ステータスを変更します
    //
    else if ($sql_kind === 'change') {

      try {
        upadte_item_status($dbh, $item_id, $change_status, $date);
        $result_msg = 'ステータスを変更しました。';
      } catch (PDOException $e) {
        $err_msg[] = 'ステータス変更失敗 '.$e->getMessage();
      }
    }
    //
    // 商品情報を削除します
    //
    else if ($sql_kind === 'delete') {

      try {
        // トランザクション開始
        $dbh->beginTransaction();
        delete_item($dbh, $item_id);
        delete_item_stock($dbh, $item_id);
        $result_msg = '商品を削除しました。';
        $dbh->commit();

      } catch (PDOException $e) {
        $dbh->rollback();
        $err_msg[] = '削除失敗 '.$e->getMessage();
      }
    }
  }

  //
  // 商品情報を取得します
  //
  try {
    $result = get_item_list_all($dbh);
    if ($result) {
      $data = entity_assoc_array($result);
    } else {
      $err_msg[] = '商品が登録されていません。';
    }
  } catch (PDOException $e) {
    $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
  }

}

// テンプレートファイル読み込み
include_once './view/admin_view.php';

?>
