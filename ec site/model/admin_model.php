<?php

// ユーザ情報を登録する
function insert_item($dbh, $name, $price, $img, $date, $status) {

  try {
    // SQLを作成
    $sql = 'INSERT INTO ec_item_master (name, price, img, create_date, update_date, status)
      VALUES (?, ?, ?, ?, ?, ?)';

    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $name,    PDO::PARAM_STR);
    $stmt->bindValue(2, $price, PDO::PARAM_INT);
    $stmt->bindValue(3, $img, PDO::PARAM_STR);
    $stmt->bindValue(4, $date, PDO::PARAM_STR);
    $stmt->bindValue(5, $date, PDO::PARAM_STR);
    $stmt->bindValue(6, $status, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();

  } catch (PDOException $e) {
    throw $e;
  }
}

// 在庫情報を登録する
function insert_item_stock($dbh, $item_id, $stock, $date) {

  try {
    // SQL文を作成
    $sql = 'INSERT INTO ec_item_stock (item_id, stock, create_date, update_date)
      VALUES (?, ?, ?, ?)';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $stock, PDO::PARAM_INT);
    $stmt->bindValue(3, $date, PDO::PARAM_STR);
    $stmt->bindValue(4, $date, PDO::PARAM_STR);
    // SQLを実行
    $stmt->execute();

  } catch (PDOException $e) {
    throw $e;
  }

}

// 在庫数の更新
function upadte_item_stock($dbh, $item_id, $update_stock, $date) {

  try {
    // SQL文を作成
    $sql = 'UPDATE ec_item_stock SET stock = ?, update_date = ? WHERE item_id = ?';

    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $update_stock, PDO::PARAM_INT);
    $stmt->bindValue(2, $date, PDO::PARAM_STR);
    $stmt->bindValue(3, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();

  } catch (PDOException $e) {
    throw $e;
  }

}

// ステータスをの更新
function upadte_item_status($dbh, $item_id, $change_status, $date) {

  try {
    // SQL文を作成
    $sql = 'UPDATE ec_item_master SET status = ?, update_date = ? WHERE item_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $change_status, PDO::PARAM_INT);
    $stmt->bindValue(2, $date, PDO::PARAM_STR);
    $stmt->bindValue(3, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();

  } catch (PDOException $e) {
    throw $e;
  }

}

// 商品情報を削除
function delete_item($dbh, $item_id) {

  try {
    // SQL文を作成
    $sql = 'DELETE from ec_item_master WHERE item_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();

  } catch (PDOException $e) {
    throw $e;
  }

}

// 在庫情報を削除
function delete_item_stock($dbh, $item_id) {

  try {
    // SQL文を作成
    $sql = 'DELETE from ec_item_stock WHERE item_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();

  } catch (PDOException $e) {
    throw $e;
  }

}

// 商品情報を取得
function get_item_list_all($dbh) {

  try {
    // SQL文を作成
    $sql = 'SELECT ec_item_master.item_id, ec_item_master.name, ec_item_master.price,
        ec_item_master.img, ec_item_master.status, ec_item_stock.stock
      FROM ec_item_master JOIN ec_item_stock
      ON  ec_item_master.item_id = ec_item_stock.item_id';

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
