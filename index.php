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
  <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
  <?php require 'templates/head.php' ?>
  <title>Echo Zone..</title>
</head>

<body>
  <div class="title">
    <?php require "templates/title.php" ?>
  </div>

  <?php if ($notFound): ?>
    <div class="error box">
      Error: cannot find the requested blog post.
    </div>
  <?php endif ?>

  <div class="post-list">
    <?php foreach ($posts as $post): ?>
      <div class="card post-synopsis">
        <div class="card-header">
          <h2>
            <?php echo htmlEscape($post['title']) ?>
          </h2>
        </div>
        <div class="card-title meta">
          <?php echo convertSqlDate($post['created_at']) ?>

          (
          <?php echo $post['comment_count'] ?> comments)
        </div>
        <div class="card-text">
          <h5>
            <?php echo htmlEscape($post['body']) ?>
          </h5>
        </div>
        <div class="post-controls">
          <div class="btn btn-primary control-btn-rm">
            <a href="view-post.php?post_id=<?php echo $post['id'] ?>">Read more...</a>
          </div>
          <?php if (isLoggedIn()): ?>
            <div class="btn btn-primary control-btn-edit">
              <a href="edit-post.php?post_id=<?php echo $post['id'] ?>">Edit</a>
            </div>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</body>

</html>