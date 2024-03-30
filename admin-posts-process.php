<?php
session_start();
require 'utilities.php';
require 'admin-authentication.php';
require 'admin-upload-image.php';

//check permission
if (!(is_logged_in() && has_role([1, 2]))) {
  redirect('index.php');
}

if (!isset($_GET['action'])) {
  redirect('index.php');
}

$action = $_GET['action'];

require 'db_connection.php';

switch ($action) {
  case 'add': {
      $error_msgs = add_post_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-posts-process.php?action=error-messages&pre=add');
      }

      $cover_image = '';
      $cover_thumbnail_image = '';
      if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $files = $_FILES['cover'];
        $file_paths = handle_image_upload($files);
        $cover_image = $file_paths[0];
        $cover_thumbnail_image = $file_paths[1];
      }

      $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $content = $_POST['content'];
      $post_created_date = date('Y-m-d H:i:s');
      $post_modified_date = date('Y-m-d H:i:s');
      $author_id = $_SESSION['user']['user_id'];
      $user_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
      $game_id = filter_input(INPUT_POST, 'game_id', FILTER_SANITIZE_NUMBER_INT);

      if (empty($game_id)) {
        $game_id = null;
      }

      $query = "INSERT INTO posts (post_title, post_image, post_thumbnail, post_content, post_created_date, post_modified_date, author_id, category_id, game_id) VALUES (:post_title, :post_image, :post_thumbnail, :post_content, :post_created_date, :post_modified_date, :author_id, :category_id, :game_id)";

      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':post_title', $title);
        $statement->bindValue(':post_image', $cover_image);
        $statement->bindValue(':post_thumbnail', $cover_thumbnail_image);
        $statement->bindValue(':post_content', $content);
        $statement->bindValue(':post_created_date', $post_created_date);
        $statement->bindValue(':post_modified_date', $post_modified_date);
        $statement->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        $statement->bindValue(':category_id', $user_id, PDO::PARAM_INT);
        $statement->bindValue(':game_id', $game_id, PDO::PARAM_INT);
        $statement->execute();
        redirect('admin-posts.php');
      } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        die('There is an error when creating a new post.');
      }
      break;
    };
  case 'edit': {
      $error_msgs = edit_post_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-posts-process.php?action=error-messages&pre=edit');
      }

      $post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

      // retrieve the author id to validate permission 
      $post_query = "SELECT author_id FROM posts WHERE post_id = :post_id";
      try {
        $statement = $db_conn->prepare($post_query);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();
        $post_row = $statement->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die('There is an error when retrieving the post.');
      }
      $author_id = $post_row['author_id'];

      // if login user is not the author, then back to posts page
      if (!has_role([1, $author_id])) {
        $error_msgs[] = 'You do not have permission to edit this post.';
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-posts-process.php?action=error-messages');
      }

      $cover_image = '';
      $cover_thumbnail_image = '';
      if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $files = $_FILES['cover'];
        $file_paths = handle_image_upload($files);
        $cover_image = $file_paths[0];
        $cover_thumbnail_image = $file_paths[1];
      }

      $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $content = $_POST['content'];
      $post_modified_date = date('Y-m-d H:i:s');
      $user_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
      $game_id = filter_input(INPUT_POST, 'game_id', FILTER_SANITIZE_NUMBER_INT);

      if (empty($game_id)) {
        $game_id = null;
      }

      $query = "UPDATE posts SET post_title = :post_title, post_image = :post_image, post_thumbnail = :post_thumbnail, post_content = :post_content, post_modified_date = :post_modified_date, category_id = :category_id, game_id = :game_id WHERE post_id = :post_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':post_id', $post_id);
        $statement->bindValue(':post_title', $title);
        $statement->bindValue(':post_image', $cover_image);
        $statement->bindValue(':post_thumbnail', $cover_thumbnail_image);
        $statement->bindValue(':post_content', $content);
        $statement->bindValue(':post_modified_date', $post_modified_date);
        $statement->bindValue(':category_id', $user_id, PDO::PARAM_INT);
        $statement->bindValue(':game_id', $game_id, PDO::PARAM_INT);
        $statement->execute();
        redirect('admin-posts.php');
      } catch (PDOException $e) {
        die('There is an error when editing the post.');
      }
      break;
    };
  case 'delete': {
      $error_msgs = delete_post_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-posts-process.php?action=error-messages&pre=delete');
      }

      $post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
      $query = "DELETE FROM posts WHERE post_id = :post_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':post_id', $post_id);
        $statement->execute();
        redirect('admin-posts.php');
      } catch (PDOException $e) {
        die('There is an error when deleting the post.');
      }
      break;
    };
  case 'error-messages': {
      $page_title = 'Errors Occurred';
      $error_msgs = [];
      if (!empty($_SESSION['error_msgs'])) {
        $error_msgs = $_SESSION['error_msgs'];

        // clear session error_msgs field
        $_SESSION['error_msgs'] = '';
      } else {
        $error_msgs[] = 'Unknown errors ocurred.';
      }

      $previous_action = '';
      if (isset($_GET['pre'])) {
        $previous_action = $_GET['pre'];
      }
      break;
    };
  default:
    redirect('index.php');
    break;
}

function add_post_validation()
{
  $error_msgs = [];
  if (empty($_POST['category'])) {
    $error_msgs[] = 'Category is required.';
  }
  if (empty($_POST['title'])) {
    $error_msgs[] = 'Title is required.';
  }
  return $error_msgs;
}

function edit_post_validation()
{
  $error_msgs = [];
  if (empty($_POST['category'])) {
    $error_msgs[] = 'Category is required.';
  }
  if (empty($_POST['title'])) {
    $error_msgs[] = 'Title is required.';
  }
  if (empty($_GET['id'])) {
    $error_msgs[] = 'Post ID is required.';
  } else {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($id)) {
      $error_msgs[] = 'Post ID must be a number.';
    }
  }
  return $error_msgs;
}
function delete_post_validation()
{
  $error_msgs = [];
  if (empty($_GET['id'])) {
    $error_msgs[] = 'Post ID is required.';
  } else {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($id)) {
      $error_msgs[] = 'Post ID must be a number.';
    }
  }
  return $error_msgs;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Center - Message</title>
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
      <div class="col ms-5 mt-5 d-flex flex-column align-items-center">
        <?php if ($action == 'error-messages') : ?>
          <h1>The following errors must be resolved before continuing:</h1>
          <ul class=" ms-4 mt-4" style="list-style: disc; font-size: 1.2rem; color: #DEE0E0;">
            <?php foreach ($error_msgs as $msg) : ?>
              <li><?= $msg ?></li>
            <?php endforeach ?>
          </ul>
          <?php if ($previous_action == 'add') : ?>
            <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="admin-posts-form.php?action=add">Back
                to
                Add Post</a></p>
          <?php else : ?>
            <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="admin-posts.php">Back
                to Posts</a></p>
          <?php endif ?>
        <?php endif ?>
      </div>
    </div>
  </main>

  <?php
  include "template-admin-footer.php";
  ?>
</body>

</html>