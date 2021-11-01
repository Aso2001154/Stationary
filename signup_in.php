<!DOCTYPE html>
<html la="ja">
<head>
    <meta name="viewport" charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <link rel="stylesheet" href="css/style.css">
    <title>新規登録</title>
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
    <a href="login_in.php"><img src="img/header_name.png" alt="画像" class="header_name"></a>
</header>
<div class="container">
    <form action="signup_out.php" method="post">
        <h1 class="title">sign up</h1>
        <h2 class="subtitle">id<span>※制限:アルファベット/数字/ハイフンなし/8文字以上</span></h2>
        <p><input type="text" class="text" name="user_id" id="id"></p>
        <h2 class="subtitle">pass<span>※制限:数字/ハイフンなし/4文字以上</span></h2>
        <p><input type="password" class="text" name="user_pass" id="pass"></p>
        <h2 class="subtitle">name</h2>
        <p><input type="text" class="text" name="user_name" id="name"></p>
        <h2 class="subtitle">address</h2>
        <p><input type="text" class="text" name="user_address" id="address"></p>
        <h2 class="subtitle">credit number<span>※制限:ハイフンなし</span></h2>
        <p><input type="number" class="number_text" name="credit_number" id="credit"></p>
        <button value="send" class="login" onsubmit="return btnCheck()" onclick="return btnCheck()">signup</button>
    </form>
</div>
</body>
</html>
