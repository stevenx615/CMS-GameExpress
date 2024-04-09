<?php
session_start();
// print_r($_SESSION);
require 'utilities.php';
require 'authentication.php';
require 'db_connection.php';
require 'user-preference.php';

// get user setting preference
$preference = get_preference();

$sortby_query = get_post_sortby_query($preference['post_sortby']);
$orderby_query = get_orderby_query($preference['orderby']);

// how many posts display on top
$top_post_count = 2;

// retrieve posts
$posts_query = "SELECT * 
          FROM posts p
            JOIN users u ON p.author_id = u.user_id
            JOIN categories c ON p.category_id = c.category_id 
          WHERE c.category_name = 'News'" . $sortby_query . $orderby_query;
try {
  $posts_stmt = $db_conn->prepare($posts_query);
  $posts_stmt->execute();
} catch (PDOException $e) {
  die('There is an error when retrieving posts.');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Express - News</title>
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
      <div class="row mt-5" style="color:#929AA6;">
        <div class="col">
          <h1 class="post-category-title">News</h1>
        </div>
        <?php if (is_logged_in()) : ?>
          <div class="col text-end fs-6">
            <form name="list-preference-form" method="POST" action="user-preference.php">
              <label for="orderby">Sort by</label>
              <select class="custom-dropdown-light dropdown-orderby ms-1" name="post_sortby" id="post_sortby" onchange="this.form.submit()">
                <?php foreach (PostSortBy::cases() as $option) : ?>
                  <option value="<?= $option->value ?>" <?= $preference['post_sortby'] == $option->value ? 'selected' : '' ?>>
                    <?= $option->value ?></option>
                <?php endforeach ?>
              </select>
              <select class="custom-dropdown-light dropdown-orderby ms-1 me-4" name="orderby" id="orderby" onchange="this.form.submit()">
                <?php foreach (OrderBy::cases() as $option) : ?>
                  <option value="<?= $option->value ?>" <?= $preference['orderby'] == $option->value ? 'selected' : '' ?>>
                    <?= $option->value ?></option>
                <?php endforeach ?>
              </select>
              <select class="custom-dropdown-light dropdown-pagesize me-1" name="pagesize" id="pagesize" onchange="this.form.submit()">
                <?php foreach (PageSize::cases() as $option) : ?>
                  <option value="<?= $option->value ?>" <?= $preference['pagesize'] == $option->value ? 'selected' : '' ?>>
                    <?= $option->value ?></option>
                <?php endforeach ?>
              </select>
              <label for="pagesize">per page</label>
            </form>
          </div>
        <?php endif ?>
        <hr class="post-title-line">
        <div class="row">
          <?php
          for ($i = 0; $i < $top_post_count; $i++) :
            $row = $posts_stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
              break;
            }
          ?>
            <div class="col">
              <div class="post-top" style=" <?php
                                            if (!empty($row['post_thumbnail'])) {
                                              echo "background-image: url(" . $row['post_thumbnail'] . ");";
                                            }
                                            ?>">
                <div class="post-top-bar">
                  <h2 class="post-top-title"><a href="view.php?pid=<?= $row['post_id'] ?>"><?= $row['post_title'] ?></a>
                  </h2>
                  <div class="post-top-author">By <span class="author-text"><?= $row['first_name'] . ' ' . $row['last_name'] ?></span><span class="ms-5"><?= date('F d, Y', strtotime($row['post_modified_date'])) ?></span></div>
                </div>
              </div>
            </div>
          <?php endfor ?>
        </div>
        <hr class="post-line">
        <div class="row">
          <div class="col pe-5">
            <?php
            while ($row = $posts_stmt->fetch(PDO::FETCH_ASSOC)) :
            ?>
              <div class="post-row row">
                <div class="post-left col-4">
                  <?php if (!empty($row['post_thumbnail'])) : ?>
                    <a href="post_view.php?pid=<?= $row['post_id'] ?>"><img src="<?= $row['post_thumbnail'] ?>" class="post-thumbnail"></a>
                  <?php endif ?>
                </div>
                <div class="post-right col">
                  <div class="post-category"><?= $row['category_name'] ?></div>
                  <h2 class="post-title"><a href="view.php?pid=<?= $row['post_id'] ?>"><?= $row['post_title'] ?></a>
                  </h2>
                  <div class="post-author">By <span class="author-text"><?= $row['first_name'] . ' ' . $row['last_name'] ?></span><span class="ms-5"><?= date('F d, Y', strtotime($row['post_modified_date'])) ?></span></div>
                  <div></div>
                </div>
              </div>
              <hr class="post-line">
            <?php endwhile ?>
          </div>
          <div class="col-2">
            <?php
            include "template-sidebar-categories.php";
            ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>