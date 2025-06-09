<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品登録</title>
        <style>
        .bd{
            font-size:18;
            background-color : #FFFFDB;
        }
        form{
            color : #CC0044;
            text-align : left;
            font-size:18px;
        }
        .lft{
            text-align:center;
            width:43%;
            border:solid 5px #86002D;
            float : left;
            font-size:20px;
        }
        
        .rgt{
            text-align:center;
            width:55%;
            border:solid 3px #86002D;
            float: right;
        }
        .div1{
            display: flow-root;
        }
        .tbl1{
            color :#86002D;
            border-collapse: collapse;
            background-color : white;
            width :100%;
            text-align:center;
            
        }
        .t1{
            background-color:#FFCDDE;
            width :30%;
            font-size:20px;
            border-top:2px solid #4C0019;
            border-bottom:1px solid #4C0019;
        }
        .t2{
            background-color:#FFCDDE;
            width :70%;
            font-size:20px;
            border-top:2px solid #4C0019;
            border-bottom:1px solid #4C0019;
        }
        span{
            color:#04C47F;
        } 
        td_img{
            height:20px;
            width:20px;
        }
        </style>
</head>
<body class = "bd">
    <div class = "div1">
    <div class="lft">
    <h1>商品登録</h1>
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
        $g_table = $g_id.'_shohin';
        $shosai = $g_id.'_shosai';
        
        //初期化
        $name = "";
        $s_id = "";
        $results = "";
        $flag = false;
        $up_id = "";
        $up_name = "";
        $uri = "";
        $up_uri = "";
        $gen = "";
        $up_gen = "";
        $img = "";
        
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div>　商品の登録(IDがかぶらないようにしてください)</div>
        
    <?php
    
        //変更
       if(isset($_POST['up_submit'])){//変更、削除ボタンが押された場合
            if(!empty($_POST["up_id"])){//編集したい投稿番号,パスワードを入力
                $up_id = $_POST["up_id"];//データの取得

                $sql ="SELECT * FROM ".$g_table." WHERE s_id = :s_id AND g_id = :g_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':s_id', $up_id, PDO::PARAM_INT);
                $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchALL();
                
                if (!empty($results)) {//データが存在する場合
                    
                    foreach($results as $row){
                        
                        //情報を変数に代入
                        $_SESSION['up_id'] = $up_id;
                        $up_name = $row['s_name'];
                        $up_uri = $row['s_uri'];
                        $up_gen = $row['s_gen'];
                        
                        //編集中の状態にする
                        echo '<r>　編集中の商品ID→</r>';
                        echo '<input type="text" name="hold" value= '. $up_id .' readonly ">';
                       
                    }
                }else{
                    //データが存在しない場合
                    echo '<span>！商品が存在しません</span>';
                    id_reset();
                }
                
            }else{
                //未入力の場合
                echo '<span>！未入力の項目があります</span>';
                id_reset();
            }
            echo '<br>';
        }
   
   ?>

        <r>　商品ID：</r>
        <?php
        
        if(isset($_POST['up_submit'])){
            //変更ボタンが押されたとき
            if(!empty($_SESSION['up_id'])){
                //変更中の状態の時
                echo '<input type="number" min = 0 name="s_id" placeholder="希望のIDを入力" value = "'.$up_id.'">';
            }else{
                //変更中じゃない場合
                echo '<input type="number" min = 0 name="s_id" placeholder="希望のIDを入力">';
            }
        }else{
            //通常時（変更ボタンが押されていないとき）
            echo '<input type="number" min = 0 name="s_id" placeholder="希望のIDを入力">';
        }
        
        ?>
        
        <r>商品名：</r>
        <?php
        if(isset($_POST['up_submit'])){
            if(!empty($up_name)){
                 //変更中の状態の時
                 echo '<input type="text" name="name" placeholder="名前を入力" value = "'.$up_name.'">';
            }else{
                //変更中じゃない場合
                echo '<input type="text" name="name" placeholder="名前を入力">';
            }
        }else{
           //通常時（変更ボタンが押されていないとき） 
            echo '<input type="text" name="name" placeholder="名前を入力">';
        }
        ?>
        <br>
        <r>　売値：</r>
        <?php
        if(isset($_POST['up_submit'])){
            if(!empty($up_uri)){
                //変更中の状態の時
                 echo '<input type="number" name="uri" placeholder="売値" value = "'.$up_uri.'">';
               
            }else{
                //変更中じゃない場合
                echo '<input type="number" name="uri" placeholder="売値">';
            }
        }else{
            //通常時（変更ボタンが押されていないとき）
            echo '<input type="number" name="uri" placeholder="売値">';
        }
        ?>
        <r>原価：</r>
        <?php
        if(isset($_POST['up_submit'])){
            if(!empty($up_gen)){
                //変更中の状態の時
                 echo '<input type="number" name="gen" placeholder="原価" value = "'.$up_gen.'">';
               
            }else{
                //変更中じゃない場合
                echo '<input type="number" name="gen" placeholder="原価">';
            }
        }else{
            //通常時（変更ボタンが押されていないとき）
            echo '<input type="number" name="gen" placeholder="原価">';
        }
        
        ?>
        <br>
        <r　text-indent:"15px">
        <input type="file" name="s_img" accept="image/jpeg, image/png"></r>
        <input type="submit" name="submit"> <br>
        
    <?php
    //送信を押す（UPDATE,INSERT)
    if(isset($_POST['submit'])){
        if(!empty($_POST['s_id']) && !empty($_POST['name'])){
            //データの取得
            $s_id = $_POST['s_id'];
            
            $sql = 'SELECT DISTINCT S.s_id,S.s_name,S.s_uri,S.s_gen,S.s_img,HS.s_id as hs FROM ';
            $sql =  $sql.$g_table.' AS S LEFT OUTER JOIN '.$shosai.' AS HS ON  S.s_id = HS.s_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchALL();
            
            if(!empty($_SESSION['up_id'])){
                $up_id = $_SESSION['up_id'];
            }
            $name = $_POST['name'];
            $uri = $_POST['uri'];
            $gen = $_POST['gen'];
            
            //データの存在を確認
            $sql = "SELECT * FROM ".$g_table." WHERE s_id = :s_id AND g_id = :g_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
            $stmt->bindParam(':s_id', $s_id, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchALL();
            
            if(empty($results)){//データが存在しない場合
                if(!empty($up_id)){//変更中、かつIDを変えたい場合
                    //UPDATE用フラグ
                    $sql = 'SELECT DISTINCT S.s_id FROM ';
                        $sql =  $sql.$g_table.' AS S LEFT OUTER JOIN '.$shosai.' AS H ON S.s_id = H.s_id ';
                        $sql =  $sql.' WHERE S.s_id = :id AND H.s_id IS NOT NULL';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $up_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $results = $stmt->fetchALL();
                        
                        foreach($results as $row){
                            if($row['s_id'] == $up_id){
                                echo '<span>！すでに販売実績のある商品はIDを変更できません</span>';
                                //id_reset();
                            }else{
                                $flag = true;
                                unset($_SESSION['up_id']);
                            }
                        }
                    
                }else{
                    //INSERT
                    
                    if(!empty($_FILES['s_img']['tmp_name'])){
                        //画像を設定する場合
                        $img = file_get_contents($_FILES['s_img']['tmp_name']);
                    }else{
                        //画像を設定しない場合
                        $img = null;
                    }
                    
                    $sql = "INSERT INTO ".$g_table."(s_id,s_name,s_uri,s_gen,s_img,g_id) VALUES (:s_id,:name,:uri,:gen,:img,:g_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':s_id', $s_id, PDO::PARAM_INT);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':uri', $uri, PDO::PARAM_INT);
                    $stmt->bindParam(':gen', $gen, PDO::PARAM_INT);
                    $stmt->bindParam(':img',$img, PDO::PARAM_LOB);
                    $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
                    
                    $stmt->execute();
                    echo '<span text-indent:"15px">登録が完了しました、商品IDは「'.$s_id.'」です</span>';
                }
            }else{
                //データが存在する場合
                if(!empty($up_id)){//変更中の場合
                        foreach($results as $row){
                            //echo 'a';
                            if($row['s_id']==$s_id && $up_id <> $s_id){
                                echo '<span>！既に使用されているIDは設定できません</span>';
                            }else{
                                //echo 'c';
                                $flag = true;
                            }
                        }
                        
                }else{
                    echo '<span>！既に使用されているIDは設定できません</span>';
                    id_reset();
                }
            }
        
            
        }else{
            echo '<span>！未入力の項目があります<spanr>';
            id_reset();
        }
        if($flag){
            //変更用フラグがtrueの場合
            
            $sql = "UPDATE ".$g_table." SET s_id = :s_id,s_name = :name, s_uri = :uri, s_gen = :gen, s_img = :img WHERE s_id = :id";
            
            
            if(!empty($_FILES['s_img']['tmp_name'])){
                //画像を変更する場合
                $img = file_get_contents($_FILES['s_img']['tmp_name']);
            }else{
                //画像を変更しない場合
                foreach($results as $row){
                    //元の画像データを代入
                    $img = $row['s_img'];
                }
                
            }
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':s_id', $s_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':uri', $uri, PDO::PARAM_INT);
            $stmt->bindParam(':gen', $gen, PDO::PARAM_INT);
            $stmt->bindParam(':img',$img, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $up_id, PDO::PARAM_INT);
            
            $stmt->execute();
            echo '<span>登録が完了しました、IDは「'.$s_id.'」です</span>';
            unset($_SESSION['up_id']);
        }
    }

    ?>
    </form>
   <hr>
   <form action="" method="post" enctype="multipart/form-data">
        <div>　商品情報の変更　</div>
        <div style = "font-size :80%; text-indent :20px; color:#04C47F;">※「編集不可」の商品はIDの変更ができません</div>
        <r>　変更したいID：</r>
        <?php
        
        if(isset($_POST['up_button'])){//表の編集可ボタンを押した場合
            //ボタンの情報を取得、入力した状態のテキストボックスを表示
            $sub = $_POST['up_button'];
            echo '<input type="number" min = 0 name="up_id" value ="'.$sub.'" placeholder="IDを入力">';
        } else{
            //通常用テキストボックス
            echo '<input type="number" min = 0 name="up_id" placeholder="IDを入力">';
        }
        ?>
        <input type="submit" name="up_submit" value = "変更">
        <input type="submit" name="de_submit" value = "削除">

   <?php
        //削除
       if(isset($_POST['de_submit'])){//変更、削除ボタンが押された場合
        if(!empty($_POST["up_id"])){//編集したい投稿番号,パスワードを入力
            $up_id = $_POST["up_id"];//データの取得
            
            $sql ="SELECT * FROM ".$g_table." WHERE s_id = :s_id AND g_id = :g_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':s_id', $up_id, PDO::PARAM_INT);
            $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchALL();
            
            if (!empty($results)) {//データが存在する場合
                
                $up_id = $_POST["up_id"];
                //DELETE
                
                $sql ="DELETE FROM ".$g_table." WHERE s_id = :s_id" ;
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':s_id', $up_id, PDO::PARAM_INT);
                
                $stmt->execute();
                echo '<span>！「'.$up_id.'」の商品を削除しました</span>';
                
                   
            }else{
                echo '<span>！商品が存在しません</span>';
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
    <br>
    </div>
    <div class = "rgt">
    <h1>商品いちらん</h1>
    <form action="" method="post" enctype="multipart/form-data">
    <?php
    //商品一覧を表示
    
    $sql = 'SELECT DISTINCT S.s_id,S.s_name,S.s_uri,S.s_gen,S.s_img,HS.s_id as hs FROM ';
    $sql =  $sql.$g_table.' AS S LEFT OUTER JOIN '.$shosai.' AS HS ON  S.s_id = HS.s_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchALL();
    
        echo '<table class = "tbl1">
          <tr>
          <th style = "width :10%;" class = "t1">商品ID</th>
          <th style = "width :20%;" class = "t2">名前</th>
          <th style = "width :15%;" class = "t2">売値</th>
          <th style = "width :15%;" class = "t2">売価</th>
          <th style = "width :20%;" class = "t2">画像</th>
          <th style = "width :20%;" class = "t2">IDの編集</th>
          </tr>';
          
        foreach($results as $row){
            echo '<tr style = "heigth:60px;">';
            echo '<td>'.$row['s_id'].'</td>';
            echo '<td>'.$row['s_name'].'</td>';
            echo '<td>'.$row['s_uri'].'</td>';
            echo '<td>'.$row['s_gen'].'</td>';

            echo '<td class = "td_img">';
            if(!empty($row['s_img'])){
                //画像が設定してある場合（エンコード）
                echo '<img src="data:image/jpeg;base64,'. base64_encode($row['s_img']).'" alt = "'.$row['s_name'].'"width="50px" height="60px"/></td>';
            }else{
                //画像が設定されていない場合（no imageと表示）
                echo 'no image </td>';
            }
            
            if(empty($row['hs'])){
                //販売実績がない商品
                echo '<td><button type="submit" name="up_button" value="'.$row['s_id'].'">編集可</button></td>';
            }else{
                //1度でも販売実績がある商品
                echo '<td>編集不可</td>';
            }
            
            echo '<tr>';
        }
        echo '</table>';
        
    function id_reset(){
        if(!empty($_SESSION['up_id'])){
            unset($_SESSION['up_id']);
        }
    }
    
    ?>
    </form>
    </div></div>
</body>
<foot>
    <br>

    <button onclick = "location.href= 'https://tech-base.net/tb-270028//regi/regi_start.php'">スタートへ戻る</button>
</foot>
</html>