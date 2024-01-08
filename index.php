<?php
require_once "lib/common.php";

session_start();

// Connect to the database, run a query, handle error
$pdo = getPDO();
$stmt = $pdo->query(
  'SELECT
        id, title, created_at,body
  FROM
      post
  ORDER BY
      created_at DESC'
);
if ($stmt === false) {
  throw new Exception("There was a problem running this query");
}

$notFound = isset($_GET['not-found']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Echo Zone</title>
</head>

<body>
  <?php require "templates/title.php" ?>

  <?php if ($notFound): ?>
    <div style="border: 1px solid #ff6666; padding: 6px;">
      Error: cannot find the requested blog post.
    </div>
  <?php endif ?>

  <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <h2>
      <?php echo htmlEscape($row['title']) ?>
    </h2>
    <div>
      <?php echo convertSqlDate($row['created_at']) ?>

      (
      <?php echo countCommentsForPost($row['id']) ?> comments
      )

    </div>
    <p>
      <?php echo htmlEscape($row['body']) ?>
    </p>
    <p>
      <a target="blank" href="view-post.php?post_id=<?php echo $row['id'] ?>">
        Read more...
      </a>
    </p>
  <?php endwhile ?>
</body>

</html>