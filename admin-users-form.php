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
      $page_title = 'Add User';

      $role_query = "SELECT * FROM roles ORDER BY role_id DESC";
      try {
        $statement = $db_conn->prepare($role_query);
        $statement->execute();
        $role_rows = $statement->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die('There is an error when retrieving roles.');
      }
      break;
    };
  case 'edit': {
      $page_title = 'Edit User';
      $user_id = null;
      edit_validation($user_id);

      $role_query = "SELECT * FROM roles ORDER BY role_id DESC";
      try {
        $statement = $db_conn->prepare($role_query);
        $statement->execute();
        $role_rows = $statement->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die('There is an error when retrieving roles.');
      }

      $user_query = "SELECT * FROM users WHERE user_id = :user_id";
      try {
        $statement = $db_conn->prepare($user_query);
        $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
        $user_row = $statement->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die('There is an error when editing the user.');
      }

      if (empty($user_row)) {
        redirect('admin-users.php');
      }
      break;
    };
  case 'view': {
      $page_title = 'View User';
      $user_id = null;
      edit_validation($user_id);
      $user_query = "SELECT * FROM users u JOIN roles r ON u.role_id = r.role_id WHERE user_id = :user_id";
      try {
        $statement = $db_conn->prepare($user_query);
        $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
        $user_row = $statement->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die('There is an error when viewing the user.');
      }

      if (!$user_row) {
        redirect('admin-users.php');
      }

      $posts_query = "SELECT COUNT(*) FROM posts WHERE author_id = :user_id";
      try {
        $statement = $db_conn->prepare($posts_query);
        $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
        $post_count = $statement->fetchColumn();
      } catch (PDOException $e) {
        die('There is an error when viewing the user.');
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
    redirect('admin-users.php');
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
        <?php if ($action == 'add' || $action == 'edit') : ?>
          <div class="mt-4">
            <h1>
              <?= !empty($user_row) ? 'Edit' : 'Add' ?> User
            </h1>
          </div>
          <div>
            <?php
            if (!empty($user_row)) {
              $url_param = 'edit&id=' . $user_id;
            } else {
              $url_param = 'add';
            }
            ?>
            <form action="admin-users-process.php?action=<?= $url_param ?>" method="POST" class="admin-form" id="admin-form" onsubmit="validateFormRequiredPassword(event, ['username', 'firstname', 'lastname'], 'password', 'match_password'); ">
              <?php if (!empty($role_rows)) : ?>
                <label for="role_id">Role</label>
                <select class="custom-dropdown-admin" name="role_id" id="role_id">
                  <?php foreach ($role_rows as $role_row) : ?>
                    <option value="<?= $role_row['role_id'] ?>" <?php
                                                                if (!empty($user_row)) {
                                                                  echo $user_row['role_id'] == $role_row['role_id'] ?  'selected' : '';
                                                                }
                                                                ?>>
                      <?= $role_row['role_name'] ?>
                    </option>
                  <?php endforeach ?>
                </select>
              <?php endif ?>
              <label for="username">Username (ID)</label>
              <input type="text" name="username" id="username" style="width: 75%;" value="<?= !empty($user_row) ? $user_row['username'] : '' ?>" />
              <div class="field_error" id="username_error"></div>
              <label for="firstname">First Name</label>
              <input type="text" name="firstname" id="firstname" style="width: 75%;" value="<?= !empty($user_row) ? $user_row['first_name'] : '' ?>" />
              <div class="field_error" id="firstname_error"></div>
              <label for="lastname">Last Name</label>
              <input type="text" name="lastname" id="lastname" style="width: 75%;" value="<?= !empty($user_row) ? $user_row['last_name'] : '' ?>" />
              <div class="field_error" id="lastname_error"></div>
              <label for="email">Email</label>
              <input type="email" name="email" id="email" style="width: 75%;" value="<?= !empty($user_row) ? $user_row['email'] : '' ?>" />
              <div class="field_error" id="email_error"></div>
              <?php if (!empty($user_row)) : ?>
                <hr style="width: 75%;" />
                <div class="mt-2">
                  <p class="mb-4 py-2 text-center bg-black bg-opacity-25" style="color:#0BB85C;width: 75%;">* Leave the
                    password blank
                    if
                    you do not want to
                    change it.
                  </p>
                </div>
              <?php endif ?>
              <label for="password">Password</label>
              <input type="password" name="password" id="password" style="width: 75%;" />
              <div class="field_error" id="password_error"></div>
              <label for="match_password">Match Password</label>
              <input type="password" name="match_password" id="match_password" style="width: 75%;" />
              <div class="field_error" id="match_password_error"></div>
              <button type="submit" class="btn-green btn-submit">Submit</button>
            </form>
          </div>
        <?php elseif ($action == 'view') : ?>
          <div class="mt-4">
            <h1>
              User Details
            </h1>
          </div>
          <div class="mt-5 ms-5">
            <div class="row mt-4">
              <div class="col-2 text-end">Role: </div>
              <div class="col"><?= $user_row['role_name'] ?></div>
            </div>
            <div class="row mt-4">
              <div class="col-2 text-end">Username (ID): </div>
              <div class="col"><?= $user_row['username'] ?></div>
            </div>
            <div class="row mt-4">
              <div class="col-2 text-end">Name: </div>
              <div class="col"><?= $user_row['first_name'] . ' ' . $user_row['last_name'] ?></div>
            </div>
            <div class="row mt-4">
              <div class="col-2 text-end">Email: </div>
              <div class="col"><?= $user_row['email'] ?></div>
            </div>
            <div class="row mt-4">
              <div class="col-2 text-end">Posts Published: </div>
              <div class="col"><?= $post_count ?></div>
            </div>
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