<?php
session_start();
$user_number = $_SESSION['user']['user_number'];//データベースにデータを入れるまでこの行は消しておく
$text = @$_POST['text'];

try {

    require "data_base.php";
    $pdo = data_base();


    $sql = 'SELECT * FROM `merchandise` WHERE `merchandise_name` LIKE ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute(['%'.$text.'%']);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0) throw new PDOException('データベースに登録されていません。');

}catch (PDOException $ex){
    $login_message = $ex->getMessage(); //エラーメッセージ
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/search.css"><!--　top.cssに変える-->
    <title>検索結果</title>
</head>
<body>
<header class="header">
    <img src="img/header_name.png" alt="画像" class="header_name">
    <form action="logout.php" method="post"><p><button type="submit" class="btn">ログアウト</button></p></form>
    <form action="cart.php" method="post"><p><button type="submit" class="btn">カート</button></p></form>
    <form action="information.php" method="post"><p><button type="submit" class="btn">会員情報</button></p></form>
    <form action="history.php" method="post"><p><button type="submit" class="btn">履歴</button></p></form>
</header>
<div class="genre_list">
    <form action="pencil.php" method="post"><button type="submit" value="send" class="genre" id="genre1">シャーペン</button></form><!--開いているジャンルのボタンは押せなくする(disabled)-->
    <form action="eraser.php" method="post"><button type="submit" value="send" class="genre" id="genre2">消しゴム</button></form>
    <form action="pen.php" method="post"><button type="submit" value="send" class="genre" id="genre3">ボールペン</button></form>
    <form action="ruler.php" method="post"><button type="submit" value="send" class="genre" id="genre4">定　　規</button></form>
    <form action="office.php" method="post"><button type="submit" value="send" class="genre" id="genre5">事務用品</button></form><br><br>
</div>
    <p class="search_answer">検索結果</p>
<!--あとでここに商品詳細のフォームが一つでも行けるか試してみる-->
<div class="container">
    <?php
       $cnt = 0;
       $i=1;
       foreach ($stmt as $row){
            $genre_id = $row['genre_id'];
            $merchandise_id = $row['merchandise_id'];
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
        if($i%2!=0) {
            // iが奇数の場合
            echo '<form action="merchandise_detail.php" method="post" name="a_form', $cnt, '">';
            echo '<div class="border_vertical1">';
            echo '<input type="hidden" name="genre_id" value="', $genre_id, '">';
            echo '<input type="hidden" name="merchandise_id" value="', $row['merchandise_id'], '">';
            echo '<div class="merchandise_range">';
            echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form', $cnt, '.submit();"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></button>';
            echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form', $cnt, '.submit();">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</button></p>';
            echo '</div>';
            echo '</div>';
            echo '</form>';
        }else{
            // iが偶数の場合
            echo '<form action="merchandise_detail.php" method="post" name="a_form', $cnt, '">';
            echo '<div class="border_vertical1">';
            echo '<input type="hidden" name="genre_id" value="', $genre_id, '">';
            echo '<input type="hidden" name="merchandise_id" value="', $row['merchandise_id'], '">';
            echo '<div class="merchandise_range">';
            echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form', $cnt, '.submit();"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></button>';
            echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form', $cnt, '.submit();">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</button></p>';
            echo '</div>';
            echo '</div>';
            echo '</form>';
        }
        $i++;
        $cnt++;
       }
        ?>
</div>
</body>
</html>