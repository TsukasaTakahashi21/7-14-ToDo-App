<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword,
);

if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit();
}

$id = $_GET['id'];
$errors = [];

// タスクで使用されているカテゴリの確認
$sql = 'SELECT COUNT(*) as count FROM tasks WHERE category_id = :id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$count = $statement->fetch(PDO::FETCH_ASSOC)['count'];

if ($count > 0) {
  $errors[] = '現在タスクで使用されているので削除できません';
} else {
  $sql = 'DELETE FROM categories WHERE id = :id';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':id', $id, PDO::PARAM_INT);
  $statement->execute();
}

if (!empty($errors)) {
  $_SESSION['errors'] = $errors;
}

header('Location: index.php');
exit();
?>