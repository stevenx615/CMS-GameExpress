<?php
require 'utilities.php';

if (!isset($_GET['action'])) {
  redirect('index.php');
}

$action = $_GET['action'];
// session_start();

// connect to database
require 'db_connection.php';

switch ($action) {
  case 'signup': {
      $page_title = 'Sign Up';
      $error_msgs = signup_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('user-action.php?action=error-messages&pre=signup');
      }

      // the role will be assigned to registered user,
      // using the role_id in Roles table, 3 indicates "User".
      $user_role_id = 3;

      // data sanitization
      $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
      $first_name = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $last_name = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // password hashing
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

      try {
        $query = 'INSERT INTO users (username, password, email, first_name, last_name, role_id) values (:username, :password, :email, :first_name, :last_name, :role_id)';
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':role_id', $user_role_id);
        $success = $statement->execute();
        $user_id = $db_conn->lastInsertId();
      } catch (PDOException $ex) {
        die('There is an error when creating user.');
      }

      if ($success) {
        clear_signup_session();
        $_SESSION['user_id'] = $user_id;
        redirect('user-action.php?action=success-messages&pre=signup');
      }

      break;
    };
  case 'login': {
      $page_title = 'Log In';
      $error_msgs = login_validation();
      if (!empty($error_msgs)) {
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('user-action.php?action=error-messages&pre=login');
      }

      $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $password = $_POST['password'];

      try {
        // validate the password with database
        $query = 'SELECT * FROM users WHERE username = :username';
        $statement = $db_conn->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $ex) {
        die('There is an error when validating user.');
      }

      // pass validation
      if (password_verify($password, $result['password'])) {
        clear_login_session();
        $_SESSION['user_id'] = $result['user_id'];
        redirect('user-action.php?action=success-messages&pre=login');
      } else {
        $error_msgs[] = 'Password is incorrect.';
        $_SESSION['error_msgs'] = $error_msgs;
        redirect('user-action.php?action=error-messages&pre=login');
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

      // get the referer, sign up or log in
      if (isset($_GET['pre'])) {
        $previous_action = $_GET['pre'];
      }
      break;
    }
  case 'success-messages': {
      $page_title = 'Successful Messages';

      // get the referer, sign up or log in
      if (isset($_GET['pre'])) {
        $previous_action = $_GET['pre'];
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

// clear the sign up fields in session after sign up successfully
function clear_signup_session()
{
  unset($_SESSION['signup_username']);
  unset($_SESSION['signup_email']);
  unset($_SESSION['signup_firstname']);
  unset($_SESSION['signup_lastname']);
}

// validate the username and password
function login_validation()
{
  $error_msgs = [];
  if (empty($_POST['username'])) {
    $error_msgs[] = 'Username is required.';
  } else {
    $_SESSION['login_username'] = $_POST['username'];
  }
  if (empty($_POST['password'])) {
    $error_msgs[] = 'Password is required.';
  }
  return $error_msgs;
}

// clear the log in fields in session after log in successfully
function clear_login_session()
{
  unset($_SESSION['login_username']);
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
        <?php if ($previous_action == 'signup') : ?>
          <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="user.php?action=signup&error=1">Back to
              Sign
              Up</a></p>
        <?php elseif ($previous_action == 'login') :  ?>
          <p class="mt-5"><a class="btn btn-outline-secondary px-5 py-2 fs-5" href="user.php?action=login&error=1">Back to
              Log In</a></p>
        <?php endif ?>
      <?php elseif ($action == 'success-messages') : ?>
        <?php if ($previous_action == 'signup') : ?>
          <h1>Sign up successfully!</h1>
          <p class="mt-3 fs-5">It will automatically jump to the home page after 3 seconds.</p>
        <?php elseif ($previous_action == 'login') :  ?>
          <h1>Log in successfully!</h1>
          <p class="mt-3 fs-5">It will automatically jump to the home page after 3 seconds.</p>
        <?php endif ?>
      <?php endif ?>
    </div>
  </main>

  <?php
  include("template-footer.php");
  ?>
</body>

</html>
<?php
if ($action == 'success-messages') {
  redirect_delay(3, 'index.php');
}
?>