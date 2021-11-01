<?php
// database関数化
function data_base(){
    return new PDO(
        'mysql:host=mysql153.phy.lolipop.lan;
        dbname=LAA1291139-company;charset=utf8',
        'LAA1291139',
        'company',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
}
?>
