<?php //とりあえず終わり　購入のSQlをやる
// ランキング
session_start();
$user_number = $_SESSION['user']['user_number'];
try {
    require "data_base.php";
    $pdo = data_base();

    // select history_genre_id,history_merchandise_id,sum(history_quantity)as quantity
    //from history_detail
    //group by history_genre_id and history_merchandise_id
    //order by quantity desc
    $sql = 'SELECT merchandise.merchandise_name,merchandise.price,merchandise.image,ranking.quantity
            FROM (select history_genre_id,history_merchandise_id,sum(history_quantity)as quantity 
                  from history_detail 
                  group by history_genre_id,history_merchandise_id) ranking,merchandise
            WHERE ranking.history_genre_id=merchandise.genre_id AND ranking.history_merchandise_id=merchandise.merchandise_id
            ORDER BY ranking.quantity DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sql1 =
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0) throw new PDOException('商品がありません');
    $i=1;
    $difference=0;
    $number=0;
}catch (PDOException $ex){
    $login_message = $ex->getMessage(); //エラーメッセージ
}
?>
<!DOCTYPE html>
<html>
 <head>
     <meta name="viewport" charset="utf-8">
     <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
     <link rel="stylesheet" href="css/ranking.css"><!-- pencil.php、eraser.php、pen.php、ruler.php、office.php-->
     <title>ランキング</title>
 </head>
 <body>
 <h2>ランキング</h2>
 <form action="ranking_narrowing.php" method="post">
 <select id="pet-select">
     <option value="シャーペン">シャーペン</option>
     <option value="消しゴム">消しゴム</option>
     <option value="ボールペン">ボールペン</option>
     <option value="定規">定規</option>
     <option value="事務用品">事務用品</option>
 </select>
     <button type="submit" value="send" class="btn">絞り込み</button>
 </form>
 <?php
 foreach ($stmt as $row){
     if($i==1) {
         $number = $row['quantity'];
     }else{
         if($number==$row['quantity']){
             $i--; // 前と同じ個数だった場合同じ順位にする
             $difference++; // この変数を利用して差を求めている
         }
     }
     // ここに問題がある　 $numberに1種類目の商品の個数が入ったままになってループしてしまっている　だから一生同じ順位が出ない

     if($number==$row['quantity']){
         echo '<p>',$i,'位</p>';
     }else{
         $i = $i + $difference;
        echo '<p>',$i,'位</p>';
        $difference=0;
     }
     echo '<p><img src="',$row['image'],'" alt="商品画像"></p>';
     echo '<p>商品名：',$row['merchandise_name'],'</p>';
     echo '<p>価格：',$row['price'],'</p>';
     $number = $row['quantity'];
     $i++;
 }
 ?>
 <form action="pencil.php" method="post"><button type="submit" value="send">top</button></form>
 </body>
</html>
