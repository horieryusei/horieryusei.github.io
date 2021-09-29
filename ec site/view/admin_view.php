<!DOCTYPE html>
<html lang="ja">
  <header>
    <a style="text-decoration:none"  href ="itemlist.php"><font color = "#000000";><img src= "アイコン１.png"><font size = 36px>DIYSHOP</font><img src= "アイコン２.png"></a>
    <a href="cart.php"><img src = "カート.png"></a>
  </header>
  <style>
    header {
      background-color:#a0522d;
      text-align:center;
    }
    section {
      margin-bottom: 20px;
      border-top: solid 1px;
    }
    table {
      width: 860px;
      border-collapse: collapse;
    }
    table, tr, th, td {
      border: solid 1px;
      padding: 10px;
      text-align: center;
    }
    caption {
      text-align: left;
    }
    .text_align_right {
      text-align: right;
    }
    .name_width {
      width: 100px;
    }
    .input_text_width {
      width: 60px;
    }
    .status_false {
      background-color: #A9A9A9;
    }
    .img_size {
      height: 125px;
    }
    .success-msg {
      color: blue;
    }
    .err-msg {
      color: #FF0000;
    }
  </style>
<head>
  <meta charset="utf-8">
  <title>商品管理ページ</title>
  <link type="text/css" rel="stylesheet" href="./css/admin.css">
</head>
<body>
  <h1>DIYSHOP 管理ページ</h1>
  <div>
    <a href="./admin_user.php" target="_blank">ユーザ管理ページ</a>
  </div>
<?php if (empty($result_msg) !== TRUE) { ?>
  <p class="success-msg"><?php print $result_msg; ?></p>
<?php } ?>
<?php foreach ($err_msg as $value) { ?>
  <p class="err-msg"><?php print $value; ?></p>
<?php } ?>
  <section>
    <h2>商品の登録</h2>
    <form method="post" enctype="multipart/form-data">
      <div><label>商品名: <input type="text" name="new_name" value=""></label></div>
      <div><label>値　段: <input type="text" name="new_price" value=""></label></div>
      <div><label>個　数: <input type="text" name="new_stock" value=""></label></div>
      <div><label>商品画像:<input type="file" name="new_img"></label></div>
      <div><label>ステータス:
        <select name="new_status">
          <option value="0">非公開</option>
          <option value="1" selected>公開</option>
        </select>
        </label>
      </div>
      <input type="hidden" name="sql_kind" value="insert">
      <div><input type="submit" value="商品を登録する"></div>
    </form>
  </section>
  <section>
    <h2>商品情報の一覧・変更</h2>
    <table>
      <tr>
        <th>商品画像</th>
        <th>商品名</th>
        <th>価　格</th>
        <th>在庫数</th>
        <th>ステータス</th>
        <th>操作</th>
      </tr>
<?php foreach ($data as $value)  { ?>
<?php if ($value['status'] === '1') { ?>
      <tr>
<?php } else { ?>
      <tr class="status_false">
<?php } ?>
        <form method="post">
          <td><img class="img_size" src="<?php print $IMG_DIR . $value['img']; ?>"></td>
          <td class="name_width"><?php print $value['name']; ?></td>
          <td class="text_align_right"><?php print $value['price']; ?>円</td>
          <td><input type="text"  class="input_text_width text_align_right" name="update_stock" value="<?php print $value['stock']; ?>">個&nbsp;&nbsp;<input type="submit" value="変更する"></td>
          <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
          <input type="hidden" name="sql_kind" value="update">
        </form>
        <form method="post">
<?php if ($value['status'] === '1' ) { ?>
          <td><input type="submit" value="公開 → 非公開にする"></td>
          <input type="hidden" name="change_status" value="0">
<?php } else { ?>
          <td><input type="submit" value="非公開 → 公開にする"></td>
          <input type="hidden" name="change_status" value="1">
<?php } ?>
          <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
          <input type="hidden" name="sql_kind" value="change">
        </form>
        <form method="post">
          <td><input type="submit" value="削除する"></td>
          <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
          <input type="hidden" name="sql_kind" value="delete">
        </form>
      <tr>
<?php } ?>
    </table>
  </section>
</body>
</html>
