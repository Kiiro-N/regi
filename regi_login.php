<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>WEBレジログイン</title>
    <div style="text-align: center;"><img src="WEBレジ.png" alt="ログイン画面" title="WEBレジ" height = "100px" width="320px" ></div>
    <style>
        .bd{

            background-color : #FFFFDB;
        }
        form{
            color : #926D12;
             text-align : center;
             font-size:24px;
        }
        .txt1{
            height : 20px;
            width : 30%;
            font-size:20px;
        }
        .btn{
            height : 32px;
            width : 30%;
            font-size:18px;
        }
        .txt2{
            color:#FF614D;
        }
    </style>
</head>
<body class = "bd">
    <?php
        // DB接続設定
        $dsn = 'mysql:dbname="データベース名";host=localhost';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //セッションの開始
        session_start();
        if(!empty($_SESSION['id'])){
            unset($_SESSION['id']);
        }
        if(!empty($_SESSION['name'])){
            unset($_SESSION['name']);
        }    
    ?>
    <form action="" method="post">
        <r>既存のグループでログイン</r><br>

        <input type="text" name="g_id" placeholder="グループIDを入力" class = "txt1">
        <br>
        <input type="text" name="g_pw" placeholder="パスワードを入力" class = "txt1">
        <br>
        <input type="submit" name = "start" value="ログインして開始" class = "btn">
        <br>
        <?php
            //変数を定義
            $results = "";
            $id = "";
            $pw = "";
            $flag = false;
            
            //buttonが押されたら
            if(isset($_POST['start'])){
                if(!empty($_POST['g_id']) && !empty($_POST['g_pw'])){//空白じゃない場合
                    //データの取得
                    $id = $_POST['g_id'];
                    $pw = $_POST['g_pw'];
                    
                    //SELECT の組み立て　id,pwの一致　
                    $sql = 'SELECT * FROM grp WHERE id = :id AND pw = :pw';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':pw', $pw, PDO::PARAM_STR);
                    $stmt->execute();
                    $results = $stmt->fetchALL();
                    
                    
                    if(!empty($results)){
                        //id,pwが一致しているデータが存在する
                        
                        foreach($results as $row){
                            $_SESSION['id'] = $id;
                            $_SESSION['name'] = $row['name'];
                            header("Location:https://tech-base.net/tb-270028/regi/regi_start.php");
                            exit();
                        }
                    }else{
                        //id,pwが一致しているデータが存在しない
                        echo '<r class="txt2">！グループが存在しないか、パスワードが間違っています。</r>';
                    }
                
                
                }else{
                    echo '<r class="txt2">！未入力の項目があります</r>';
                }
            }    
            
        ?>
        <br>
    </form>
    <hr>
    <form>
        <r>グループの新規作成</r><br>
        <button type="button" name="new" onclick ="location.href= 'https://tech-base.net/tb-270028/regi/regi_group.php'" class = "btn">グループを作成</button>
    </form>


    
</body>
</html>