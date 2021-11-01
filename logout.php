<?php
session_start();
unset($_SESSION['user']);
?>
<!DOCTYPE html>
<html>
 <head>
     <meta name="viewport" charset="utf-8">
     <title>ログアウト</title>
     <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
     <link rel="stylesheet" href="css/information.css">
 </head>
 <body>
 <header class="header">
     <p class="head_img"><img src="img/header_name.png" alt="画像" class="header_name"></p>
     <p class="head_border"></p>
 </header>
        <p class="login_message">ログアウトしました。</p>
        <a href="pencil.php" class="back_login">top</a>
 </body>
</html>