<?php
require_once "lib/common.php";

session_start();

// Connect to the database, run a query, handle error
$pdo = getPDO();
$posts = getAllPosts($pdo);

$notFound = isset($_GET['not-found']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <?php require 'templates/head.php' ?>
  <title>Echo Zone</title>
</head>

<body>
  <?php require "templates/title.php" ?>

  <?php if ($notFound): ?>
    <div class="error box">
      Error: cannot find the requested blog post.
    </div>
  <?php endif ?>

  <div class="post-list">
    <?php foreach ($posts as $post): ?>
      <div class="post-synopsis">
        <h2>
          <?php echo htmlEscape($post['title']) ?>
        </h2>
        <div class="meta">
          <?php echo convertSqlDate($post['created_at']) ?>

          (
          <?php echo countCommentsForPost($pdo, $post['id']) ?> comments)
        </div>
        <p>
          <?php echo htmlEscape($post['body']) ?>
        </p>
        <div class="post-controls">
          <a href="view-post.php?post_id=<?php echo $post['id'] ?>">Read more...</a>
          <?php if (isLoggedIn()): ?>
            |
            <a href="edit-post.php?post_id=<?php echo $post['id'] ?>">Edit</a>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</body>

</html>