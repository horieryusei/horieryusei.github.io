<?php

// ユーザ情報の存在チェック
function exist_user($dbh, $user_name) {

  $exist_flag = false;  // ユーザが存在している場合はtrue

  try {
    // SQL文を作成
    $sql = 'SELECT *
        FROM ec_user
        WHERE user_name = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();

    if (count($rows) === 1) {
     $exist_flag = true;
    }

  } catch (PDOException $e) {
    throw $e;
  }

  return $exist_flag;
}

// ユーザ情報を登録する
function insert_user($dbh, $user_name, $password, $date) {

  try {
    // SQL文を作成
    $sql = 'INSERT INTO ec_user (user_name, password, create_date, update_date) VALUES (?, ?, ?, ?)';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $password, PDO::PARAM_STR);
    $stmt->bindValue(3, $date, PDO::PARAM_STR);
    $stmt->bindValue(4, $date, PDO::PARAM_STR);
    // SQLを実行
    $stmt->execute();

  } catch (PDOException $e) {
    throw $e;
  }

}

?>
