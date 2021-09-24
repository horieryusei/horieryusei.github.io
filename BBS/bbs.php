<?php
//$filenameにテキストを代入
$filename = './review.txt';
$errmsg = [];
//ログにファイルを書き込み(fwrite)
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  if (isset($_POST['my_name']) === TRUE) {
    $my_name = $_POST['my_name'];
    if ($my_name === '') {
      $errmsg[]= '名前を入力してください';
    } elseif(mb_strlen($my_name)> 20 ){
      $errmsg[]= '名前は20文字以内で入力してください';
    }
  }
  if (isset($_POST['comment']) === TRUE) {
    $comment = $_POST['comment'];
    if ($comment === '') {
      $errmsg[]= 'コメントを入力してください';
    } elseif(mb_strlen($comment)> 100 ){
      $errmsg[]= 'コメントは100文字以内で入力してください';
    }
  }
  if(count($errmsg)=== 0){
//ログに日付を書き込み(fwrite)
    $log = date('-Y-m-d H:i:s');
    if (($fp = fopen($filename, 'a')) !== FALSE) {
      if (fwrite($fp, $my_name.":\t".$comment.":\t".$log."\n") === FALSE) {
        print 'アクセスログ書き込み失敗: ' . $filename;
      }
    }
  }
}

//ログのファイルをhtmlへ読み込み(fgets)
$data = array();
if (is_readable($filename) === TRUE) {
  if(($fp = fopen($filename, 'r')) !== FALSE) {
    while (($tmp = fgets($fp)) !== FALSE) {
      $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
    }
    $data = array_reverse($data);
    fclose($fp);
  }
} else {
  $data[] = 'ファイルがありません';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>
     <ul>
    <?php foreach ($errmsg as $read) { ?>
  <li><?php print $read; ?></li>
<?php } ?>
</ul>
     <form method="post">
    <a>名前:</a><input type="text" name="my_name">
    <a>ひとこと:</a><input type="text" name="comment">
    <input type="submit" name="submit" value="送信">
     </form>
     <ul>
    <?php foreach ($data as $read) { ?>
  <li><?php print $read; ?></li>
<?php } ?>
</ul>
</body>
</html>
