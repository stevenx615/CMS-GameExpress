<?php
session_start();
print_r($_SESSION);
require 'utilities.php';
require 'authentication.php';
require 'db_connection.php';

// validate post Id
if (!validatePostId()) {
  redirect('index.php');
}

$post_id = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);

// retrieve posts
$query = "SELECT * 
          FROM posts p
            JOIN users u ON p.author_id = u.user_id
            JOIN categories c ON p.category_id = c.category_id 
          WHERE p.post_id = :post_id";
try {
  $statement = $db_conn->prepare($query);
  $statement->bindValue(":post_id", $post_id, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('There is an error when retrieving posts.');
}

// no data was found
if (empty($result)) {
  redirect('index.php');
}

// validate the post Id in URL 
function validatePostId()
{
  $valid = true;
  if (empty($_GET['pid'])) {
    $valid = false;
  } elseif (!filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT)) {
    $valid = false;
  }
  return $valid;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Express - <?= $result['post_title'] ?></title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  include "template-header.php";
  include "template-search.php";
  ?>

  <main class="main d-flex justify-content-center flex-grow-1">
    <div class="wrap">
      <article>
        <section class="view-heading row mt-5">
          <nav class="breadcrumb">
            <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="search.php?cid=<?= $result['category_id'] ?>"><?= $result['category_name'] ?></a></li>
            </ul>
          </nav>
          <h1><?= $result['post_title'] ?></h1>
          <div class="heading-author">By <span class="author-text"><?= $result['first_name'] . ' ' . $result['last_name'] ?></span>
            <span class="ms-3">published on <?= date('F d, Y', strtotime($result['post_modified_date'])) ?></span>
          </div>
          <?php if (!empty($result['post_image'])) : ?>
            <div class="heading-image">
              <img src="<?= $result['post_image'] ?>" alt="Game image">
            </div>
          <?php endif ?>
        </section>
        <section class="post-content">
          <?= $result['post_content'] ?>
        </section>
      </article>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>