<?php
session_start();
$user_number = $_SESSION['user']['user_number'];
$user_id = @$_POST['user_id'];
$user_pass = @$_POST['user_pass'];
$user_name = @$_POST['user_name'];
$user_address = @$_POST['user_address'];
$credit_number = @$_POST['credit_number'];
$form_link = 'pencil.php';
$from_btn_message = 'top';
$message = '既に登録されているIDかパスワードです。';

require "data_base.php";
$pdo = data_base();


$sql = 'SELECT * FROM `user` WHERE `user_id` = ? AND `user_pass` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_id,$user_pass]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt > 0) {
        $login_message = '登録に失敗しました';

    }else {

        $sql1 = 'UPDATE `user` SET `user_id` = ?,`user_pass` = ?,`user_name` = ?,`user_address` = ?,`credit_number` = ?
            WHERE `user_number` = ?';
        $stmt = $pdo->prepare($sql1);
        $stmt->execute([$user_id, $user_pass, $user_name, $user_address, $credit_number, $user_number]);
        $message = '';
        $login_message = '登録に成功しました';
        $form_link = 'login_in.php';
        $from_btn_message = 'login';
        unset($_SESSION['user']); // 更新に成功したら今ログインしているIDからログアウトする
    }

?>
<!DOCTYPE html>
<html>
 <head>
     <meta name="viewport" charset="utf-8">
     <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
     <link rel="stylesheet" href="css/information.css">
     <title>会員情報認証画面</title>
 </head>
 <body>
 <header class="header">
     <p class="head_img"><img src="img/header_name.png" alt="画像" class="header_name"></p>
     <p class="head_border"></p>
 </header>
     <?php
     echo '<form action="',$form_link,'" method="post">';
     echo '<h2 class="login_message">',$login_message,'</h2>';
     echo '<p class="message">',$message,'</p>';
     echo '<button type="submit" value="send" class="back_login">',$from_btn_message,'</button>';
     echo '</form>';
     ?>
 </body>
</html>
