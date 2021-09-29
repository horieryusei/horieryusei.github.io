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
  </style>
<head>
  <meta charset="utf-8">
  <title>ログインページ</title>
  <link type="text/css" rel="stylesheet" href="./css/common.css">
</head>
<body>
  <div class="content">
    <div class="login">
      <form method="post" action="./login.php">
        <div><input type="text" name="user_name" placeholder="ユーザー名"></div>
        <div><input type="password" name="password" placeholder="パスワード">
        <div><input type="submit" value="ログイン">
<?php foreach ($err_msg as $value) { ?>
  <p class="err-msg"><?php print $value; ?></p>
<?php } ?>
      </form>
      <div class="account-create">
        <a href="./register.php">ユーザーの新規作成</a>
      </div>
    </div>
  </div>
</body>
</html>
