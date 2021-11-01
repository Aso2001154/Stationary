<?php
session_start();
 $user_number = $_SESSION['user']['user_number'];
 try {
     require "data_base.php";
     $pdo = data_base();


    $sql = 'SELECT * FROM `user` WHERE `user_number` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_number]);
    $cnt = $stmt -> rowCount(); //取得件数
     if($cnt == 0) throw new PDOException('問題があり情報を取得できませんでした。');

 }catch (PDOException $ex){
    $login_message = $ex->getMessage(); //エラーメッセージ
 }
?>
<!DOCTYPE html>
<html>
 <head>
     <meta name="viewport" charset="utf-8">
     <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
     <link rel="stylesheet" href="css/information.css">
     <title>会員情報</title>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
     <script>
         let btnCheck = () =>{
             let beforeId = document.getElementById('id').value;
             let beforePass = document.getElementById('pass').value;
             let beforeCredit = document.getElementById('credit').value;
             if(beforeId.match(/[a-zA-Z0-9]{8}/) && beforePass.match(/[0-9]{4}/)){
                 if(beforeId.match(/[あ-ん-ー]/) || beforePass.match(/[あ-んa-zA-Z-ー]/) || beforeCredit.match(/[-]/)){
                     swal('誤入力があります');
                     return false;
                 }else{
                     return true;
                 }
             }else{
                 swal('文字数が足りない部分があります');
                 return false;
             }
         }
     </script>
 </head>
 <body>
 <header class="header">
     <form action="pencil.php" method="post">
         <?php echo '<input type="hidden" name="user_number" value="',$user_number,'">'; ?>
         <button type="submit" value="send" class="header_btn"><img src="img/header_name.png" alt="画像" class="header_name"></button>
         <p class="head_border"></p>
     </form>
 </header>
 <div class="container_info">
     <form action="information_out.php" method="post">
         <h1 class="title">information</h1>
         <h2 class="subtitle">id<span class="span">※制限:アルファベット/数字/ハイフンなし/8文字以上</span></h2>
         <?php
         foreach ($stmt as $row) {
             echo '<p><input type="text" class="text" id="id"name="user_id" value="',$row['user_id'], '"></p>';
             echo '<h2 class="subtitle">pass<span class="span">※制限:数字/ハイフンなし/4文字以上</span></h2>';
             echo '<p><input type="password" class="text" id="pass" name="user_pass" value="',$row['user_pass'], '"></p>';
             echo '<h2 class="subtitle">name</h2>';
             echo '<p><input type="text" class="text" id="name" name="user_name" value="',$row['user_name'], '"></p>';
             echo '<h2 class="subtitle">address</h2>';
             echo '<p><input type="text" class="text" id="address" name="user_address" value="',$row['user_address'], '"></p>';
             echo '<h2 class="subtitle" id="credit_number">credit number<span class="span">※制限:ハイフンなし</span></h2>';
             echo '<p><input type="number" class="number_text" id="credit" name="credit_number" value="',$row['credit_number'],'"></p>';
             echo '<button value="send" class="login" id="login_info" onsubmit="return btnCheck()" onclick="return btnCheck()">update</button>';
         }
         ?>
     </form>
     <form action="pencil.php" method="post"><button type="submit" value="send" class="top_info">top</button></form>
 </div>
 </body>
</html>
