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

// postでのフォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';
  $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
  $contents = isset($_POST['contents']) ? $_POST['contents'] : '';
  $deadline = isset($_POST['deadline']) ? $_POST['deadline'] : '';

  // バリデーション
  if (empty($category_id)) {
    $errors[] = 'カテゴリが選択されていません';
  }
  if (empty($contents)) {
    $errors[] = 'タスク名が入力されていません';
  }
  if (empty($deadline)) {
    $errors[] = '締切日が入力されていません';
  }

  // エラーがない場合は更新処理を実行
  if (empty($errors) ){
    $sql = 'UPDATE tasks SET category_id = :category_id, contents = :contents, deadline = :deadline WHERE id = :id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $statement->bindValue(':contents', $contents, PDO::PARAM_STR);
    $statement->bindValue(':deadline', $deadline, PDO::PARAM_STR);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
  
    header('Location: ../../../index.php');
    exit();
  } else {
   // エラーがある場合はセッションにエラー情報を保存して再度編集画面を表示
  $_SESSION['errors'] = $errors;
  header('Location: edit.php?id=' . $id);
  exit();
  }
} else {
  header('Location: ../../../index.php');
  exit();
}
?>