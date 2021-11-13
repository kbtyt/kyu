<!DOCTYPE html>

<html>

<head>
<meta charset="utf-8">
<title>Mission5-1</title>
</head>
    
<body>
    
    <h1>
        <font color="crimson">
        是非聞いてほしい!
        <?php
        echo "<br>";
        ?>
        あなたの好きな音楽、歌手を募集します!
        </font>
    </h1>
<?php

//DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//tbtestのテーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    $editname="";
    $editnumber="";
    $editpass="";
    $hiddennumber="";
    
    //新規送信
    if(isset($_POST["submitbutton"]) && empty($_POST["editing"])){
        
        //全て入力されてない
        if(empty($_POST["name"]) && empty($_POST["comment"]) && empty($_POST["pass"])){
            echo "名前とコメントとパスワードを入力してください<br>";
        
        //パスだけ入力
        }elseif(empty($_POST["name"])  && empty($_POST["comment"])){
            echo "名前とコメントを入力してください<br>";
        
        //コメントだけ入力
        }elseif(empty($_POST["name"]) && empty($_POST["pass"])){
            echo "名前とパスワードを入力してください<br>";
            
        //名前だけ入力
        }elseif(empty($_POST["comment"]) && empty($_POST["pass"])){
            echo "パスワードを入力してください<br>";
            
        //パスだけ入力されてない
        }elseif(empty($_POST["pass"])){
            echo "パスワードを入力してください<br>";
            
        //コメントだけ入力されてない
        }elseif(empty($_POST["comment"])){
            echo "コメントを入力してください<br>";
        
        //名前だけ入力されてない
        }elseif(empty($_POST["name"])){
            echo "名前を入力してください<br>";
            
        }else{
            
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $pass = $_POST["pass"];
            $sql -> execute();
            
            echo "「".$comment."」を投稿しました<br>";
        }
    }
    
    //削除(削除のところに文字を打つ)
    
    if(isset($_POST["deletebutton"])){
        
        //削除番号と削除パスの変数化
        $deletenumber=$_POST["deletenumber"];
        $deletepass=$_POST["deletepass"];
        
        //削除番号と削除パスがない
        if(empty($_POST["deletenumber"]) && empty($_POST["deletepass"])){
            echo "削除する投稿番号とパスワードを入力してください<hr>";
            
        //削除番号がない
        }elseif(empty($_POST["deletenumber"])){
            echo "削除する投稿番号を入力してください<hr>";
            
        //パスがない
        }elseif(empty($_POST["deletepass"])){
            echo "パスワードを入力してください<hr>";
            

        }else{
            
            //入力されたデータレコードの表示
            //sqlの該当箇所を抽出
            $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            
            //差し替えたパラメータを含めたsqlの準備
            $stmt = $pdo->prepare($sql);
            
            //差し替えるパラメータ(削除番号)の値の指定
            $stmt->bindParam(':id', $deletenumber, PDO::PARAM_INT);
            
            //sqlの実行
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach($results as $row){
                
                //$deletepass2に'pass'の列にあるものを代入
                $deletepass2=$row['pass'];
            }
            
            //パスが一致
            if($deletepass==$deletepass2){
                
                //入力したデータレコードの削除
                //場所の変数化と指定
                $id = $_POST["deletenumber"];
                $sql = 'delete from tbtest where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                echo "投稿番号".$deletenumber."の投稿を削除しました<hr>";
                
            }else{
                echo "パスワードが間違っています<hr>";
            }
        }
    }
    
    //編集(編集のところに文字を打つ)
    if(isset($_POST["editbutton"])){
        
        //編集番号とパスの変数化
        $editnumber=$_POST["editnumber"];
        $editpass=$_POST["editpass"];
        
        //編集番号とパスがない
        if(empty($_POST["editnumber"]) && (empty($_POST["editpass"]))){
            echo "編集する投稿番号とパスワードを入力してください<hr>";
            
        //編集番号がない
        }elseif(empty($_POST["editnumber"])){
            echo "編集する投稿番号を入力してください<hr>";
            
        //パスワードがない
        }elseif(empty($_POST["editpass"])){
            echo "パスワードを入力してください<hr>";
            
        }else{
            //入力されたデータレコードの表示
            //sqlの該当箇所を抽出
            $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            
            //差し替えたパラメータを含めたsqlの準備
            $stmt = $pdo->prepare($sql);
            
            //差し替えるパラメータ(編集番号)の値の指定
            $stmt->bindParam(':id', $editnumber, PDO::PARAM_INT);
            
            //sqlの実行
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach($results as $row){
                
                $editpass2=$row['pass'];
            }
            
            //パスワードが一致
            if($editpass==$editpass2){
                
                //＄resultsをそれぞれの＄rowへ
                foreach($results as $row){
                    
                    //それぞれのテーブルの列を変数化
                    $hiddennumber=$row['id'];
                    $editname=$row['name'];
                    $editcomment=$row['comment'];
                    $editpass=$row['pass'];
                    
                    echo "投稿番号".$hiddennumber."を編集しています<hr>";
                }
                
            //パスワードが間違っている
            }else{
                echo "パスワードが間違っています<hr>";
            }
        }
    }
    
    //呼び出しの完了
    
    //上書き(新規投稿のところに文字を打つ)
    //投稿ボタン＋(編集中なら)editingが埋まってる
    if(isset($_POST["submitbutton"]) && !empty($_POST["editing"])){
        
        $id = $_POST["editing"];
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date =  date("Y/m/d H:i:s");
        $pass = $_POST["pass"];
        $sql = 'UPDATE tbtest SET name=:name, comment=:comment, date=:date, pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        echo "投稿番号".$id."の投稿を編集しました<hr>";
    }
?>

<!--送信フォーム-->
    <form action="" method="post">
        
        <input type="text" name="name" placeholder="名前" 
            value= "<?php if(isset($_POST["editbutton"]) && !empty($_POST["editnumber"]) && !empty($_POST["editpass"]) ){ echo $editname; } ?>" >
        
        <input type="text" name="comment" placeholder="コメント" 
            value= "<?php if(isset($_POST["editbutton"]) && !empty($_POST["editnumber"]) && !empty($_POST["editpass"]) ){ echo $editcomment; } ?>" >
        
        <input type="password" name="pass" placeholder="パスワード" value= "<?php echo $editpass; ?>">
            
        <input type="submit" name="submitbutton">
        
        <!--編集中かどうかを判断するために使うフォーム-->
        <input type="hidden" name="editing" 
            value= "<?php if(isset($_POST["editbutton"])){ echo $hiddennumber; } ?>">
        
    </form>
    
    <!--削除フォーム-->
    <form action="" method="post">
        <input type="number" name="deletenumber" placeholder="削除対象番号">
        <input type="password" name="deletepass" placeholder="パスワード">
        <input type="submit" name="deletebutton" value="削除">
    </form>
    
    <!--編集番号指定用フォーム-->
    <form action="" method="post">
        <input type="number" name="editnumber" placeholder="編集対象番号">
        <input type="password" name="editpass" placeholder="パスワード">
        <input type="submit" name="editbutton" value="編集">
    </form>
    
    <hr>
    
    <?php
    //ブラウザ表示
    //該当箇所の抽出
    $sql = 'SELECT * FROM tbtest';
    
    //実行
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    //$resultsそれぞれを$rowへ
    foreach($results as $row){
        
        echo $row['id'].". ";
        echo '<span style="font-color:dodgerblue;">'.$row['name'].'</span>'."　さんの投稿　";
        echo $row['date']."<br>";
        echo '<span style="font-size:24px">'.$row['comment']."<br>".'</span>';
    }
    ?>
</body>
</html>