<!DOCTYPE html>
<html lang="ja">
  <header>
    <a class="header_main" style="text-decoration:none"  href ="itemlist.php"><font color = "#000000";><img src= "アイコン１.png"><font size = 36px>DIYSHOP</font><img src= "アイコン２.png"></a>
    <a class="header_cart" href="cart.php"><img src = "カート.png"></a>
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
  <title>ユーザ管理ページ</title>
  <link type="text/css" rel="stylesheet" href="./css/admin.css">
</head>
<body>
<?php foreach ($err_msg as $value) { ?>
  <p class="err-msg"><?php print $value; ?></p>
<?php } ?>
  <h1>DIYSHOP 管理ページ</h1>
  <div>
    <a href="./admin.php" target="_blank">商品管理ページ</a>
  </div>
  <h2>ユーザ情報一覧</h2>
  <table>
    <tr>
      <th>ユーザID</th>
      <th>登録日</th>
    </tr>
<?php foreach ($data as $value)  { ?>
    <tr>
      <td class="name_width"><?php print $value['user_name']; ?></td>
      <td ><?php print $value['create_date']; ?></td>
    </tr>
<?php } ?>
  </table>
</body>
</html>
