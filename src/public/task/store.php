<?php
session_start();
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

$errors = [];

// バリデーションチェック
$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
$task_name = isset($_POST['task_name']) ? $_POST['task_name'] : '';
$due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '';

if (empty($category_id)) {
  $errors[] = 'カテゴリが選択されていません';
}

if (empty($task_name)) {
  $errors[] = 'タスク名が入力されていません';
}

if (empty($due_date)) {
  $errors[] = '締切日が入力されていません';
}

if (!empty($errors)) {
  $_SESSION['errors'] = $errors;
  header('Location: create.php');
  exit();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (is_null($user_id)) {
  $errors[] = 'ユーザーIDが取得できませんでした。ログインしてください。';
  $_SESSION['errors'] = $errors;
  header('Location: create.php');
  exit();
}

// タスク登録処理
$sql = 'INSERT INTO tasks (contents, deadline, category_id, user_id) VALUES (:contents, :deadline, :category_id, :user_id)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':contents', $task_name, PDO::PARAM_STR);
$statement->bindValue(':deadline', $due_date, PDO::PARAM_STR);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

header('Location: ../../../index.php');
exit();
?>
