<?php

$page_title = '';

// If no action parameter is obtained, return to the previous page.
if (!isset($_GET['action'])) {
  header("Location: index.php");
}

$action = $_GET['action'];

session_start();

switch ($action) {
  case 'signup': {
      // fill up the fields user previous filled
      print_r($_SESSION);
      retrieve_fields();
      break;
    }
  case 'login': {
      break;
    }
  default:
    break;
}

// retrieve the fields when back from error page
function retrieve_fields()
{
  global $username;
  global $email;
  global $first_name;
  global $last_name;
  if (isset($_GET['error'])) {
    if ($_GET['error'] == 1) {
      $username = get_session_field('signup_username');
      $email = get_session_field('signup_email');
      $first_name = get_session_field('signup_firstname');
      $last_name = get_session_field('signup_lastname');
    }
  } else {
    $_SESSION['signup_username'] = '';
    $_SESSION['signup_email'] = '';
    $_SESSION['signup_firstname'] = '';
    $_SESSION['signup_lastname'] = '';
  }
}

// get a value from session
function get_session_field($field)
{
  $result = '';
  if (isset($_SESSION[$field])) {
    $result = $_SESSION[$field];
    $_SESSION[$field] = '';
  } else {
    $result = '';
  }
  return $result;
}

$has_login = false;

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
      <?php if ($action == 'signup') : ?>
        <h1>Create your account.</h1>
        <form action="user-action.php?action=signup" method="POST" class="user-form">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" value="<?= $username ?>" />
          <label for="password">Password</label>
          <input type="password" name="password" id="password" />
          <label for="password">Confirm Password</label>
          <input type="password" name="confirm-password" id="confirm-password" />
          <label for="email">Email</label>
          <input type="email" name="email" id="email" value="<?= $email ?>" />
          <label for="firstname">First Name</label>
          <input type="text" name="firstname" id="firstname" value="<?= $first_name ?>" />
          <label for="lastname">Last Name</label>
          <input type="text" name="lastname" id="lastname" value="<?= $last_name ?>" />
          <div>
            <button type="submit" class="btn-green btn-submit">SIGN UP</button>
          </div>
          <div class="mt-3 text-center fs-5">
            <span>Already have an account? <a href="user.php?action=login">Log In</a></span>
          </div>
        </form>
      <?php endif ?>
    </div>
  </main>

  <?php
  include("template-footer.php");
  ?>
</body>

</html>