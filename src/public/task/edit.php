<?php
session_start();
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword,
);

$errors = [];

if (!isset($_GET['id'])) {
  header('Location: edit.php');
  exit();
}
$id = $_GET['id'];

// 編集するタスク情報の取得
$sql = 'SELECT * FROM tasks WHERE id = :id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$task = $statement->fetch(PDO::FETCH_ASSOC);

// エラーメッセージの取得
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>タスク編集</title>
  <!-- エラーメッセージ表示 -->
  <?php if (!empty($errors)): ?>
    <ul>
      <?php foreach($errors as $error): ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</head>

<body>
  <form action="./update.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <select name="category_id" id="category">
      <option value="">カテゴリを選んでください</option>
      <?php
      // データベース接続
      $dbUserName = 'root';
      $dbPassword = 'password';
      $pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);
      $sql = 'SELECT id, name FROM categories';
      $statement = $pdo->query($sql);
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' .$row['id'] . '">' . $row['name'] . '</option>';
      }
      ?>
    <input type="text" name="contents" id="contents" value="<?php echo $task['contents']; ?>">
    <input type="date" name="deadline" id="deadline", value="<?php $task['deadline']; ?>">
    <button type="submit">更新</button>
  </form>
  <a href="../index.php">戻る</a>
</body>
</html>
