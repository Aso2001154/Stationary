<?php
// カート
session_start();
$user_number = $_SESSION['user']['user_number'];
$sum = 0;
require "data_base.php";
$pdo = data_base();


$sql = $pdo->prepare('SELECT * FROM merchandise,cart WHERE merchandise.genre_id = cart.cart_genre_id
           AND merchandise.merchandise_id = cart.cart_merchandise_id
           AND cart.cart_user_number = ?');
    $sql -> execute([$user_number]);
    $cnt = $sql -> rowCount(); //取得件数
    if($cnt == 0){
        $message = 'カートに何も商品が入っていません。';
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/cart.css">
    <title>ショッピングカート</title>
</head>
<body>
<header class="header">
    <img src="img/header_name.png" alt="画像" class="header_name">
</header>
<h1 class="subtitle">ショッピングカート</h1>
<div class="container">

    <?php
    foreach ($sql as $row){
        $box_genre_id[] = $row['cart_genre_id'];
        $box_merchandise_id[] = $row['cart_merchandise_id'];
        $quantity = $row['cart_quantity'];
        $price = $row['price'];
        $sum = $sum + $quantity * $price;
        $box_quantity[] = $quantity;
        $box_price[] = $price;
        $merchandise_name = $row['merchandise_name'];

        /*if ($genre_id == 1) {
            $merchandise_img = ['', 'img/シャーペン１.png', 'img/シャーペン２.png', 'img/シャーペン３.png', 'img/シャーペン４.png', 'img/シャーペン５.png'];
        } else if ($genre_id == 2) {
            $merchandise_img = ['', 'img/消しゴム１.png', 'img/消しゴム２.png', 'img/消しゴム３.png', 'img/消しゴム４.png', 'img/消しゴム５.png'];
        } else if ($genre_id == 3) {
            $merchandise_img = ['', 'img/ボールペン１.png', 'img/ボールペン２.png', 'img/ボールペン３.png', 'img/ボールペン４.png', 'img/ボールペン５.png'];
        } else if ($genre_id == 4) {
            $merchandise_img = ['', 'img/定規１.png', 'img/定規２.png', 'img/定規３.png', 'img/定規４.png', 'img/定規５.png'];
        } else if ($genre_id == 5) {
            $merchandise_img = ['', 'img/事務用品１.png', 'img/事務用品２.png', 'img/事務用品３.png', 'img/事務用品４.png', 'img/事務用品５.png'];
        }*/
        echo '<form action="delete.php" method="post">';
        echo '<p><img src="',$row['image'],'" alt="商品画像" class="merchandise_img"></p>';
        echo '<p>',$merchandise_name,'</p>';
        echo '<p>',$price,'</p>';
        echo '<p>',$quantity,'</p>';
        echo '<input type="hidden" name="cart_user_number" value="',$user_number,'">';
        echo '<input type="hidden" name="cart_count" value="',$row['cart_count'],'">';
        echo '<button type="submit" value="send">delete</button>';
        echo '</form>';
        if(isset($message)) {
            echo '<p>', $message, '</p>';
        }

    }
    echo '<form action="comp_purchase.php" method="post">';
    echo '<p>金額合計：<span>',$sum,'</span></p>';
    echo '<input type="hidden" name="sum" value="',$sum,'">';
    if(isset($box_genre_id)) {
        for ($i = 0; $i < count($box_genre_id); $i++) {
            echo '<input type="hidden" name="box_genre_id[]" value="', $box_genre_id[$i], '">';
            echo '<input type="hidden" name="box_merchandise_id[]" value="', $box_merchandise_id[$i], '">';
            echo '<input type="hidden" name="box_quantity[]" value="', $box_quantity[$i], '">';
            echo '<input type="hidden" name="box_price[]" value="', $box_price[$i], '">';//配列で商品ID、等を渡す
        }
    }

    if(!isset($message)) {
        echo '<button type="submit" value="send">購入</button>';
    }
    echo'</form>';
    ?>

    <form action="pencil.php" method="post"><button type="submit" value="send">top</button></form>
</div>
</body>
</html>