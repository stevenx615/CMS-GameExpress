<?php
session_start();
require 'utilities.php';
require 'admin-authentication.php';

// check permission
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
      $page_title = 'Add Post';
      $category_rows = get_categories($db_conn);
      break;
    };
  case 'edit': {
      $page_title = 'Edit Post';
      $post_id = null;
      edit_validation($post_id);
      $category_rows = get_categories($db_conn);
      $query = "SELECT *, u.username, u.first_name, u.last_name
                FROM posts p 
                  INNER JOIN users u ON p.author_id = u.user_id
                WHERE post_id = :post_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $statement->execute();
        $post_row = $statement->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        echo "" . $e->getMessage() . "";
        die('There is an error when editing the post.');
      }

      if (empty($post_row)) {
        redirect('admin-posts.php');
      }

      $user_id = $post_row['category_id'];
      $game_id = $post_row['game_id'];
      $author_id = $post_row['author_id'];
      $post_thumbnail = $post_row['post_thumbnail'];
      $post_title = $post_row['post_title'];
      $post_content = $post_row['post_content'];
      $post_created_date = $post_row['post_created_date'];
      $post_modified_date = $post_row['post_modified_date'];
      $author_username = $post_row['username'];
      $author_fullname = $post_row['first_name'] . ' ' . $post_row['last_name'];

      // if login user is not the author, then back to posts page
      if ($author_id != $_SESSION['user']['user_id']) {
        if (!has_role([1])) {
          $error_msgs[] = 'You do not have permission to edit this post.';
          $_SESSION['error_msgs'] = $error_msgs;
          redirect('admin-posts-process.php?action=error-messages');
        }
      }


      if (empty($post_thumbnail)) {
        $post_thumbnail = 'images/placeholder_cover_picture.png';
      }
      // retrieve the game info
      $game_name = '';
      if (!empty($game_id)) {
        $game_query = "SELECT * from games WHERE game_id = :game_id";
        try {
          $statement = $db_conn->prepare($game_query);
          $statement->bindValue(":game_id", $game_id, PDO::PARAM_INT);
          $statement->execute();
          $game_row = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          die('There is an error when retrieving game info.');
        }
        $game_name = $game_row['game_name'];
      }

      break;
    };
  case 'delete': {
      $page_title = 'Delete Post';
      break;
    };
  default:
    redirect('index.php');
    break;
}

function get_categories($db_conn)
{
  $query = "SELECT * FROM categories";
  try {
    $statement = $db_conn->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die('There is an error when fetching the categories.');
  }
}

function edit_validation(&$post_id)
{
  $isValid = true;
  if (!empty($_GET['id'])) {
    $post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($post_id)) {
      $isValid = false;
    }
  } else {
    $isValid = false;
  }
  if (!$isValid) {
    redirect('admin-posts.php');
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Center - <?= $page_title ?></title>
  <?php
  include "template-head.php";
  ?>
  <script src="https://cdn.tiny.cloud/1/sd2wcyddmokf5gis0si7924owx7xfi0vbbgkzubommmbgy53/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea#content',
      height: 800,
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar1: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough',
      toolbar2: 'link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
      font_size_formats: '8px 10px 12px 14px 16px 18px 24px 36px 48px',

      // without images_upload_url set, Upload tab won't show up
      images_upload_url: 'admin-tinymce-upload-handler.php'
    });
  </script>
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
        <div class="mt-4">
          <h1><?= empty($post_row) ? 'ADD' : 'Edit' ?> Post</h1>
        </div>
        <div>
          <form action="admin-posts-process.php?action=<?= empty($post_row) ? 'add' : 'edit&id=' . $post_id ?>" method="POST" class="admin-form" id="admin-form" enctype="multipart/form-data" onsubmit="validateForm(event, ['title'])">
            <div class="row">
              <div class="col-5">
                <label for="cover">Cover Picture</label>
                <input class="admin-form-file" type="file" name="cover" id="cover" onchange="loadFile(event)" />
                <?php if (!empty($post_row)) : ?>
                  <label>Author ID</label>
                  <div class="admin-form-field-plain-text"><?= $author_username ?></div>
                  <label>Author</label>
                  <div class="admin-form-field-plain-text"><?= $author_fullname ?></div>
                  <label>Created Date</label>
                  <div class="admin-form-field-plain-text"><?= $post_created_date ?></div>
                  <label>Modified Date</label>
                  <div class="admin-form-field-plain-text"><?= $post_modified_date ?></div>
                <?php endif ?>
                <label for="category">Category</label>
                <select class="custom-dropdown-admin" name="category" id="category">
                  <?php foreach ($category_rows as $category_row) : ?>
                    <option value="<?= $category_row['category_id'] ?>" <?php if (!empty($post_row)) {
                                                                          echo $post_row['category_id'] == $category_row['category_id'] ? 'selected' : '';
                                                                        } ?>>
                      <?= $category_row['category_name'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="col">
                <label for="cover">Cover Picture Preview</label>
                <img class="rounded-3" src="<?= empty($post_row) ? 'images/placeholder_cover_picture.png' : $post_thumbnail ?>" alt="Cover Picture Preview" id="cover-preview" width="300px">
              </div>
            </div>
            <label for="game">Game</label>
            <input type="text" name="game" id="game" value="<?= empty($post_row) ? '' : $game_name ?>" style="width: 75%;" placeholder="Not function yet. Planning to use AJAX to fetch Game data." />
            <input type="hidden" name="game_id" id="game_id">
            <hr>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?= empty($post_row) ? '' : $post_title ?>" style="width: 100%;" />
            <div class="field_error" id="title_error"></div>
            <label for="content">Content</label>
            <textarea name="content" id="content"><?= empty($post_row) ? '' : $post_content ?></textarea>
            <div>
              <button type="submit" class="btn-green btn-submit"><?= !empty($post_row) ? 'Update' : 'Add' ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-admin-footer.php";
  print_r($_SESSION);
  ?>


</body>

</html>