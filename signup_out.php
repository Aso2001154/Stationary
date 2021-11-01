<?php
$user_id = @$_POST['user_id'];
$user_pass = @$_POST['user_pass'];
$user_name = @$_POST['user_name'];
$user_address = @$_POST['user_address'];
$credit_number = @$_POST['credit_number'];
$form_link = 'signup_in.php';
$from_btn_message = 'signup';
$message = '既に登録されているidかpassです。';

require "data_base.php";
$pdo = data_base();


$sql = 'SELECT * FROM `user` WHERE `user_id` = ? AND `user_pass` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_id,$user_pass]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt > 0){ $login_message = '登録に失敗しました';}

    $sql1 = 'INSERT INTO `user`
            (`user_id`, `user_pass`, `user_name`, `user_address`,`credit_number`)
            VALUES(?,?,?,?,?)';
    $stmt1 = $pdo->prepare($sql1);
    $stmt1 -> execute([$user_id,$user_pass,$user_name,$user_address,$credit_number]);

    $login_message = '登録に成功しました';
    $message = '';
    $form_link = 'login_in.php';
    $from_btn_message = 'login';

?>
<!DOCTYPE html>
<html la="ja">
<head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/information.css">
    <title>ログイン認証結果</title>
</head>
<body>
<header class="header">
    <img src="img/header_name.png" alt="画像" class="header_name">
</header>
    <?php
    echo '<form action="',$form_link,'" method="post">';
    echo '<h2>',$login_message,'</h2>';
    echo '<p>',$message,'</p>';
    echo '<button type="submit" value="send" class="back_login">',$from_btn_message,'</button>';
    echo '</form>';
    ?>
</body>
</html>
