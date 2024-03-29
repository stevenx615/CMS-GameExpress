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
      $error_msgs = add_categories_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-categories-process.php?action=error-messages&pre=add');
      }

      $category_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $query = "INSERT INTO categories (category_name) VALUES (:category_name)";

      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':category_name', $category_name);
        $statement->execute();
        redirect('admin-categories.php');
      } catch (PDOException $e) {
        die('There is an error when creating a new category.');
      }
      break;
    };
  case 'edit': {
      $error_msgs = edit_categories_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-categories-process.php?action=error-messages&pre=edit');
      }

      $category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $category_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $query = "UPDATE categories SET category_name = :category_name WHERE category_id = :category_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->bindValue(':category_name', $category_name);
        $statement->execute();
        redirect('admin-categories.php');
      } catch (PDOException $e) {
        die('There is an error when editing the category.');
      }
      break;
    };
  case 'delete': {
      $error_msgs = delete_categories_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-categories-process.php?action=error-messages&pre=delete');
      }

      $category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $query = "DELETE FROM categories WHERE category_id = :category_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();
        redirect('admin-categories.php');
      } catch (PDOException $e) {
        die('There is an error when deleting the category.');
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

      if (isset($_GET['pre'])) {
        $previous_action = $_GET['pre'];
      }
      break;
    };
  default:
    redirect('index.php');
    break;
}

function add_categories_validation()
{
  $error_msgs = [];
  if (empty($_POST['name'])) {
    $error_msgs[] = 'Category name is required.';
  }
  return $error_msgs;
}

function edit_categories_validation()
{
  $error_msgs = [];
  if (empty($_POST['name'])) {
    $error_msgs[] = 'Category name is required.';
  }
  if (empty($_GET['id'])) {
    $error_msgs[] = 'Category id is required.';
  } else {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($id)) {
      $error_msgs[] = 'Category id is required.';
    }
  }
  return $error_msgs;
}

function delete_categories_validation()
{
  $error_msgs = [];
  if (empty($_GET['id'])) {
    $error_msgs[] = 'Category id is required.';
  } else {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($id)) {
      $error_msgs[] = 'Category id is required.';
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
            <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="admin-categories-form.php?action=add">Back
                to
                Add Category
              </a>
            </p>
          <?php elseif ($previous_action == 'edit') : ?>
            <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="admin-categories.php">Back
                to
                Categories
              </a>
            </p>
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