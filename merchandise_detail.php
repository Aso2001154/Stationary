<?php
// 商品詳細
session_start();
    $genre_id = @$_POST['genre_id']; //ジャンルID
    $merchandise_id = @$_POST['merchandise_id'];//商品ID
    $merchandise_img = '';
    //ジャンルによって呼び出す画像を変える
    /*if($genre_id==1){
        $merchandise_img = ['','img/シャーペン１.png','img/シャーペン２.png','img/シャーペン３.png','img/シャーペン４.png','img/シャーペン５.png'];
    }else if($genre_id==2){
        $merchandise_img = ['','img/消しゴム１.png','img/消しゴム２.png','img/消しゴム３.png','img/消しゴム４.png','img/消しゴム５.png'];
    }else if($genre_id==3){
        $merchandise_img = ['','img/ボールペン１.png','img/ボールペン２.png','img/ボールペン３.png','img/ボールペン４.png','img/ボールペン５.png'];
    }else if($genre_id==4){
        $merchandise_img = ['','img/定規１.png','img/定規２.png','img/定規３.png','img/定規４.png','img/定規５.png'];
    }else if($genre_id==5){
        $merchandise_img = ['','img/事務用品１.png','img/事務用品２.png','img/事務用品３.png','img/事務用品４.png','img/事務用品５.png'];
    }*/
try {
    require "data_base.php";
    $pdo = data_base();


    $sql = 'SELECT * FROM `merchandise` WHERE `genre_id` = ? AND `merchandise_id` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$genre_id,$merchandise_id]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0) throw new PDOException('データベースに登録されていません。');

}catch (PDOException $ex){
    $login_message = $ex->getMessage(); //エラーメッセージ
}

?>
<!DOCTYPE html>
<html>
 <head>
     <meta name="viewport" charset="utf-8">
     <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
     <link rel="stylesheet" href="css/detail.css">
     <title>商品詳細</title>
 </head>
 <body>
  <header class="header">
     <img src="img/header_name.png" alt="画像" class="header_name">
  </header>
  <form action="add_cart.php" method="post">
  <?php
   foreach ($stmt as $row){
       echo '<h2>',$row['merchandise_name'],'</h2>';
       echo '<img src="',$row['image'],'" alt="商品画像">';
       echo '<p>商品価格：¥<span>',$row['price'],'</span></p>';
       echo '<p>商品詳細：</p>';
       echo '<p>',$row['merchandise_detail'],'</p>';
       echo '<p>個数：';
       echo '<span><select name="cart_quantity">';
       for($i = 1;$i <= 10;$i++) {
           echo '<option value="',$i,'">',$i,'</option>';
       }
       echo '</select></span>';
       echo '</p><br>';
   }
        echo '<input type="hidden" name="genre_id" value="',$genre_id,'">';
        echo '<input type="hidden" name="merchandise_id" value="',$merchandise_id,'">';
      ?>
      <button type="submit" value="send">add to cart</button>
  </form>
 <form action="pencil.php" method="post"><button type="submit" value="send">top</button></form>

 </body>
</html>
