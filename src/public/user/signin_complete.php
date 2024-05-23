<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword,
);

// エラーの初期化
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // POSTで送信された場合、値を変数に代入
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  // EmailかPasswordに入力がない場合
  if (empty($email) || empty($password)) {
    $errors[] = 'パスワードとメールアドレスを入力してください';
  }

  // ログインチェック
  $sql = 'SELECT * FROM users WHERE email = :email';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':email', $email, PDO::PARAM_STR);
  $statement->execute();
  $user = $statement->fetch(PDO::FETCH_ASSOC);

  if (!$user || !password_verify($password, $user['password'])) {
    $errors[] = 'メールアドレスまたはパスワードが違います';
    $_SESSION['errors'] = $errors;
    header('Location: signin.php');
    exit();
  } else {
  // ログイン成功時
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['name'];
  header('Location: ../index.php');
  exit();
  }
}
?>