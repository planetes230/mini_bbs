<?php
require('dbconnect.php');

session_start();

if($_COOKIE['email'] != '') {
  $_POST['email'] = $_COOKIE['email'];
  $_POST['password'] = $_COOKIE['password'];
  $_POST['save'] ='on';
}

if(!empty($_POST)) {
    //ログインの処理
    if($_POST['email'] != '' && $_POST['password'] != '') {
       $sql = sprintf('SELECT * FROM members WHERE email="%s" AND password="%s"',
            mysqli_real_escape_string($db,$_POST['email']),
            mysqli_real_escape_string($db,sha1($_POST['password']))
    );
    $record = mysqli_query($db,$sql) or die(mysqli_error($db));
    if($table = mysqli_fetch_assoc($record)) {
      //ログイン成功
      $_SESSION['id'] = $table['id'];
      $_SESSION['time'] = time();

      //ログイン情報を記録する
      if($_POST['save'] == 'on') {
        setcookie('email',$_POST['email'],time() +60*60*24*14);
        setcookie('password',$_POST['password'],time()+60*60*24*14);
      }
      header('Location: index.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログイン画面</title>
</head>

<body>
<div id="wrap">
<div id="head">
<h1>ログイン画面</h1>
</div>
<div id="content">
<div id="lead">
<p>メールアドレスとパスワードを記入してログインしてください。</p>
<p>入会手続きがまだの方はこちらからどうぞ。</p>
<p>&raquo;<a href="join/">入会手続きをする</a></p>
</div>
<form action="" method="post">
  <dl>
    <dt>メールアドレス</dt>
    <dd>
      <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email']);?>" />
      <?php if ($error['login'] == 'blank') : ?>
      <p class="error">*　メールアドレスとパスワードをご記入ください。</p>
      <?php endif; ?>
      <?php if ($error['login'] == 'failed') : ?>
      <p class="error">*　ログインに失敗しました。正しくご記入ください。</p>
      <?php endif; ?>
    </dd>
    <dt>パスワード</dt>
    <dd>
      <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password']); ?>" />
    </dd>
    <dt>ログイン情報の記録</dt>
    <dd>
      <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
    </dd>
  </dl>
  <div><input type="submit" value="ログインする" />
</div>
</form>
</div>
<div id="foot">
<p><img src="/images/txt_copyright.png" width="136" height="15" alt="(C) H2O SPACE, Mynavi" /></p>
</div>

</div>
</body>
</html>
