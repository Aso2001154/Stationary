<?php //まだ
session_start();
$user_number = $_SESSION['user']['user_number'];
$period = '*';
$period = @$_POST['period'];
$sum = 0; // 合計金額
// 絞る日数をここで判断
date_default_timezone_set('Japan');
$day = date('Y-m-d',strtotime("-1 month"));
if($period==7){
    $day = date('Y-m-d',strtotime("-1 week"));
    $message = "1週間前";
}else if($period==31){
    $message = "1カ月前";
}else{
    $message = "全ての期間";
}
require "data_base.php";
$pdo = data_base();

   /* $sql = $pdo->prepare('select * from history_purchase where history_user_number = ?');
    $cnt = $sql->execute($user_number);
    $cnt = $sql->rowCount();
    if($cnt == 0) throw new PDOException('何も購入されてしていません。');
    $array = [];
    foreach ($sql as $row){
        array_push($array,$row['purchase_day']);
    }
    $sql1 = $pdo->prepare('SELECT * FROM merchandise m,history_detail h WHERE `history_id`= (select history_id from history_purchase where `history_user_number` = ?)
                         AND m.genre_id=h.history_genre_id AND m.merchanse_id=h.history_merchanse_id AND `puchase_day` <= ?');
    $sql1 -> execute([$user_number,$day]); //範囲を指定していないからする
   */

if($period == 7 || $period == 31){
    // 絞込みされた期間の履歴のデータを抽出する
    $sql = $pdo->prepare('select * from `history_purchase` where `history_user_number` = ? and `purchase_day` >= ?');
    $sql->execute([$user_number,$day]);
    $cnt = $sql->rowCount();
    if($cnt!=0) {
        $array = [];
        foreach ($sql as $row) {
            array_push($array, $row['purchase_day']);
        }
        $sql1 = $pdo->prepare('SELECT * FROM merchandise m,history_detail h WHERE h.history_id= (select history_id from history_purchase where `history_user_number` = ? AND `purchase_day` >= ?)
                         AND m.genre_id=h.history_genre_id AND m.merchandise_id=h.history_merchandise_id');
        //$sql = $pdo->prepare('SELECT * FROM merchandise,history_detail WHERE merchandise.genre_id = history.history_genre_id
        ////   AND merchandise.merchandise_id = history.history_merchandise_id
        //   AND history.history_user_number = ?');
        $sql1->execute([$user_number,$day]);
        $cnt = $sql1->rowCount(); //取得件数
        if ($cnt == 0) {
            $login_message = '何も購入されてしていません。';
        }
    }
}else {
    // 絞込みがなく全て履歴を抽出する
    $sql = $pdo->prepare('select * from history_purchase where history_user_number = ?');
    $sql->execute([$user_number]);
    $cnt = $sql->rowCount();
    if($cnt!=0) {
        $array = [];
        foreach ($sql as $row) {
            array_push($array, $row['purchase_day']);
        }
        $sql1 = $pdo->prepare('SELECT * FROM merchandise m,history_detail h WHERE h.history_id= (select history_id from history_purchase where `history_user_number` = ?)
                         AND m.genre_id=h.history_genre_id AND m.merchandise_id=h.history_merchandise_id');
        $sql1->execute([$user_number]);
        $cnt = $sql1->rowCount();
        if($cnt==0){
            $login_message = 'まだ何も購入されていません';
        }
    }
}


?>
<!DOCTYPE html>
<html>
 <head>
     <meta name="viewport" charset="utf-8">
     <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
     <link rel="stylesheet" href="css/history.css">
     <title>履歴</title>
 </head>
 <body>
 <header class="header">
         <a href="pencil.php"><img src="img/header_name.png" alt="画像" class="header_name"></a> <!--//セクションを使ってページの遷移をする-->
 </header>
 <h1 class="subtitle">履歴</h1>
 <h2><?php echo $message;?></h2>
 <form action="history_genre.php" method="post">
     <select name="period" class="period">
         <option value="*">すべて</option>
         <option value="7">1週間前</option>
         <option value="31">1カ月前</option>
     </select>
     <button type="submit" value="send">絞り込み</button>
 </form>
 <div class="container">
     <?php
     $i = 0;
     $history_id = 0;
     if(isset($sql1)) {
         foreach ($sql1 as $row) {
             $genre_id = $row['history_genre_id'];
             $merchandise_id = $row['history_merchandise_id'];
             $merchandise_name = $row['merchandise_name'];
             if ($i == 0) {
                 $history_id = $row['history_id'];
             } else {
                 if ($history_id == $row['history_id']) {
                     $i--; // 同じ時に購入した場合は-1をして同じ配列の値の日時を出力するようにする処理
                 }
             }
             $sum = $sum + $row['history_quantity'] * $row['history_price'];

             echo '<p><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></p>';
             echo '<p>商品名：<span>', $row['merchandise_name'], '</span></p>';
             echo '<p>価格：<span>', $row['history_price'], '</span></p>'; // データベースのhistory_detailのpriceをhistory_priceに変える。データベース定義書も
             echo '<p>個数：<span>', $row['history_quantity'], '</span></p>';
             echo '<p>購入日時：<span>', $array[$i], '</span></p>';
             $i++; //history_idが変わればiにプラスする　(日付が変わるから)
         }
     }
     echo '<p>金額合計：<span>',$sum,'</span></p>';
     ?>
     <form action="pencil.php" method="post">
         <button type="submit" value="send">top</button>
     </form>

 </div>
 </body>
</html>
