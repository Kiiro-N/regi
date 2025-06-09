<?php
    // DB接続設定
    $dsn = 'mysql:dbname="データベース名";host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    session_start();
    $g_id = $_SESSION['id'];
    $hanbai = $g_id."_hanbai";//データの取得
    $shosai = $g_id."_shosai";
    
    $sql = "DELETE FROM ".$shosai;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sql = "DELETE FROM ".$hanbai;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    header("Location:https://tech-base.net/tb-270028/regi/regi_hanlog.php");
?>