<!DOCTYPE html>
<html lang="ja">
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
  <title>ショッピングカートページ</title>
  <link type="text/css" rel="stylesheet" href="./css/common.css">
</head>
<body>
  <header>
    <div class="header-box">
      <a class="nemu" href="./logout.php">ログアウト</a>
      <a href="./cart.php" class="cart"></a>
      <p class="nemu">ユーザー名：<?php print $_SESSION['user_name']; ?></p>
    </div>
  </header>
  <div class="content">
    <h1 class="title">ショッピングカート</h1>
<?php foreach ($err_msg as $value) { ?>
  <p><?php print $value; ?></p>
<?php } ?>

<?php if (empty($result_msg) !== TRUE) { ?>
    <p class="success-msg"><?php print $result_msg; ?></p>
<?php } ?>
    <div class="cart-list-title">
      <span class="cart-list-price">価格</span>
      <span class="cart-list-num">数量</span>
    </div>
    <ul class="cart-list">
<?php foreach ($data as $value)  { ?>
      <li>
        <div class="cart-item">
          <img class="cart-item-img" src="<?php print $IMG_DIR . $value['img']; ?>">
          <span class="cart-item-name"><?php print $value['name']; ?></span>
          <form class="cart-item-del" action="./cart.php" method="post">
            <input type="submit" value="削除">
            <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
            <input type="hidden" name="sql_kind" value="delete_cart">
          </form>
          <span class="cart-item-price">¥ <?php print $value['price']; ?></span>
          <form class="form_select_amount" id="form_select_amount<?php print $value['item_id']; ?>" action="./cart.php" method="post">
            <input type="text" class="cart-item-num2" min="0" name="select_amount" value="<?php print $value['amount']; ?>">個&nbsp;<input type="submit" value="変更する">
            <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
            <input type="hidden" name="sql_kind" value="change_cart">
          </form>
        </div>
      </li>
<?php } ?>
    </ul>
    <div class="buy-sum-box">
      <span class="buy-sum-title">合計</span>
      <span class="buy-sum-price">¥<?php print $sum_price; ?></span>
    </div>
    <div>
      <form action="./finish.php" method="post">
        <input class="buy-btn" type="submit" value="購入する">
      </form>
    </div>
  </div>
</body>
</html>
