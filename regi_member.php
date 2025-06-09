<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メンバー登録</title>
    <style>
        .bd{
            font-size:18;
            background-color : #FFFFDB;
        }
        form{
            color : #2F3B21;
            text-align : left;
            font-size:18px;
        }
        .wide{
            text-align:center;
            width:55%;
            border:solid 5px #53683A;
            float : left;
            font-size:20px;
        }
        
        .rgt{
            text-align:center;
            width:40%;
            border:solid 3px #53683A;
            float: right;
        }
        .div1{
            display: flow-root;
        }
        .tbl1{
            color :#2F3B21;
            border-collapse: collapse;
            background-color : white;
            width :100%;
            text-align:center;
            
        }
        .t1{
            background-color:#C3D3B1;
            width :25%;
            font-size:20px;
            border-top:2px solid #53683A;
            border-bottom:1px solid #53683A;
        }
        .t2{
            background-color:#C3D3B1;
            width :50%;
            font-size:20px;
            border-top:2px solid #53683A;
            border-bottom:1px solid #53683A;
        }
        span{
            color:#FF614D;
        }
    </style>
</head>
<body class="bd">
    <div class = "div1">
    <div class ="wide">
    <h1>メンバー登録</h1>
    <?php
        // DB接続設定
        $dsn = 'mysql:dbname="データベース名";host=localhost';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        
        //セッションの開始
        session_start();
        $g_id = $_SESSION['id'];
        $g_name = $_SESSION['name'];
        $g_table = $g_id.'_member';
        $hanbai = $g_id.'_hanbai';

        
        //初期化
        $name = "";
        $m_id = "";
        $pw = "";
        $results = "";
        $flag = false;
        $up_id = "";
        $up_name = "";
        $up_pw = "";
    ?>
    <form action="" method="post">
        <div>　メンバー情報の登録(ほかのメンバーとIDがかぶらないようにしてください)</div>
        
    <?php
        //変更
       if(isset($_POST['up_submit'])){//変更、削除ボタンが押された場合
            if(!empty($_POST["up_id"]) && !empty($_POST["up_pw"])){//編集したい投稿番号,パスワードを入力
                $up_id = $_POST["up_id"];//データの取得
                $pw =$_POST["up_pw"];
                $sql ="SELECT * FROM ".$g_table." WHERE m_id = :m_id AND g_id = :g_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':m_id', $up_id, PDO::PARAM_INT);
                $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchALL();
                
                if (!empty($results)) {//データが存在する場合
                    
                    foreach($results as $row){
                        if($pw == $row['m_pw']){
                            $_SESSION['up_id'] = $up_id;
                            $up_name = $row['m_name'];
                            $up_pw = $row['m_pw'];
                            echo '<span>編集中のメンバーID→</span>';
                            echo '<input type="text" name="hold" value= '. $up_id .' readonly ">';
                        }else{
                            echo '<span>！パスワードが一致しません</span>';
                            id_reset();
                        }
                       
                    }
                }else{
                    echo '<span>！メンバーが存在しません</span>';
                    id_reset();
                }
                
            }else{
                echo '<span>！未入力の項目があります</span>';
                id_reset();
            }
        }
   
   ?>
        <br>
        <r>　ログイン用ID：</r>
        <?php
        if(isset($_POST['up_submit'])){
            if(!empty($_SESSION['up_id'])){
                
                echo '<input type="number" min = 0 name="m_id" placeholder="希望のIDを入力" value = "'.$up_id.'">';
            }else{
                echo '<input type="number" min = 0 name="m_id" placeholder="希望のIDを入力">';
            }
        }else{
            echo '<input type="number" min = 0 name="m_id" placeholder="希望のIDを入力">';
        }
        
        ?>
        
        <r>名前：</r>
        <?php
        if(isset($_POST['up_submit'])){
            if(!empty($up_name)){
                
                 echo '<input type="text" name="name" placeholder="名前を入力" value = "'.$up_name.'">';
               
            }else{
                echo '<input type="text" name="name" placeholder="名前を入力">';
            }
        }else{
            echo '<input type="text" name="name" placeholder="名前を入力">';
        }
        ?>
        <br>
        <r>　パスワード　：</r>
        <?php
        if(isset($_POST['up_submit'])){
            if(!empty($up_pw)){
                
                echo '<input type="text" name="pw" placeholder="パスワードを入力" value = "'.$pw.'">';
            }else{
                echo '<input type="text" name="pw" placeholder="パスワードを入力">';
            }
        }else{
            echo '<input type="text" name="pw" placeholder="パスワードを入力">';
        }
        ?>
        
        <input type="text" name="re_pw" placeholder="確認用">
        <input type="submit" name="submit"><br>
        
    <?php
    //送信を押す（UPDATE,INSERT)
    if(isset($_POST['submit'])){
        if(!empty($_POST['m_id']) && !empty($_POST['name']) && !empty($_POST['pw']) && !empty($_POST['re_pw'])){
            if($_POST['pw'] == $_POST['re_pw']){
                //echo 1;
                //データの取得
                $m_id = $_POST['m_id'];
                if(!empty($_SESSION['up_id'])){
                    $up_id = $_SESSION['up_id'];
                }
                $name = $_POST['name'];
                $pw = $_POST['pw'];
                
                $sql = "SELECT * FROM ".$g_table." WHERE m_id = :m_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':m_id', $m_id, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchALL();
                
                if(empty($results)){
                    if(!empty($up_id)){
                        //echo 2;
                        $sql = 'SELECT DISTINCT M.m_id FROM ';
                        $sql =  $sql.$g_table.' AS M LEFT OUTER JOIN '.$hanbai.' AS H ON M.m_id = H.m_id ';
                        $sql =  $sql.' WHERE M.m_id = :id AND H.m_id IS NOT NULL';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $up_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $results = $stmt->fetchALL();
                        
                        foreach($results as $row){
                            if($row['m_id'] == $up_id){
                                echo '<span>！すでに販売実績のあるメンバーはIDを変更できません</span>';
                                //id_reset();
                            }else{
                                $flag = true;
                                unset($_SESSION['up_id']);
                            }
                        }
                    }else{
                        //echo 3;
                        //INSERT
                        $sql = "INSERT INTO ".$g_table."(m_id,m_name,m_pw,g_id) VALUES (:m_id,:name,:pw,:g_id)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':m_id', $m_id, PDO::PARAM_INT);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':pw', $pw, PDO::PARAM_STR);
                        $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
                                        
                        $stmt->execute();
                        echo '<span>　登録が完了しました、メンバーIDは「'.$m_id.'」です</span>';
                    }
                }else{
                    if(!empty($up_id)){
                        //echo 4;
                        foreach($results as $row){
                            //echo 'a';
                            if($row['m_id']==$m_id && $up_id <> $m_id){
                                echo '<span>！既に使用されているIDは設定できません</span>';
                            }else{
                                //echo 'c';
                                $flag = true;
                            }
                        }
                        
                    }else{
                        //echo 5;
                        echo '<span>！既に使用されているIDは設定できません</span>';
                        id_reset();
                    }
                }
            }else{
                //echo 6;
                echo '<span>！パスワードが一致しません</span>';
                id_reset();
            }
            
            
        }else{
            //echo 7;
            echo '<span>！未入力の項目があります</span>';
            id_reset();
        }
        if($flag){
            //UPDATE
            //echo 8;
            $sql = "UPDATE ".$g_table." SET m_id = :m_id,m_name = :name,m_pw = :pw WHERE m_id = :id and g_id = :g_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':m_id', $m_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':pw', $pw, PDO::PARAM_STR);
            $stmt->bindParam(':id', $up_id, PDO::PARAM_INT);
            $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
            
            $stmt->execute();
            echo '<span>登録が完了しました、新しいメンバーIDは「'.$m_id.'」です</span>';
            unset($_SESSION['up_id']);
        }
    }
    

    ?>
    </form>
   <hr>
   <form action="" method="post">
        <div>　メンバー情報の変更</div>
        <div style = "font-size :80%; text-indent :20px; color:#FF614D;">※「編集不可」のメンバーは削除、IDの変更ができません</div>
        <r>　変更したいID：</r>
        <?php
        if(isset($_POST['up_button'])){
            $sub = $_POST['up_button'];
            echo '<input type="number" min = 0 name="up_id" value = "'.$sub.'"placeholder="IDを入力"><br>';
        }else{
            echo '<input type="number" min = 0 name="up_id" placeholder="IDを入力"><br>' ; 
        }
        ?>
        <r>　パスワード　：</r>
        <input type="text" name="up_pw" placeholder="パスワードを入力">
        <input type="submit" name="up_submit" value = "変更">
        <input type="submit" name="de_submit" value = "削除">

   <?php
        //削除
       if(isset($_POST['de_submit'])){//変更、削除ボタンが押された場合
        if(!empty($_POST["up_id"]) && !empty($_POST["up_pw"])){//編集したい投稿番号,パスワードを入力
            $up_id = $_POST["up_id"];//データの取得
            $pw =$_POST["up_pw"];
            
            $sql ="SELECT * FROM ".$g_table." WHERE m_id = :m_id AND g_id = :g_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':m_id', $up_id, PDO::PARAM_INT);
            $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchALL();
            
            if (!empty($results)) {//データが存在する場合
                
                foreach($results as $row){
                    if($pw == $row['m_pw']){
                        if(isset($_POST['de_submit'])){
                            $up_id = $_POST["up_id"];
                            //DELETE
                            
                            $sql ="DELETE FROM ".$g_table." WHERE m_id = :m_id AND g_id = :g_id" ;
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':m_id', $up_id, PDO::PARAM_INT);
                            $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
                            
                            $stmt->execute();
                            echo '<span>！「'.$up_id.'」のメンバーを削除しました</span>';
                        }else{
                            echo '<span>！パスワードが一致しません</span>';
                        }
                    }
                }
                   
            }else{
                echo '<span>！投稿が存在しません</span>';
                id_reset();
            }
            
        }else{
            echo '<span>！未入力の項目があります</span>';
            id_reset();
        }
    }
   
   ?>
    </form>
    <br>
    </div>
    <div class= "rgt">
        <form action="" method="post">
        <h1>メンバー いちらん</h1>
        <?php
        
        //これまでの投稿を表示する
        
        $sql = 'SELECT DISTINCT M.m_id,M.m_name,H.m_id as hm FROM ';
        $sql =  $sql.$g_table.' AS M LEFT OUTER JOIN '.$hanbai.' AS H ON M.m_id = H.m_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchALL();
        
            echo '<table class = "tbl1">
              <tr>
              <th class= "t1">ID</th>
              <th class= "t2">名前</th>
              <th class= "t1">IDの編集</th>
              </tr>';
              
            foreach($results as $row){
                echo '<tr>';
                echo '<td style = "width:10%">'.$row['m_id'].'</td>';
                echo '<td style = "width:20%">'.$row['m_name'].'</td>';
                if(empty($row['hm'])){
                    echo '<td><button type="submit" name="up_button" value="'.$row['m_id'].'">編集可</button></td>';
                }else{
                    echo '<td>編集不可</td>';
                }
                echo "<tr>";
            }
            echo '</table>';
         ?>
         </form>
         <?php
         
        function id_reset(){
            if(!empty($_SESSION['up_id'])){
                unset($_SESSION['up_id']);
            }
        }
        ?>
        
    </div>
    </div>
</body>
<foot>
    <br>
    <br>
    <button onclick = "location.href= 'https://tech-base.net/tb-270028//regi/regi_start.php'">スタートへ戻る</button>
</foot>
</html>