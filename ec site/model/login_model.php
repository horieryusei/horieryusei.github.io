<?php

// ユーザ情報の取得
function get_user($dbh, $user_name, $password) {


  try {
    // SQL文を作成
    $sql = 'SELECT * FROM ec_user WHERE user_name = ? AND password = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $password, PDO::PARAM_STR);
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
