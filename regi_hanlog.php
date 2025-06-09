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
        .tbl2{
            color :#581D75;
            border-collapse: collapse;
            border :5px double #79359B;
            background-color : white;
            text-align:center;
        }

        .th4{
             background-color : #D5ACEA;
            border-bottom:2px solid #79359B;
        }
        .th3{
            color :#FFFFFF;
            background-color : #AC59D5;
            border-bottom:2px solid #79359B;
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
            background-color : #F0E1F7;
            border:4px double #79359B;
            height:40px;
        }
        .lft{

            width : 20%;
            display: table-cell;
        }
        .cen{
            text-align :center;
            width : 60%;
            display: table-cell;
        }
        .rgt{
            text-align :right;
            width : 20%;
            display: table-cell;
        }
        .div1{
             display: table;
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
    session_start();
    $g_id = $_SESSION['id'];
    $hanbai = $g_id."_hanbai";//データの取得
    $shosai = $g_id."_shosai";
    $shohin = $g_id."_shohin";
    $member = $g_id."_member";
    
    
    ?>
<script>
function confirm_test() {
    var select = confirm("販売履歴を削除します、よろしいですか？\n「OK」で削除\n「キャンセル」で削除中止");
    return select;
}
</script>

<div class = "div1">
    <div class = "lft">
        <button onclick = "location.href= 'https://tech-base.net/tb-270028//regi/regi_start.php'">　　戻る　　</button>
    </div>
    <div class = "cen">
        <h1>販売履歴</h1>
    </div>
    <div class = "rgt">
        
        <form method="POST" action="regi_delete.php" onsubmit="return confirm_test()">
        <input type="submit" name="clear" value ="販売履歴を削除する">
        </form><br>
        <form action="" method="post">
        <select name='sortopt'>
            <?php
                $sql = "SELECT s_id,s_name FROM ".$shohin;
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($results as $row){
                    echo '<option value='.$row['s_id'].'>'.$row['s_name'].'</option>';
                }
            ?>
        </select>
        <input type="submit" name="sort" value ="ならべかえ">
        <input type="submit" name="reset" value ="リセット"><br>
        <?php
        if(isset($_POST['sort'])){
            $_SESSION['sortid'] = $_POST['sortopt'];
        }
        if(isset($_POST['reset'])){
            $_SESSION['sortid'] = "";
        }
       
        ?>
    </div>
    
</form>
</div>

    <?php
        //idからパスワードを抽出
        $sql = 'SELECT DISTINCT H.h_date,H.h_id,HS.hs_id,S.s_name,HS.su,HS.ukin,HS.gkin,M.m_name FROM ';
        $sql = $sql.$hanbai.' AS H INNER JOIN '.$shosai.' AS HS ON H.h_id = HS.h_id JOIN ';
        $sql = $sql.$shohin.' AS S ON HS.s_id = S.s_id JOIN ';
        $sql = $sql.$member.' AS M ON H.m_id = M.m_id ';
        if(!empty($_SESSION['sortid'])){
            $sql = $sql.'WHERE S.s_id = :id ';
        }
        $sql = $sql.'ORDER BY H.h_date ASC , HS.hs_id ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id',$_SESSION['sortid'], PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = 1;
        $su_total = 0;
        $kin_total = 0;
        $gen_total = 0;
        
    
    if(isset($_POST['expo'])){
       
        $filename = "log.csv";
        $fp = fopen($filename,"w");
        // CSVのヘッダー行を追加 
        
        fputcsv($fp, ["日時", "販売ID", "販売詳細ID", "商品名", "数量", "金額","原価", "担当者"]);
        
        foreach($results as $line){
            fputcsv($fp , $line);
        }
        fclose($fp);
        
        // 変換元のファイル（UTF-8 の内容だと仮定）を読み込む
        $contents = file_get_contents($filename);
        
        // UTF-8 から Shift-JIS（SJIS-win）に変換する
        $converted = mb_convert_encoding($contents, 'SJIS', 'UTF-8');
        
        // 変換後の内容を新しいファイルに保存する
        file_put_contents('output.csv', $converted);
        
    header("Location:https://tech-base.net/tb-270028/regi/download.php");
    exit;
        
    }
    
    echo '<div>';
    echo '<form action="" method="post"><table class = "tbl2">
            <tr class = "th3">
              <th colspan = "7">購入商品</th>
              <th><input type="submit" name="expo" value ="CSVに出力"></th>
            </tr>
            <tr class = "th4">
                <th style = "width :3%;">連番</th>
                 <th style = "width :10%;">日時</th>
                 <th style = "width :5%;">販売ID</th>
                 <th style = "width :8%;">商品名</th>
                 <th style = "width :5%;">数量</th>
                 <th style = "width :5%;">金額</th>
                 <th style = "width :5%;">原価</th>
                 <th style = "width :8%;">担当者</th>
          </tr>';
          
            foreach($results as $row){
                echo '<tr>';
                echo '<td class = "td2">'.$count.'</td>';
                echo '<td class = "td2">'.$row['h_date'].'</td>';
                echo '<td class = "td2">'.$row['h_id'].'</td>';
                echo '<td class = "td2">'.$row['s_name'].'</td>';
                echo '<td class = "td2">'.$row['su'].'</td>';
                echo '<td class = "td2">'.$row['ukin'].'</td>';
                echo '<td class = "td2">'.$row['gkin'].'</td>';
                echo '<td class = "td2">'.$row['m_name'].'</td>';
                echo "<tr>";
                
                $count = $count + 1;
                $su_total = $su_total + $row['su'];
                $kin_total = $kin_total + $row['ukin'];
                $gen_total = $gen_total + $row['gkin'];
            }
            $rieki = $kin_total - $gen_total;
            echo '<tfoot><tr>
                  <td colspan = "4">合計</td>
                  <td>'.$su_total.'</td>
                  <td>'.$kin_total.'</td>
                  <td>'.$gen_total.'</td>
                  <td>利益：'.$rieki.'</td>
                  
              </tr></tfoot>';
            $_SESSION['su_total'] = $su_total;
            $_SESSION['kin_total'] = $kin_total;
            

    ?>


    
 
</body>
</html>