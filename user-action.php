<?php

if (!isset($_GET['action'])) {
  header('Location: index.php');
  die();
}

$action = $_GET['action'];

// the role will be assigned to registered user,
// using the role_id in Roles table, 3 indicates "User".
$user_role_id = 3;

// connect to database
require 'db_connection.php';

session_start();

switch ($action) {
  case 'signup': {
      $error_msgs = signup_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        header('Location: user-action.php?action=error-messages');
      }

      // data sanitization
      $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
      $first_name = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $last_name = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


      // password hashing
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $query = 'INSERT INTO users (username, password, email, first_name, last_name, role_id) values (:username, :password, :email, :first_name, :last_name, :role_id)';
      $statement = $db_conn->prepare($query);
      $statement->bindValue(':username', $username);
      $statement->bindValue(':password', $password);
      $statement->bindValue(':email', $email);
      $statement->bindValue(':first_name', $first_name);
      $statement->bindValue(':last_name', $last_name);
      $statement->bindValue(':role_id', $user_role_id);
      $statement->execute();
      break;
    };
  case 'login': {
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
      break;
    }
  default:
    break;
}

// data validation and save valid fields to session
function signup_validation()
{
  $error_msgs = [];
  if (empty($_POST['username'])) {
    $error_msgs[] = 'Username is required.';
  } else {
    $_SESSION['signup_username'] = $_POST['username'];
  }
  if (empty($_POST['password'])) {
    $error_msgs[] = 'Password is required.';
  }
  if ($_POST['password'] != $_POST['confirm-password']) {
    $error_msgs[] = 'Password and Confirm Password must be match.';
  }
  if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
    $error_msgs[] = 'A valid email is required.';
  } else {
    $_SESSION['signup_email'] = $_POST['email'];
  }
  if (empty($_POST['firstname'])) {
    $error_msgs[] = 'First name is required.';
  } else {
    $_SESSION['signup_firstname'] = $_POST['firstname'];
  }
  if (empty($_POST['lastname'])) {
    $error_msgs[] = 'Last name is required.';
  } else {
    $_SESSION['signup_lastname'] = $_POST['lastname'];
  }
  return $error_msgs;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark d-flex flex-column min-vh-100">
  <?php
  include("template-header-lite.php");
  ?>

  <main class="main mt-5 d-flex justify-content-center flex-grow-1">
    <div class="wrap d-flex align-items-center flex-column">
      <?php if ($action == 'error-messages') : ?>
        <h1>The following errors must be resolved before continuing:</h1>
        <ul class="mt-4" style="list-style: disc; font-size: 1.2rem; color: #DEE0E0;">
          <?php foreach ($error_msgs as $msg) : ?>
            <li><?= $msg ?></li>
          <?php endforeach ?>
        </ul>
        <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="user.php?action=signup&error=1">Back to
            Sign
            Up</a></p>
      <?php endif ?>
    </div>
  </main>

  <?php
  include("template-footer.php");
  ?>
</body>

</html>