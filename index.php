<?php
session_start();
print_r($_SESSION);
require 'authentication.php';
require 'db_connection.php';
require 'user-preference.php';

// get user setting preference
$admin_preference = get_preference();

// how many posts display on top
$top_post_count = 2;

$query = "SELECT * 
          FROM posts p
            JOIN users u ON p.author_id = u.user_id
            JOIN categories c ON p.category_id = c.category_id 
          WHERE p.category_id = 1 ORDER BY p.post_id " . $admin_preference['orderby'];
$statement = $db_conn->prepare($query);
$statement->execute();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News</title>
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
      <div class="row mt-4" style="color:#929AA6;">
        <div class="col">
          <h1 class="post-category-title">News</h1>
        </div>
        <div class="col text-end fs-6">
          <form name="list-preference-form" method="POST" action="admin-user-preference.php">
            <label for="orderby">Order by</label>
            <select class="custom-dropdown-light dropdown-orderby ms-1 me-4" name="orderby" id="orderby" onchange="this.form.submit()">
              <?php foreach (OrderBy::cases() as $option) : ?>
                <option value="<?= $option->value ?>" <?= $admin_preference['orderby'] == $option->value ? 'selected' : '' ?>>
                  <?= $option->value == 'desc' ? 'Latest' : 'Oldest' ?></option>
              <?php endforeach ?>
            </select>
            <select class="custom-dropdown-light dropdown-pagesize me-1" name="pagesize" id="pagesize" onchange="this.form.submit()">
              <?php foreach (PageSize::cases() as $option) : ?>
                <option value="<?= $option->value ?>" <?= $admin_preference['pagesize'] == $option->value ? 'selected' : '' ?>>
                  <?= $option->value ?></option>
              <?php endforeach ?>
            </select>
            <label for="pagesize">per page</label>
          </form>
        </div>
        <hr class="post-title-line">
        <div class="row">
          <?php
          for ($i = 0; $i < $top_post_count; $i++) :
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
              break;
            }
          ?>
            <div class="col">
              <div class="post-top">
                <img class="post-top-image" src="<?= $row['post_thumbnail'] ?>" alt="">
                <div class="post-top-bar">
                  <div><a href="post-top-title"><?= $row['post_title'] ?></a></div>
                  <div class="post-top-author"><?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                </div>
              </div>
            </div>
          <?php endfor ?>
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>