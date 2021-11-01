<?php
//　カートに追加
session_start();
$user_number = $_SESSION['user']['user_number'];
$cart_quantity = @$_POST['cart_quantity'];
$cart_genre_id = @$_POST['genre_id'];
$cart_merchandise = @$_POST['merchandise_id'];
$from_btn_message = 'top';
$su = 0;

require "data_base.php";
$pdo = data_base();


$sql = 'SELECT * FROM `cart` WHERE `cart_user_number` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_number]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0){
        $su = 1;
    }else {
        foreach ($stmt as $row){
            $su = $row['cart_count'];
        }
        $su++;
    }
        $sql1 = 'INSERT INTO `cart`
            (`cart_user_number`, `cart_count`, `cart_genre_id`, `cart_merchandise_id`,`cart_quantity`)
            VALUES(?,?,?,?,?)';
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute([$user_number, $su, $cart_genre_id, $cart_merchandise,$cart_quantity]);
        $login_message = 'カートに追加しました';

?>
<!DOCTYPE html>
<html la="ja">
<head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/style.css">
    <title>カートに追加</title>
</head>
<body>
<header class="header">
    <img src="img/header_name.png" alt="画像" class="header_name">
</header>
<form action="pencil.php" method="post">
<?php echo '<h2>',$login_message,'</h2>'; // カートに追加メッセージ
echo '<button type="submit" value="send" class="back_login">',$from_btn_message,'</button>'; ?>
</form>
</body>
</html>
