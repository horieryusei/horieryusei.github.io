<?php
//
// 共通系のモデル
// 共通関数
//

// ユーザがログインしているかどうかチェック
function check_user_login($url_root = "") {
  if (isset($_SESSION['user_name']) !== TRUE) {
    // loginページへリダイレクト
    redirect_login_page();
  }
}

// loginページへリダイレクト
function redirect_login_page() {
  $url_root = dirname($_SERVER["REQUEST_URI"]).'/';
   header('Location: '.(empty($_SERVER["HTTPS"]) ? "http://" : "https://"). $_SERVER['HTTP_HOST'] . $url_root . 'login.php');
   exit();
}

// 特殊文字をHTMLエンティティに変換する
function entity_str($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 特殊文字をHTMLエンティティに変換する(2次元配列の値)
function entity_assoc_array($assoc_array) {

  foreach ($assoc_array as $key => $value) {
    foreach ($value as $keys => $values) {
      // 特殊文字をHTMLエンティティに変換
      $assoc_array[$key][$keys] = entity_str($values);
    }
  }

  return $assoc_array;
}

// 数値かどうかチェック
function check_number($number) {

  if (preg_match('/\A\d+\z/', $number) === 1 ) {
    return true;
  } else {
    return false;
  }
}

// POSTデータを取得
function get_post_data($key) {

  $str = '';
  if (isset($_POST[$key]) === TRUE) {
   $str = $_POST[$key];
  }

  return $str;
}

// 前後の空白を削除
function trim_space($str) {
  return preg_replace('/\A[　\s]*|[　\s]*\z/u', '', $str);
}

// 購入の合計金額を取得
function get_sum_price($data) {

  $sum_price = 0;
  foreach ($data as $value) {
    $sum_price = $sum_price + $value['price'] * $value['amount'];
  }

  return $sum_price;
}

// DBに接続
//function get_db_connect($host, $username, $password, $dbname) {

function get_db_connect($dsn, $username, $password) {

  // MySQL用のDNS文字列
//  $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset=utf8';
  $dbh = null;

  try {
    // データベースに接続
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  } catch (PDOException $e) {
    throw $e;
  }

  return $dbh;
}


// カートにある商品情報の一覧を取得する
function get_cart_item_list($dbh, $user_id) {

  try {
    // SQL文を作成
    $sql = 'SELECT 
      ec_item_master.item_id, 
      ec_item_master.name, 
      ec_item_master.price,
      ec_item_master.img, 
      ec_cart.amount,
      ec_item_stock.stock
     FROM ec_cart 
     INNER JOIN ec_item_master ON ec_cart.item_id = ec_item_master.item_id
     INNER JOIN ec_item_stock ON ec_cart.item_id = ec_item_stock.item_id
     WHERE ec_item_master.status = 1' . ' AND user_id = ' . $user_id; 
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();
  } catch (PDOException $e) {
    throw $e;
  }

  return $rows;
}

?>
