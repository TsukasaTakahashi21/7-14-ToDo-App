<?php
session_start();
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword,
);

// キーワードのデータを変数に代入
$search = isset($_GET['search']) ? '%' . $_GET["search"] . '%' : '%%';

// 指定された並び替えのデータを変数に代入
$order = isset($_GET['order']) ? $_GET['order'] : '';

// 完了状態のデータを変数に代入
$status = isset($_GET['status']) ? $_GET['status'] : ''; 

// カテゴリIDのデータを変数に代入
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

// キーワードが含まれるデータを取得
$sql = 'SELECT tasks.id, tasks.contents, tasks.deadline, tasks.status, tasks.category_id, categories.name as category_name FROM tasks LEFT JOIN categories ON tasks.category_id = categories.id WHERE tasks.contents LIKE :search OR categories.name LIKE :search';


// 完了状態が指定された場合の条件を追加
$sql .= ($status === 'done') ? ' AND tasks.status = 1' : ' AND tasks.status = 0';


// 並び替えの指定を設定
$sql .= ($order === 'asc') ? ' ORDER BY tasks.created_at ASC' : ' ORDER BY tasks.created_at DESC';

$statement = $pdo->prepare($sql);
$statement->bindValue(':search', $search, PDO::PARAM_STR);

// カテゴリIDが指定されている場合はバインドする
if (!empty($category_id)) {
  $sql .= ' AND tasks.category_id = :category_id';
  $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
}

$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>ヘッダー</title>
</head>
<body>
  <div class="header">
    <div class="header-title">
      <h2>ToDoアプリ</h2>
    </div>
    <div class="navi">
      <ul>
        <li><a href="index.php">ホーム</a></li>
        <li><a href="category/index.php">カテゴリ一覧</a></li>
        <li><a href="user/logout.php">ログアウト</a></li>
      </ul>
    </div>
  </div>
  <!-- 絞り込み機能 -->
  <h2>絞り込み検索</h2>
  <div class="filter">
    <form action="index.php" method="get">
      <!-- キーワード検索フォーム -->
      <div class="keyword">
        <input type="text" name="search" placeholder="キーワードで検索">
      </div>
      <!-- 作成日の並び替えフォーム -->
      <div class="order">
        <label>
          <input type="radio" name="order" value="desc" <?php echo isset($_GET['order']) && $_GET['order'] === 'desc' ? 'checked' : ''; ?>>
          <span>新着順</span>
        </label>
        <label>
          <input type="radio" name="order" value="asc" <?php echo isset($_GET['order']) && $_GET['order'] === 'asc' ? 'checked' : ''; ?>>
          <span>古い順</span>
        </label>
      </div>
      <!-- カテゴリ検索フォーム -->
      <div class="search-category">
        <select name="category_id">
          <option value="">カテゴリ</option>
          <?php
          $sql = 'SELECT id, name FROM categories';
          $statement = $pdo->query($sql);
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' .$row['id'] . '">' .$row['name']. '</option>';
          }
          ?>
        </select>
      </div>
      <!-- 完了未完了フォーム -->
      <div class="status">
        <label>
          <input type="radio" name="status" value="done" <?php echo isset($_get['status']) && $_get['status'] === 'done' ? 'checked' : ''; ?>>
          完了
        </label>
        <label>
          <input type="radio" name="status" value="yet" <?php echo isset($_GET['status']) && $_GET['status'] === 'yet' ? 'checked' : ''; ?>>
          未完了
        </label>
      </div>
      <button submit>検索</button>
    </form>
  </div>

  <a href="task/create.php">タスクを追加</a>
  <section class="task">
    <table border="1">
        <tr>
          <th>タスク名</th>
          <th>締切</th>
          <th>カテゴリー名</th>
          <th>完了未完了</th>
          <th>編集</th>
          <th>削除</th>
        </tr>
        <?php foreach ($tasks as $task): ?>
          <tr>
            <td><?php echo $task['contents']; ?></td>
            <td><?php echo $task['deadline']; ?></td>
            <td><?php echo $task['category_name']; ?></td>  
            <td>
              <!-- 未完了の場合の表示 -->
              <?php if ($task['status'] == 1): ?>
                <a href="task/updateStatus.php?id=<?= $task['id']; ?>&status=0">完了</a>
                <!-- 完了の場合の表示 -->
              <?php else: ?>
                <a href="task/updateStatus.php?id=<?= $task['id']; ?>&status=1">未完了</a>
              <?php endif; ?>
            </td>
            <td><button onclick="location.href='task/edit.php?id=<?php echo $task['id']; ?>'">編集</button></td>     
            <td><button onclick="location.href='task/delete.php?id=<?php echo $task['id']; ?>'">削除</button></td>
          </tr>
        <?php endforeach; ?>
      </table>
  </section>
  <a href="user/signup.php">Sign Up</a>

</body>
</html>


