<?php
session_start();
$genre_id = 1;
$login_message = '';
if(@$_POST['genre_id']){
    $genre_id = @$_POST['genre_id']; //ジャンルによって出力する商品を変える
}
$action_login = '<form action="login_in.php" method="post">';
$action_cart = '<form action="cart.php" method="post">';
$action_information = '<form action="information.php" method="post">';
$action_history = '<form action="history.php" method="post">';
$form_login = '<p><button type="submit" class="btn">ログイン</button></p>';
$form_cart = '<p><button type="submit" class="btn" onclick="btnCheck()">カート</button></p>';
$form_information = '<p><button type="submit" class="btn" onclick="btnCheck()">会員情報</button></p>';
$form_history = '<p><button type="submit" class="btn" onclick="btnCheck()">履歴</button></p>';
if(isset($_SESSION['user'])){
    //if($user_number > 0){ //0より大きかったらログインしている状態・0だった場合ログインしていない状態
    $action_login = '<form action="logout.php" method="post">';
    $form_login1 = '<p><button type="submit" class="btn">ログアウト</button></p>';
    $form_cart1 = '<p><button type="submit" class="btn">カート</button></p>';
    $form_information1 = '<p><button type="submit" class="btn">会員情報</button></p>';
    $form_history1 = '<p><button type="submit" class="btn">履歴</button></p>';
}

try {
    require "data_base.php";
    $pdo = data_base();
    $sql = 'SELECT * FROM `merchandise` WHERE `genre_id` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$genre_id]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0) throw new PDOException('商品がありません');
    $i=1;
    if($genre_id==1){ $img='シャーペン';}
    else if($genre_id==2){ $img='消しゴム';}
    else if($genre_id==3){ $img='ボールペン';}
    else if($genre_id==4){ $img='定規';}
    else if($genre_id==5){ $img='事務用品';}

}catch (PDOException $ex){
    $login_message = $ex->getMessage(); //エラーメッセージ
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/top.css"><!-- pencil.php、eraser.php、pen.php、ruler.php、office.php-->
    <title>シャーペン(ジャンル)</title>
    <script>
        let btnCheck = () =>{
            alert("ログインしないとクリックをすることはできません。");
        }
    </script>
</head>
<body>
<header class="header">
    <div class="list"><img src="img/header_name.png" alt="画像" class="header_name" style="border-right: 2px solid black;padding-right: 50px;"></div>
    <?php echo $action_login;?><!--ログインしていたらログアウトにとぶ-->
    <?php if(isset($form_login1)){echo $form_login1;}echo '</form>';?>

    <?php echo $action_login;?><!--ログインしていなかったらログインのボタンでログイン画面にとぶ-->
    <?php if(!isset($form_login1)){echo $form_login;}echo '</form>';?>

    <?php echo $action_cart;?><!--ログインしていたらカートにとぶ-->
    <?php if(isset($form_cart1)){echo $form_cart1;}echo '</form>';?>

    <?php echo $action_login;?><!--ログインしていなかったらカートのボタンでログイン画面にとぶ-->
    <?php if(!isset($form_cart1)){echo $form_cart;}echo '</form>';?>

    <?php echo $action_information;?><!--ログインしていたら会員情報にとぶ-->
    <?php if(isset($form_information1)){echo $form_information1;}echo '</form>';?>

    <?php echo $action_login;?><!--ログインしていなかったら会員情報のボタンでログイン画面にとぶ-->
    <?php if(!isset($form_information1)){echo $form_information;}echo '</form>';?>

    <?php echo $action_history;?><!--ログインしていたら履歴にとぶ-->
    <?php if(isset($form_history1)){echo $form_history1;}echo '</form>';?>

    <?php echo $action_login;?><!--ログインしていなかったら履歴のボタンでログイン画面にとぶ-->
    <?php if(!isset($form_history1)){echo $form_history;}echo '</form>';?>
</header>
<div class="genre_list">
    <form action="pencil.php" method="post"><input type="hidden" value="1" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre1">シャーペン</button></p></form><!--開いているジャンルのボタンは押せなくする(disabled)-->
    <form action="pencil.php" method="post"><input type="hidden" value="2" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre2">消しゴム</button></p></form>
    <form action="pencil.php" method="post"><input type="hidden" value="3" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre3">ボールペン</button></p></form>
    <form action="pencil.php" method="post"><input type="hidden" value="4" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre4">定　　規</button></p></form>
    <form action="pencil.php" method="post"><input type="hidden" value="5" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre5">事務用品</button></p></form>
    <form action="ranking.php" method="post"><li class="genre_name"><button type="submit" value="send" class="genre" id="genre6">ランキング</button></li></form><br><br>
</div>
<br>
<?php
if(isset($form_login1)){
    echo '<form action="search.php" method="post">';
    echo '<div class="search_div"><input type="text" class="text" size="80" name="text"></div>';
    echo '<button type="submit" class="search_btn">検索</button>';
    echo '</form>';
}else{
    echo $action_login;
    echo '<div class="search_div"><input type="text" class="text" size="80" name="text"></div>';
    echo '<button type="submit" class="search_btn" onclick="btnCheck()">検索</button>';
    echo '</form>';
}
?>
<br>
<?php echo '<p><img src="img/',$img,'背景.png" alt="背景画像" class="back_img"></p>'; ?>
<?php
$cnt=0;
foreach ($stmt as $row){
    if($i%2!=0){
        // 奇数の場合
        if(isset($_SESSION['user'])) {
            // ログインしている時
            echo '<form action="merchandise_detail.php" method="post" name="a_form',$cnt,'">';
            echo '<div class="border_vertical">';
            echo '<input type="hidden" name="genre_id" value="', $row['genre_id'], '">';
            echo '<input type="hidden" name="merchandise_id" value="', $row['merchandise_id'], '">';
            echo '<div class="merchandise_range">';
            echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form',$cnt,'.submit();"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></a></p>'; //ボタンをaタグにする
            echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form',$cnt,'.submit();">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</a></p>';
            echo '</div>';
            // echo '<button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_img_btn"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></button>';
            // echo '<p class="p_btn"><button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_name_btn">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</button></p>';
            echo '</div>';
            echo '</form>';
        }else{
            // ログインしていない時
            echo '<form action="login_in.php" method="post" name="a_form',$cnt,'">'; // login.phpのページへ遷移
            echo '<div class="border_vertical">';
            echo '<div class="merchandise_range">';
            echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form',$cnt,'.submit();"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></a></p>'; //ボタンをaタグにする
            echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form',$cnt,'.submit();">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</a></p>';
            echo '</div>';
            // echo '<button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_img_btn" onclick="btnCheck()"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></button>';
            // echo '<p class="p_btn"><button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_name_btn" onclick="btnCheck()">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</button></p>';
            echo '</div>';
            echo '</form>';
        }
    }else{
        // 偶数の場合
        if(isset($_SESSION['user'])) {
            // ログインしている時
            echo '<form action="merchandise_detail.php" method="post" name="a_form',$cnt,'">';
            echo '<div class="border_vertical1">';
            echo '<input type="hidden" name="genre_id" value="', $row['genre_id'], '">';
            echo '<input type="hidden" name="merchandise_id" value="', $row['merchandise_id'], '">';
            echo '<div class="merchandise_range">';
            echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form',$cnt,'.submit();"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></a></p>'; //ボタンをaタグにする
            echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form',$cnt,'.submit();">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</a></p>';
            echo '</div>';
            // echo '<button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_img_btn"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></button>';
            // echo '<p class="p_btn"><button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_name_btn">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</button></p>';
            echo '</div>';
            echo '</form>';
        }else{
            // ログインしていない時
            echo '<form action="login_in.php" method="post" name="a_form',$cnt,'">'; // login.phpのページへ遷移
            echo '<div class="border_vertical1">';
            echo '<div class="merchandise_range">';
            echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form',$cnt,'.submit();"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></a></p>'; //ボタンをaタグにする
            echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form',$cnt,'.submit();">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</a></p>';
            echo '</div>';
            // echo '<button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_img_btn" onclick="btnCheck()"><img src="', $row['image'], '" alt="商品画像" class="merchandise_img"></button>';
            // echo '<p class="p_btn"><button type="submit" name="merchandise_id" value="', $row['merchandise_id'], '" class="merchandise_name_btn" onclick="btnCheck()">', $row['merchandise_name'], '<br>¥', number_format($row['price']), '</button></p>';
            echo '</div>';
            echo '</form>';
        }
    }
    $cnt++;
    $i++;
}
echo '<p>',$login_message,'</p>';
?>

</body>
</html>