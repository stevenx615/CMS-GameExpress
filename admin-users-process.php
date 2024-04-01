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
      $error_msgs = add_users_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-users-process.php?action=error-messages&pre=add');
      }

      $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
      $first_name = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $last_name = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // password hashing
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

      try {
        $query = 'INSERT INTO users (username, password, email, first_name, last_name, role_id) VALUES (:username, :password, :email, :first_name, :last_name, :role_id)';
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':role_id', $role_id);
        $success = $statement->execute();
        $user_id = $db_conn->lastInsertId();
      } catch (PDOException $ex) {
        die('There is an error when creating user.');
      }
      redirect('admin-users.php');
      break;
    };
  case 'edit': {
      $error_msgs = edit_users_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-users-process.php?action=error-messages&pre=edit');
      }

      $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
      $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
      $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $first_name = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $last_name = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $password_query = '';
      if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_query = ', password = :password';
      }

      $query = 'UPDATE users SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, role_id = :role_id' . $password_query . ' WHERE user_id = :user_id';

      try {
        $statement = $db_conn->prepare($query);
        if (!empty($password_query)) {
          $statement->bindValue(':password', $password);
        }
        $statement->bindValue(':username', $username);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':role_id', $role_id);
        $success = $statement->execute();
      } catch (PDOException $e) {
        die('There is an error when editing the user.');
      }
      redirect('admin-users.php');
      break;
    };
  case 'delete': {
      $error_msgs = delete_users_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('admin-users-process.php?action=error-messages&pre=delete');
      }

      $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $query = "DELETE FROM users WHERE user_id = :user_id";
      try {
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        redirect('admin-users.php');
      } catch (PDOException $e) {
        die('There is an error when deleting the user.');
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

function add_users_validation()
{
  $error_msgs = [];
  if (empty($_POST['role_id'])) {
    $error_msgs[] = 'Role is required.';
  } else {
    $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($role_id)) {
      $error_msgs[] = 'Role is required.';
    }
  }
  if (empty($_POST['username'])) {
    $error_msgs[] = 'Username is required.';
  }
  if (empty($_POST['password'])) {
    $error_msgs[] = 'Password is required.';
  }
  if ($_POST['password'] != $_POST['match_password']) {
    $error_msgs[] = 'Password and Confirm Password must be match.';
  }
  if (!empty($_POST['email'])) {
    if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
      $error_msgs[] = 'A valid email is required.';
    }
  }
  if (empty($_POST['firstname'])) {
    $error_msgs[] = 'First name is required.';
  }
  if (empty($_POST['lastname'])) {
    $error_msgs[] = 'Last name is required.';
  }
  return $error_msgs;
}

function edit_users_validation()
{
  $error_msgs = [];
  if (empty($_GET['id'])) {
    $error_msgs[] = 'User Id is required.';
  } else {
    $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($user_id)) {
      $error_msgs[] = 'User ID is required.';
    }
  }
  if (empty($_POST['role_id'])) {
    $error_msgs[] = 'Role is required.';
  } else {
    $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($role_id)) {
      $error_msgs[] = 'Role is required.';
    }
  }
  if (empty($_POST['username'])) {
    $error_msgs[] = 'Username is required.';
  }
  if (!empty($_POST['password']) && $_POST['password'] != $_POST['match_password']) {
    $error_msgs[] = 'Password and Confirm Password must be match.';
  }
  if (!empty($_POST['email'])) {
    if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
      $error_msgs[] = 'A valid email is required.';
    }
  }
  if (empty($_POST['firstname'])) {
    $error_msgs[] = 'First name is required.';
  }
  if (empty($_POST['lastname'])) {
    $error_msgs[] = 'Last name is required.';
  }
  return $error_msgs;
}

function delete_users_validation()
{
  $error_msgs = [];
  if (empty($_GET['id'])) {
    $error_msgs[] = 'User Id is required.';
  } else {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($id)) {
      $error_msgs[] = 'User id is required.';
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
      <div class="col ms-5 mt-5 d-flex flex-column align-items-center">
        <?php if ($action == 'error-messages') : ?>
          <h1>The following errors must be resolved before continuing:</h1>
          <ul class=" ms-4 mt-4" style="list-style: disc; font-size: 1.2rem; color: #DEE0E0;">
            <?php foreach ($error_msgs as $msg) : ?>
              <li><?= $msg ?></li>
            <?php endforeach ?>
          </ul>
          <?php if ($previous_action == 'add') : ?>
            <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="admin-users-form.php?action=add">Back
                to
                Add User
              </a>
            </p>
          <?php elseif ($previous_action == 'edit') : ?>
            <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="admin-users.php">Back
                to
                Users
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