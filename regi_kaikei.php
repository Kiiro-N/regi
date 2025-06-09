<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お会計</title>
    <style>
        .bd{
            font-size:18;
            background-color : #FFFFDB;
        }
        .btn1{
            width:30%;
            height:3%;
            padding:10px;
            font-size:15;
        }
        .txt1{
            height:50px;
            text-align:center;
        }
        footer {
          position: fixed;
          text-align:right;
          bottom: 0;
          background-color:#FEF09C;
          width: 99%;
        }
        .kaikei_1{
            width:25%;
            height:3%;
            padding:10px;
            font-size:18;
            text-align:right;
        }
        .wrapper{
          min-height: 140vh;/*①高さの最小値*/
          position: relative;/*②相対位置*/
        }
        .tbl1{
            color :#443308;
            border-collapse: collapse;
            background-color : white;
            border :5px double #926D12;
            text-align:center;
            width :50%;
        }

        .tbl2{
            color :#19293B;
            border-collapse: collapse;
            border :5px double #305074;
            background-color : white;
            text-align:center;
            width :47%;
        }

        
        .th1{
            background-color : #F5E091;
            border-bottom:2px dashed #8E7A16;
        
        }
        .th2{
            background-color : #F5E091;
            border-bottom:2px solid #8E7A16;
        
        }
        .th3{
            background-color : #3B9EC7;
            border-bottom:2px dashed #305074;
        }
        .th4{
            background-color : #89C4C7;
            border-bottom:2px solid #305074;
        }
        .td1{
            padding: 5px;
        }
        .td2{
            border-top:1px dashed #305074;
            height:5;
            padding: 5px;
        }

        tfoot{
            font-size:18px;
            background-color : #D7E9E8;
            border:4px double #305074;
            height:40px;
        }
        span{
            color:#FF614D;
        }
        .lft{
            float : left;
        }
        .rgt{
            float: right;
        }
        .div1{
            width : 99%;
            display: flow-root;
            color : #3E240C;
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
        $g_id = $_SESSION['id'];
        $g_name = $_SESSION['name'];
        $s_table = $g_id.'_shohin';
        $m_table = $g_id.'_member';
        $hold = $g_id.'_hold';
        $hanbai = $g_id. '_hanbai';
        $shosai = $g_id.'_shosai';
        
        $s_id = "";
        $m_id = "";

    if(isset($_POST['rset'])){
        header("Refresh:0");
    }
    if(isset($_POST['back'])){
        name_reset();
        hold_reset($hold);
        header("Location:https://tech-base.net/tb-270028/regi/regi_start.php");
        exit();
    }
        
    ?>
    <div class = "wrapper">
        <div class = "div1">
            <div class = "lft"><form action="" method="post">
                担当者ID：
                <input type="number" name="m_id" placeholder="担当者のIDを入力">
                <input type="number" name="m_pw" placeholder="パスワードを入力">
                <input type="submit" name="submit">
            </form>
            </div>
        
        <?php
        //メンバーのログイン
        if(isset($_POST['submit'])){
            //echo 0;
            if(!empty($_POST['m_id']) && !empty($_POST['m_pw'])){
                $m_id = $_POST['m_id'];
                $m_pw = $_POST['m_pw'];
                
                $sql = "SELECT * FROM ".$m_table." WHERE m_id = :m_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':m_id', $m_id, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchALL();
                //echo 1;
                if(!empty($results)){
                    //echo 2;
                    foreach($results as $row){
                        //echo 3;
                        if($m_pw == $row['m_pw']){
                            $_SESSION['m_id'] = $row['m_id'];
                            $_SESSION['m_name'] = $row['m_name'];
                        }
                    }
                }else{
                    echo "<span class = 'rgt'>！メンバーが存在しません</span>";
                    name_reset();
                }
                
            }else{
                echo "<span class = 'rgt'>！未入力の項目があります</span>";
                name_reset();
            }
        }
         //echo 6;
         //ログイン情報の表示
        if(!empty($_SESSION['m_name'])){
            echo '<div class = "rgt">'.$_SESSION['m_name'].'さんがログイン中です</div>';
        }else{
            echo '<span class = "rgt">！ログインしてください</span>';
        }
        ?>
    </div>   
    <hr>    
    
    <?php
        //商品一覧を表示する
        $count = 1;
        
        //ボタンが押されたら
         if(isset($_POST['button'])){
             //echo 9;
            if(!empty($_POST['su'])){
                //echo 10;
                $s_id = $_POST["button"];
                $su = $_POST['su'];
                
                $sql ='INSERT INTO '.$hold.'(s_id,su) VALUES(:s_id,:su)';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':s_id', $s_id, PDO::PARAM_INT);
                $stmt->bindParam(':su', $su, PDO::PARAM_INT);
                $stmt->execute();
            }else{
                echo '<span>！購入数を入力してください</span>';
            }
        }   
        
        $sql = 'SELECT * FROM '.$s_table;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchALL();
        
            echo '<form action="" method="post"><table align = "left" class = "tbl1">
            <tr>
              <th colspan = "4" class = "th1">商品一覧</th>
            </tr>
              <tr class = "th2">
              <th style = "width :20%;"  class = "th2">名前</th>
              <th style = "width :10%;"  class = "th2">売値</th>
              <th style = "width :5%;" class = "th2">数量</th>
              <th style = "width :15%;" class = "th2">購入</th>
          </tr>';

        foreach($results as $row){
            echo '<tr>';
            
            echo '<td class = "td1">'.$row['s_name'].'</td>';
            echo '<td class = "td1">'.$row['s_uri'].'</td>';
            if($count == 1){
                echo '<td rowspan="0">';
                echo '<input type="number" name="su" min = 0 placeholder="購入数量を入力" class = "txt1">';
                echo '</td>'; 
            }
            
            echo '<td class = "td1">';
            echo '<button type="submit" name="button" value="'.$row['s_id'].'">'.$row['s_name'].'</button>';
            echo '</td>';
            echo "<tr>";
            $count = $count + 1;
        }
        echo '</table></form>';
        
    ?>

    <?php
        
        if(isset($_POST['de_button'])){
            $de_id = $_POST['de_button'];
            $sql = 'DELETE FROM '.$hold.' WHERE id = :de_id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':de_id', $de_id, PDO::PARAM_INT);
            $stmt->execute();
            
        }
        if(isset($_POST['h_reset'])){
            hold_reset($hold);
        }
        //購入商品を表示する
        
        $sql = 'SELECT H.id, S.s_name, S.s_uri, H.su FROM '.$s_table.' AS S INNER JOIN '.$hold. ' AS H ON H.s_id = S.s_id ORDER BY H.id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchALL();
        $count = 1;
        $kin_total = 0;
        $su_total = 0;
        
            echo '<form action="" method="post"><table align = "right" class = "tbl2">
            <tr class = "th3">
              <th colspan = "6">購入商品</th>
            </tr>
             <tr class = "th4">
              <th style = "width :5%;">番号</th>
              <th style = "width :18%;">商品名</th>
              <th style = "width :8%;">単価</th>
              <th style = "width :5%;">数量</th>
             <th style = "width :10%;">金額</th>
             <th style = "width :3%;"><input type = "submit" name = "h_reset" value = "リセット"></th>

          </tr>';
          
            foreach($results as $row){
                echo '<tr>';
                echo '<td class = "td2">'.$count.'</td>';
                echo '<td class = "td2">'.$row['s_name'].'</td>';
                echo '<td class = "td2">'.$row['s_uri'].'</td>';
                echo '<td class = "td2">'.$row['su'].'</td>';
                echo '<td class = "td2">'.($row['s_uri'] * $row['su']).'</td>';
                echo '<td class = "td2"><button type="submit" name="de_button" value="'.$row['id'].'">削除</button></td>';
                echo "<tr>";
                
                $count = $count + 1;
                $kin_total = $kin_total + ($row['s_uri'] * $row['su']);
                $su_total = $su_total + $row['su'];
            }
            echo '<tfoot><tr>
                  <td colspan = "3">合計</td>
                  <td>'.$su_total.'</td>
                  <td>'.$kin_total.'</td>
                  <td></td>
              </tr></tfoot>';
            $_SESSION['su_total'] = $su_total;
            $_SESSION['kin_total'] = $kin_total;
            
        echo '</table></form>';
    ?>

    
    <footer>
        <br>
        <div class = "div1">
            <form action="" method="post" >
                <div class = "lft"><input type = "submit" name = "back" value = "　　戻る　　"></div>
                <div class = "rgt">
                <input type="number" name="kin" value ="<?=$_SESSION['kin_total']?>" class ="kaikei_1">
                <input type="number" name="h_kin" placeholder="預り金" class ="kaikei_1">
                <input type="submit" name="kaikei" value = "お会計へ進む" class = "btn1">
                <br>
            
    <?php
        if(isset($_POST['kaikei'])){
            //echo 1;
            if(!empty($_SESSION['m_name'])){
                //echo 2;
                if(!empty($_POST['h_kin'])){
                    //echo 3;
                    $h_kin = $_POST['h_kin'];
                    if(($h_kin - $_SESSION['kin_total'])>=0){
                        //echo 4;
                        $oturi = $h_kin - $_SESSION['kin_total'];
                        $count = 0;
                        
                        //販売IDの計算
                        $sql = 'SELECT COUNT(*) AS cnt FROM '.$hanbai;
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $results = $stmt->fetchALL();
                        foreach($results as $row){
                            $count = $row['cnt'];
                        }
                        $h_id = $g_id * 1000 + $count + 1;
                        
                        //販売テーブルへの登録
                        $sql = 'INSERT INTO '.$hanbai.'(h_id, h_kin, h_oturi, h_date, m_id, g_id) VALUES (';
                        $sql = $sql.':h_id, :h_kin, :oturi, NOW(), :m_id, :g_id)';
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':h_id', $h_id, PDO::PARAM_INT);
                        $stmt->bindParam(':h_kin', $h_kin, PDO::PARAM_INT);
                        $stmt->bindParam(':oturi', $oturi, PDO::PARAM_INT);
                        $stmt->bindParam(':m_id', $_SESSION['m_id'], PDO::PARAM_INT);
                        $stmt->bindParam(':g_id', $g_id, PDO::PARAM_INT);
                        
                        $stmt->execute();
                        
                        //販売詳細用の仮想表の作成
                        $sql = 'SELECT S.s_id, S.s_name, SUM(H.su) AS su, SUM(H.su * S.s_uri) AS ukin,SUM(H.su * S.s_gen) AS gkin ';
                        $sql = $sql.'FROM '.$s_table.' AS S INNER JOIN '.$hold.' AS H ON H.s_id = S.s_id GROUP BY S.s_id, S.s_name';
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $results = $stmt->fetchALL();
                        
                        foreach($results as $row){
                            
                            //販売詳細テーブルへ登録
                            $sql = 'INSERT INTO '.$shosai.'(h_id,s_id,su,ukin,gkin) VALUES (:h_id,:s_id,:su,:ukin,:gkin)';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':h_id', $h_id, PDO::PARAM_INT);
                            $stmt->bindParam(':s_id', $row['s_id'], PDO::PARAM_INT);
                            $stmt->bindParam(':su', $row['su'], PDO::PARAM_INT);
                            $stmt->bindParam(':ukin', $row['ukin'], PDO::PARAM_INT);
                            $stmt->bindParam(':gkin', $row['gkin'], PDO::PARAM_INT);
                            $stmt->execute();
                            
                            //holdテーブルのデータを削除
                            $sql = 'DELETE FROM '.$hold;
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                        }
                        unset($_SESSION['su_total']);
                        unset($_SESSION['kin_total']);
                        echo '<span style = "font-size:25px;">！おつりは'.$oturi.'円です</span>';
                        echo '<input type="submit" name="rset" value = "ok" style = "font-size:25px;">';
                    }else{
                        echo '<span>！預り金が足りません</span>';
                    }
                }else{
                    echo '<span>！預り金を入力してください</span>';
                }
            }else{
                echo '<span>！担当者を入力してください</span>';
            }
            
        }
    ?>
        </div>
        </form></div>
    </footer>
</div>
<br>
    <?php
    function hold_reset($hold){
         // DB接続設定
        $dsn = 'mysql:dbname="データベース名";host=localhost';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $sql = "DELETE FROM ".$hold;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    function name_reset(){
        unset($_SESSION['m_id']);
        unset($_SESSION['m_name']);
    }    
    ?>

</body>



</html>