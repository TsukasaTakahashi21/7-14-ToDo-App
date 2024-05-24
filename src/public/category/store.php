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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_name = isset($_POST['category_name']) ? $_POST['category_name'] : '';
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

  if(empty($category_name)) {
    $errors[] = 'カテゴリー名が入力されていません';
  }
  // カテゴリの新規登録
  if (empty($errors)) {
    $sql = 'INSERT INTO categories (name, user_id) VALUES (:name, :user_id)';
    $statement  = $pdo->prepare($sql);
    $statement->bindValue(':name', $category_name, PDO::PARAM_STR);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->execute();
  } else {
    $_SESSION['errors'] = $errors;
  }
  header('Location: index.php');
  exit();
}
?>
