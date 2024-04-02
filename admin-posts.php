<?php
session_start();
print_r($_SESSION);
require 'utilities.php';
require 'admin-authentication.php';
require 'admin-user-preference.php';

// check permission
if (!(is_logged_in() && has_role([1, 2]))) {
  redirect('index.php');
}

require 'db_connection.php';

// get user setting preference
$admin_preference = get_admin_preference();
$post_sortby_query = get_post_sortby_query($admin_preference['post_sortby']);
$orderby_query = get_orderby_query($admin_preference['orderby']);

$author_query = '';
if (has_role([2])) {
  $author_query = ' WHERE p.author_id = :user_id';
}

$query = "SELECT * 
          FROM posts p 
            JOIN categories c ON p.category_id = c.category_id
            JOIN users u ON p.author_id = u.user_id" . $author_query . $post_sortby_query . $orderby_query;

try {
  $statement = $db_conn->prepare($query);
  if (!empty($author_query)) {
    $statement->bindValue(':user_id', $_SESSION['user']['user_id']);
  }
  $statement->execute();
  $rows = $statement->fetchAll();
} catch (PDOException $e) {
  die('There is an error when retrieving posts.');
}

$posts_count = count($rows);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Center | Posts</title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  include "template-admin-header.php";
  ?>

  <main class="main d-flex justify-content-center flex-grow-1">
    <div class="wrap row">
      <?php
      include "template-admin-sidebar.php";
      ?>
      <div class="col ms-3">
        <div class="mt-4 d-flex justify-content-between align-items-end">
          <h1>Posts</h1>
          <a class="btn btn-green px-5 fw-bold" href="admin-posts-form.php?action=add">ADD</a>
        </div>
        <div>
          <div class="row mt-4" style="color:#929AA6;">
            <div class="col-4">Found <span style="color:#DEE0E0;"><?= $posts_count ?></span> Posts</div>
            <div class="col text-end fs-6">
              <form name="list-preference-form" method="POST" action="admin-user-preference.php">
                <label for="orderby">Sort by</label>
                <select class="custom-dropdown-light dropdown-orderby ms-1" name="post_sortby" id="post_sortby" onchange="this.form.submit()">
                  <?php foreach (PostSortBy::cases() as $option) : ?>
                    <option value="<?= $option->value ?>" <?= $admin_preference['post_sortby'] == $option->value ? 'selected' : '' ?>>
                      <?= $option->value ?></option>
                  <?php endforeach ?>
                </select>
                <select class="custom-dropdown-light dropdown-orderby ms-1 me-4" name="orderby" id="orderby" onchange="this.form.submit()">
                  <?php foreach (OrderBy::cases() as $option) : ?>
                    <option value="<?= $option->value ?>" <?= $admin_preference['orderby'] == $option->value ? 'selected' : '' ?>>
                      <?= $option->value ?></option>
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
          </div>
          <div class="admin-list-plane mt-3 p-2">
            <table class="admin-table">
              <thead>
                <tr>
                  <th style="width: 3rem;">#</th>
                  <th>Title</th>
                  <th style="width: 6rem;">Author</th>
                  <th style="width: 6rem;">Category</th>
                  <th style="width: 8rem;">Modified</th>
                  <th style="width: 8rem;">Controls</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $row) : ?>
                  <tr>
                    <td><?= $row['post_id'] ?></td>
                    <td><a href="admin-posts-form.php?action=edit&id=<?= $row['post_id'] ?>"><?= $row['post_title'] ?></a>
                    </td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['category_name'] ?></td>
                    <td><?= date('F d, Y', strtotime($row['post_modified_date'])) ?></td>
                    <td>
                      <a class="operate-icon tooltip-view" href="post_view.php?pid=<?= $row['post_id'] ?>"><img src=" images/icon-view.png" alt="View" /></a>
                      <a class="operate-icon tooltip-edit" href="admin-posts-form.php?action=edit&id=<?= $row['post_id'] ?>"><img src=" images/icon-edit.png" alt="Edit" /></a>
                      <a class="operate-icon tooltip-delete" href="admin-posts-process.php?action=delete&id=<?= $row['post_id'] ?>" onclick="return confirm('Are you sure you want to delete this post?')"><img src=" images/icon-delete.png" alt="Delete" /></a>
                    </td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-admin-footer.php";
  ?>
</body>

</html>