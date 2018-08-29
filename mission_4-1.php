<meta charset="UTF-8">
<html lang = "ja">
<head>
</head>
<body>
<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password='パスワード';
$pdo = new PDO($dsn,$user,$password);

$sql ="CREATE TABLE keiziban"
."("
."id INT,"
."name char(32),"
."comment TEXT,"
."date TEXT"
.");";
$stmt = $pdo->query($sql);

$sql ='SELECT * FROM keiziban';
$result = $pdo -> query($sql);
foreach ($result as $row){
 if($count<$row['id']+1){
  $count = $row['id']+1;
 }
 if($row['id']==0){
  $pass = $row['comment'];
 }
}
if(empty($count)){
 $count=1;
}
 $hensyu = 0;
 $Hensyu[] = 0;
 if(!($_POST["hensyu"]==0)){
  $sql ='SELECT * FROM keiziban';
  $result = $pdo -> query($sql);
  foreach ($result as $row){
   if($_POST["hensyu"]==$row['id']){
    $Hensyu[0] = $row['id'];
    $Hensyu[1] = $row['name'];
    $Hensyu[2] = $row['comment'];
    $hensyu = 1;
   }
  }
 }
 if(!($_POST["password_a"] == $pass) && !empty($_POST["name"]) && empty($_POST["hensyunum"])){
  echo 'パスワードが違います<br>';
 }
 if(!($_POST["password_b"] == $pass) && !empty($_POST["delete"])){
  echo 'パスワードが違います<br>';
 }
 if(!($_POST["password_c"] == $pass) && !empty($_POST["hensyu"])){
  echo 'パスワードが違います<br>';
  $hensyu=0;
 }
?>

<form action = "mission_4-1.php" method = "post">
 <input type = "text" name="name" placeholder="名前" value = <?php if(!($hensyu==0)) echo $Hensyu[1]; ?>><br>
 <input type = "text" name="comment" placeholder="コメント" value = <?php if(!($hensyu==0)) echo $Hensyu[2]; ?>><br>
 <input type = "text" name="password_a" placeholder="パスワード">
 <input type="hidden" name="hensyunum" value = <?php if(!($hensyu==0)) echo $_POST["hensyu"]; ?>>
<!--hensyunumは対象を編集するときにどれを選択したかを記録するフォーム--!>
 <input type = "submit" value = "送信"><br><br>
 <input type = "text" name="delete" placeholder="削除対象番号"><br>
 <input type = "text" name="password_b" placeholder="パスワード">
 <input type = "submit" value = "削除"><br><br>
 <input type = "text" name="hensyu" placeholder="編集対象番号"><br>
 <input type = "text" name="password_c" placeholder="パスワード">
 <input type = "submit" value = "編集">
<!--hensyuはどれを編集するか選択するフォーム-->
</form>

<?php
echo "<hr>";

$sql ='SELECT * FROM keiziban';
$result = $pdo -> query($sql);
$timestamp = time();
$date = date("Y年n月j日　G:i:s", $timestamp);
foreach ($result as $row){
 if($count<$row['id']+1){
  $count = $row['id']+1;
 }
 if($row['id']==0){
  $password = $row['comment'];
 }
}
if(empty($count)){
 $count=1;
}

if(!empty($_POST['name'])){
 if(empty($_POST['hensyunum']) && ($_POST['password_a']==$pass)){
  $sql = $pdo -> prepare("INSERT INTO keiziban (id,name,comment,date) VALUES ($count,:name,:comment,:date)");
  $sql -> bindParam(':name',$name,PDO::PARAM_STR);
  $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
  $sql -> bindParam(':date',$date,PDO::PARAM_STR);
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $sql -> execute();
 }
 else{
  $id = $_POST['hensyunum'];
  $nm = $_POST['name'];
  $kome = $_POST['comment'];
  $sql = "update keiziban set name = '$nm',comment = '$kome',date='$date' where id = $id";
  $result = $pdo->query($sql);
 }
}

if(!empty($_POST['delete']) && !($_POST['delete']==0) && ($_POST['password_b']==$pass)){
 $id = $_POST['delete'];
 $sql ="delete from keiziban where id = $id";
 $result = $pdo->query($sql);
}

$icount = 1;
while($icount <= $count){
 $sql ='SELECT * FROM keiziban';
 $result = $pdo -> query($sql);
 foreach ($result as $row){
  //$rowの中にはテーブルのカラム名が入る
  if($icount == $row['id']){
   echo $row['id'].',';
   echo $row['name'].',';
   echo $row['comment'].',';
   echo $row['date'].'<br>';
  }
 }
 $icount = $icount+1;
}

?>
</body>
</html>