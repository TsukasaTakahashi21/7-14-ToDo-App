<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : '';
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>タスク作成画面</title>
</head>
<body>
  <!-- エラー表示 -->
  <?php if (!empty($errors)): ?>
    <ul>
      <?php foreach ($errors as $error): ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <!-- タスク作成フォームの表示 -->
  <a href="../category/index.php">カテゴリを追加</a>
  <form action="store.php" method="post">
    <select name="category_id" id="category">
      <option value="">カテゴリを選んでください</option>
      <?php
      $dbUserName = 'root';
      $dbPassword = 'password';
      $pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);
      $sql = 'SELECT id, name FROM categories';
      $statement = $pdo->query($sql);
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
      }
      ?>
    </select>
    <input type="text" name="task_name" placeholder="タスクを追加">
    <input type="date" name="due_date">
    <button type="submit">追加</button>
  </form>
  <a href="../index.php">戻る</a>
</body>
</html>l