<?php
session_start();
require 'utilities.php';
require 'admin-authentication.php';

// check permission
if (!(is_logged_in() && has_role([1]))) {
  redirect('index.php');
}

if (!isset($_GET['action'])) {
  redirect('index.php');
}

$action = $_GET['action'];

require 'db_connection.php';

switch ($action) {
  case 'add': {
      $page_title = 'Add Category';
      break;
    };
  case 'edit': {
      $page_title = 'Edit Post';
      $category_id = null;
      edit_validation($category_id);
      $query = "SELECT * FROM categories WHERE category_id = :category_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(":category_id", $category_id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die('There is an error when editing the category.');
      }

      if (empty($result)) {
        redirect('admin-categories.php');
      }
      break;
    };
  default:
    redirect('index.php');
    break;
}

function edit_validation(&$category_id)
{
  $isValid = true;
  if (!empty($_GET['id'])) {
    $category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($category_id)) {
      $isValid = false;
    }
  } else {
    $isValid = false;
  }
  if (!$isValid) {
    redirect('admin-categories.php');
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
        <?php if ($action == 'add') : ?>
          <div class="mt-4">
            <h1>Add Category</h1>
          </div>
          <div>
            <form action="admin-categories-process.php?action=add" method="POST" class="admin-form" id="admin-form" onsubmit="validateForm(event, ['name'])">
              <label for="game">Name</label>
              <input type="text" name="name" id="name" style="width: 75%;" />
              <div class="field_error" id="name_error"></div>
              <div>
                <button type="submit" class="btn-green btn-submit">Submit</button>
              </div>
            </form>
          </div>
        <?php elseif ($action == 'edit') : ?>

          <div class="mt-4">
            <h1>Edit Category</h1>
          </div>
          <div>
            <form action="admin-categories-process.php?action=edit&id=<?= $result['category_id'] ?>" method="POST" class="admin-form" id="admin-form" onsubmit="validateForm(event, ['name'])">
              <label for="game">Name</label>
              <input type="text" name="name" id="name" style="width: 75%;" value="<?= $result['category_name'] ?>" />
              <div class="field_error" id="name_error"></div>
              <div>
                <button type="submit" class="btn-green btn-submit">Submit</button>
              </div>
            </form>
          </div>
        <?php endif ?>
      </div>
    </div>
  </main>

  <?php
  include "template-admin-footer.php";
  print_r($_SESSION);
  ?>
</body>

</html>