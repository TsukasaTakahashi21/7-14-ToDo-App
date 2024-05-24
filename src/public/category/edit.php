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
  header('Location: index.php');
}
$id = $_GET['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_name = isset($_POST['category_name']) ? $_POST['category_name'] : '';

  if (empty($_POST['category_name'])) {
    $errors[] = 'カテゴリー名が入力されていません';
  }

  if (empty($errors)) {
    $sql = 'UPDATE categories SET name = :name WHERE id = :id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $category_name, PDO::PARAM_STR);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    header('Location: index.php');
    exit();
  } else {
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
    exit();
  }
} else {
  $sql = 'SELECT * FROM categories WHERE id = :id';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':id', $id, PDO::PARAM_INT);
  $statement->execute();
  $category = $statement->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カテゴリ編集</title>
</head>
<body>
  <?php if (!empty($_SESSION['errors'])): ?>
    <ul>
      <?php foreach ($_SESSION['errors'] as $error): ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['errors']); ?>
  <?php endif; ?>

  <form action="edit.php?id=<?php echo $id; ?>" method="post">
    <input type="text" name="category_name" value="<?php echo $category['name']; ?>">
    <button type="submit">更新</button>
  </form>
  <a href="index.php">戻る</a>
</body>
</html>