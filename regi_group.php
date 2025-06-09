<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>グループを作成</title>
    <style>
        .bd{
            font-size:18;
            background-color : #FFFFDB;
            text-align:center;
        }
    </style>
</head>
<body class = "bd">
<?php
    // DB接続設定、初期化
    $dsn = 'mysql:dbname="データベース名";host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    session_start();
    
    $results = "";
    $name = "";
    $pw = "";
    
    $flag = false;
?>
    <h1>グループを作成</h1><br>
    <form action="" method="post">
        <span>グループ名：</span>
        <input type="text" name="name" placeholder="グループ名を入力">
        <input type="submit" name="n_buttom">
        <hr>
    </form>
    <?php
        //グループ名がかぶってないか確認
        if(isset($_POST['n_buttom'])){
            //echo "a";
            if(!empty($_SESSION['g_name'])){
                unset($_SESSION['g_name']);                
            }
            if(!empty($_POST['name'])){
                //echo "b";
                $name = $_POST['name'];
                //名前が一緒のグループをSELECT
                $sql = 'SELECT * FROM grp WHERE name = :name';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->execute();
                $results = $stmt->fetchALL();
                
                if(!empty($results)){//同じグループ名が存在する場合
                    //echo "c";
                    echo '<r>このグループ名は既に使用されています。</r>';
                }else{
                    //echo "d";
                    //グローバル関数に名前を保存
                    $_SESSION['gname'] = $name;
                    hyoji($name);
                }
            }else{
                //echo "e";
                echo '<r>グループ名を入力してください</r>';
            }
        }
        
        //グループ作成
        if(isset($_POST['submit'])){
            ////echo 1;
            if(!empty($_POST['pw']) && !empty($_POST['re_pw'])){
                ////echo 2;
                if($_POST['pw'] == $_POST['re_pw']){//パスワードが一致するか
                    ////echo 3;
                    $pw = $_POST['pw'];
                    $name = $_SESSION['gname'];
                    
                     //名前が一緒のグループをSELECT
                    $sql = 'SELECT * FROM grp WHERE name = :name';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->execute();
                    $results = $stmt->fetchALL();
                    
                    if(empty($results)){
                        ////echo 4;
                        //INSERT グループの登録
                        $sql = "INSERT INTO grp(name,pw) VALUES (:name, :pw)";
                        $name = $_SESSION['gname'];
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':pw', $pw, PDO::PARAM_INT);
                        $stmt->execute();
                        //IDを表示するためのflag
                        $flag = true;
                        
                        echo '<r>グループを作成しました。</r>';
                        
                    }
                        
                    
                }else{
                    //echo 6;
                    hyoji($_SESSION['gname']);
                    echo '<r>パスワードが一致しません</r>';
                }
            }else{
                //echo 7;
                hyoji($_SESSION['gname']);
                echo '<r>未入力の項目があります</r>';
            }
    
        }
        
        //echo 8;
        
        if($flag){
            $sql = 'SELECT * FROM grp WHERE name = :name';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchALL();
            
            if(!empty($results)){
                foreach($results as $row){
                    
                    echo '<r>グループIDは</r>';
                    echo '<r style = "color : red; size : 150%;">「'.$row['id'].'」</r>';
                    echo '<r>です。</r><br>';
                    echo  'グループIDはログイン時必要になります';
                    
                    $g_id = $row['id'];
                }    
                
                //グループごとのテーブルを作成（使うかは未定）
                $sql = "CREATE TABLE IF NOT EXISTS "
                        . $g_id
                        ."_member"
                        ." ("
                        . "m_id INT PRIMARY KEY,"
                        . "m_name VARCHAR(30),"
                        . "m_pw VARCHAR(10),"
                        . "g_id INT, FOREIGN KEY(g_id) REFERENCES grp(id)"
                        .");";
                        
                $stmt = $pdo->query($sql);
                $sql = "CREATE TABLE IF NOT EXISTS "
                        . $g_id
                        . '_shohin'
                        ." ("
                        . "s_id INT UNSIGNED,"
                        . "s_name VARCHAR(30),"
                        . "s_uri INT UNSIGNED,"
                        . "s_gen INT UNSIGNED,"
                        . "s_img MEDIUMBLOB,"
                        . "g_id INT, "
                        . "PRIMARY KEY (s_id,g_id),"
                        . "FOREIGN KEY(g_id) REFERENCES grp(id)"
                        .");";
     
     
                $stmt = $pdo->query($sql);
                $sql = "CREATE TABLE IF NOT EXISTS "
                        . $g_id
                        . '_hold'
                        ." ("
                        . "id INT AUTO_INCREMENT,"
                        . "s_id INT UNSIGNED, "
                        . "su INT UNSIGNED, "
                        . "PRIMARY KEY (id,s_id),"
                        . "FOREIGN KEY(s_id) REFERENCES "
                        . $g_id
                        ."_shohin(s_id)"
                        .");";
                $stmt = $pdo->query($sql);
                $sql = "CREATE TABLE IF NOT EXISTS "
                        . $g_id
                        . '_hanbai'
                        ." ("
                        . "h_id INT UNSIGNED,"
                        . "h_kin INT UNSIGNED,"
                        . "h_oturi INT UNSIGNED,"
                        . "h_date TIMESTAMP,"
                        . "m_id INT, "
                        . "g_id INT, "
                        . "PRIMARY KEY (h_id,m_id,g_id),"
                        . "FOREIGN KEY(m_id) REFERENCES "
                        . $g_id
                        ."_member(m_id),"
                        . "FOREIGN KEY(g_id) REFERENCES "
                        ."grp(id)"
                        .");";
                $stmt = $pdo->query($sql);
                $sql = "CREATE TABLE IF NOT EXISTS "
                        . $g_id
                        . '_shosai'
                        ." ("
                        . "hs_id INT AUTO_INCREMENT,"
                        . "h_id INT UNSIGNED,"
                        . "s_id INT UNSIGNED,"
                        . "su INT UNSIGNED, "
                        . "ukin INT UNSIGNED, "
                        . "gkin INT UNSIGNED, "
                        . "PRIMARY KEY (hs_id,h_id,s_id),"
                        . "FOREIGN KEY(h_id) REFERENCES "
                        . $g_id
                        ."_hanbai(h_id),"
                        . "FOREIGN KEY(s_id) REFERENCES "
                        . $g_id
                        ."_shohin(s_id)"
                        .");";
                 
                $stmt = $pdo->query($sql);
                unset($_SESSION['g_name']);
            }    
        }
            
        function hyoji($name){
            $name = $_SESSION['gname'];
            
            echo '<form action="" method="post">';
            echo 'グループ名：';
            echo '<input type="text" name="hold" value= '. $name .' readonly>';
            echo '<hr>
                <span>パスワードを入力してください</span><br>
                <span>パスワード：</span>
                <input type="text" name="pw" placeholder="パスワードを入力"><br>
                <span>確　認　用：</span>
                <input type="text" name="re_pw" placeholder="もう一度入力">
                <input type="submit" name="submit" value="確定">
                </form>';
        }
                
    
    
    ?>
    
</body>
<foot>
    <?php
    unset($_SESSION['g_name']);
    ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <button onclick = "location.href= 'https://tech-base.net/tb-270028//regi/regi_login.php'">ログイン画面へ戻る</button>
</foot>
</html>