<?php
// 購入完了
session_start();
$user_number = $_SESSION['user']['user_number'];
$box_genre_id = @$_POST['box_genre_id']; // 配列で商品のジャンルIDを受け取る
$box_merchandise_id = @$_POST['box_merchandise_id'];// 配列で商品のIDを受け取る
$box_quantity = @$_POST['box_quantity'];// 配列で商品の個数を受け取る
$box_price = @$_POST['box_price'];// 配列で商品の金額を受け取る
$all_price = @$_POST['sum']; // 合計金額を受け取る
$sq= 0; // history_idの保管場所として利用する
$flag = 0; // 在庫があるかどうかを判断するための変数
$count = count($box_genre_id);
$message = '<p>購入完了しました。</p>';
require "data_base.php";
$pdo = data_base();


//$genre_number = 0;　いらないかも
//$merchandise_number = 0;　いらないかも

for($i = 0;$i < $count;$i++) {
    $number = $pdo->prepare('select `stock` from `merchandise` where `genre_id` = ? and `merchandise_id` = ?');
    $number->execute([$box_genre_id[$i], $box_merchandise_id[$i]]);
    foreach ($number as $st) {
        if ($st['stock'] < 10) {
            $flag = 1;//フラグが1の場合は在庫が10未満の商品が含まれているという状態・フラグが0の場合は在庫が10より多くあるため購入可能
            //$genre_number = $st['genre_id'];//この情報を使ってどの商品が在庫10未満なのかを出力する
            //$merchandise_number = $st['merchandise_id'];//この情報を使ってどの商品が在庫10未満なのかを出力する
            $not_stock = $st['merchandise_name']; // 在庫がない商品名
            break 2;
        }
    }
}
date_default_timezone_get('Japan');
// カートに何個商品あるか　$countが0:カートに何もない時に行う　1:1個以上カートに商品がある時に行う
if($count!=0) {
    // $flagが0:問題なく購入処理を行う 1:在庫が10未満の商品がある
    if ($flag == 0) {
        // 在庫があれば購入履歴に追加や更新、削除を行う
        $sql = 'INSERT INTO `history_purchase`
            (`history_user_number`, `purchase_day`,`all_price`)
            VALUES(?,?,?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_number, date('Y-m-d'),$all_price]);
        $i = 1;
        $stmt1 = $pdo->prepare('select * from `history_purchase` where `history_user_number` = ?');
        $stmt1->execute([$user_number]);
        foreach ($stmt1 as $row) {
            if ($i == 1) {
                $sq = $row['history_id'];
            } else {
                if ($sq < $row['history_id']) {
                    $sq = $row['history_id'];
                }
            }
            $i++;
        }
        for ($i = 0; $i < $count; $i++) {
            $sql2 = 'INSERT INTO `history_detail`
            (`history_id`, `history_genre_id`, `history_merchandise_id`,`history_price`,`history_quantity`)
            VALUES(?,?,?,?,?)';
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([$sq, $box_genre_id[$i], $box_merchandise_id[$i], $box_price[$i], $box_quantity[$i]]);

            $sql3 = 'UPDATE `merchandise` SET `stock` = `stock` - ? WHERE `genre_id` = ? AND `merchandise_id` = ?';
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute([$box_quantity[$i], $box_genre_id[$i], $box_merchandise_id[$i]]);
        }
        // 購入されたらカートテーブルから購入された消費の情報を消す
        $sql4 = 'DELETE FROM `cart` WHERE `cart_user_number` = ?';
        $stmt4 = $pdo->prepare($sql4);
        $stmt4->execute([$user_number]);

    } else {
        // 在庫が少ないときに行われる
        $message = '<p>'.$not_stock.'の商品がありません。</p>';
    }
}else{
    $message = '<p>カートに何も入っていません。</p>';
}
//購入のテーブルに追加する
//カートのテーブルの購入されたものを削除する


?>
<!DOCTYPE html>
<html>
 <head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/style.css"><!-- home.php、login_in.php、login_out.php、signup_in.php、signup_out.php、logout.php、information.php、information_out.php、delete.php、comp_purchase.php、add_cart.php、 -->
 </head>
 <body>
 <header class="header">
     <img src="img/header_name.png" alt="画像" class="header_name">
 </header>
 <p><?php echo $message; // 在庫があるかないかを出力する?></p>
 <form action="pencil.php" method="post"><button type="submit" value="send" class="back_login">top</button></form>
 </body>
</html>
