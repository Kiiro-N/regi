<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ようこそ</title>
    <style>
        button{
            height : 32px;
            width : 30%;
            font-size:18px;
        }
        .bd{
            font-size:18;
            background-color : #FFFFDB;
        }
        .co1{
            /*ピンク*/
            background-color:#FFCDDE;
            color:#86002D;
        }
        .co2{
            /*青*/
            background-color:#CCD1E6;
            color:#222946;
        }
        .co3{
            /*緑*/
            background-color:#C3D3B1;
            color:#2F3B21;
        }
        .co4{
            /*紫*/
            background-color:#F0E1F7;
            color:#581D75;
        }


        
        div{
            text-align:center;
        }
    </style>
</head>
<body class = "bd">
    <div>
    <?php
        // DB接続設定
        $dsn = 'mysql:dbname="データベース名";host=localhost';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //セッションの開始
        session_start();
        if(!empty($_SESSION['id']) && !empty($_SESSION['name'])){
            $id = $_SESSION['id'];
            $name = $_SESSION['name'];
            
            echo '<h1>「'.$name. '」のレジ管理システムです</h1>';
            
        }else{
            echo 'ログインからやり直してください';
        }
        
    ?>
    
    <button onclick = "location.href= 'https://tech-base.net/tb-270028/regi/regi_kaikei.php'" class = "co2">レジを始める</button><br><br>
    <button onclick = "location.href= 'https://tech-base.net/tb-270028/regi/regi_shohin.php'" class = "co1">商品管理</button><br><br>
    <button onclick = "location.href= 'https://tech-base.net/tb-270028/regi/regi_member.php'" class = "co3">メンバー登録</button><br><br>
    <button onclick = "location.href= 'https://tech-base.net/tb-270028/regi/regi_hanlog.php'" class = "co4">販売履歴</button><br><br><hr><br>
    <button onclick = "location.href= 'https://tech-base.net/tb-270028//regi/regi_login.php'">ログイン画面へ戻る</button>
    </div>
    
    
</body>
</html>