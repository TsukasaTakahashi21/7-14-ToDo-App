<?php
session_start();
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

// タスクIDと完了状態を取得
$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = isset($_GET['status']) ? intval($_GET['status']) : 0;

if ($task_id > 0 && ($status === 0 || $status === 1)) {
  // タスクの完了状態を更新
  $sql = 'UPDATE tasks SET status = :status WHERE id = :task_id';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':status', $status, PDO::PARAM_INT);
  $statement->bindValue(':task_id', $task_id, PDO::PARAM_INT);
  $statement->execute();

  // タスク一覧ページにリダイレクト
  header('Location: ../index.php');
  exit();
} 
