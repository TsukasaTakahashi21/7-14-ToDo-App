<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : '';
unset($_SESSION['errors']);

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword,
);

// カテゴリー一覧の取得
$sql = 'SELECT * FROM categories';
$statement = $pdo->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <title></title>
</head>
<body>
  <h2>カテゴリ一覧</h2>

  <!-- エラー表示 -->
  <?php if(!empty($errors)): ?>
    <ul>
      <?php foreach($errors as $error): ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form action="store.php" method="post">
    <input type="text" name="category_name" placeholder="タスクを追加">
    <button type="submit">登録</button>
  </form>

  <?php foreach ($categories as $category): ?>
    <div class="category_type">
      <p><?php echo $category['name']; ?></p>
      <button onclick="location.href='edit.php?id=<?php echo $category['id']; ?>'">編集</button>
      <button onclick="location.href='delete.php?id=<?php echo $category['id']; ?>'">削除</button>
    </div>
  <?php endforeach; ?>
  
  <a href="../task/create.php">戻る</a>
</body>
</html>