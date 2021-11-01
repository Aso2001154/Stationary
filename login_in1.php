<?php
session_start();
unset($_SESSION['user']);
$user_id = @$_POST['user_id'];
$user_pass = @$_POST['user_pass'];
$branch = 0; //IDとパスワードがデータベースにある場合
$login_flg = 1;

require "data_base.php";
$pdo = data_base();

    $sql = 'SELECT * FROM `user` WHERE `user_id` = ? AND `user_pass` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_id,$user_pass]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0){ $branch = 1; //IDとパスワードがデータベースにない場合
    }else {foreach ($stmt as $row) {
            $_SESSION['user'] = ['user_number'=>$row['user_number'],'user_name'=>$row['user_name']];
          }
          $login_flg = 0;
    }
    if($login_flg==1){
        $message = "ID、パスワードを間違っています。";
    }else{
        $message = "ログインに成功しました。";
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/information.css"><!-- login_in.php、login_out.php、※signup_in.php、signup_out.php、logout.php、※information.php、information_out.php、delete.php、comp_purchase.php、add_cart.php、 -->
    <title>ログイン</title>
</head>
 <body>
 <header class="header">
     <p class="head_img"><img src="img/header_name.png" alt="画像" class="header_name"></p>
     <p class="head_border"></p>
 </header>
 <?php if($branch==0){
     echo '<form action="pencil.php" method="post">';
     echo '<p class="login_message">',$message,'</p>';
     echo '<p class="message">沢山買いましょう!!</p>';
     echo '<button type="submit" class="back_login">top</button>';
     echo '</form>';
     }else{
     echo '<div class="container" style="height: 550px;">';
     echo '<form action="login_in.php" method="post">';
     echo '<h1 class="title">login</h1>';
     echo '<p class="error">',$message,'</p>';
     echo '<h2 class="subtitle">id</h2>';
     echo '<p><input type="text" class="text" name="user_id"></p>';
     echo '<h2 class="subtitle">pass</h2>';
     echo '<p><input type="text" class="text" name="user_pass"></p>';
     echo '<input type="hidden" name="login_flg" value="1">';
     echo '<button type="submit" class="login">login</button>';
     echo '</form>';
     echo '</div>';
     echo '<p class="link_a" style="margin-top: 50px;"><a href="signup_in.php">sign up</a></p>';
     }
 ?>
 </body>
</html>
