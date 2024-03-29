<?php
session_start();
require 'utilities.php';
require 'authentication.php';

if (!is_logged_in()) {
  redirect('user.php?action=login');
}

$page_title = 'User Account';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?></title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  include "template-header.php";
  ?>

  <main class="main mt-5 d-flex justify-content-center flex-grow-1">
    <div class="wrap d-flex align-items-center flex-column">
      <h1>Manage Account</h1>
      <form action="user-action.php?action=update-account" method="POST" class="user-account-form w-50">
        <div class="row">
          <label class="col-4 col-form-label me-3 text-end" for="username">Username</label>
          <div class="col">
            <div class="mb-3 py-1 fs-5"><?= $_SESSION['user']['username'] ?></div>
          </div>
        </div>
        <div class="row">
          <label class="col-4 col-form-label me-3 text-end" for="email">Email</label>
          <div class="col">
            <input type="text" name="email" id="email" value="<?= $_SESSION['user']['email'] ?>" />
          </div>
        </div>
        <div class="row">
          <label class="col-4 col-form-label me-3 text-end" for="firstname">First Name</label>
          <div class="col">
            <input type="text" name="firstname" id="firstname" value="<?= $_SESSION['user']['first_name'] ?>" />
          </div>
        </div>
        <div class="row">
          <label class="col-4 col-form-label me-3 text-end" for="lastname">Last Name</label>
          <div class="col">
            <input type="text" name="lastname" id="lastname" value="<?= $_SESSION['user']['last_name'] ?>" />
          </div>
        </div>
        <hr />
        <div class="mt-2">
          <p class="mb-4 py-2 text-center bg-black bg-opacity-25" style="color:#0BB85C;">* Leave the password blank if
            you do not want to
            change it.
          </p>
        </div>
        <div class=" row">
          <label class="col-4 col-form-label me-3 text-end" for="password">Password</label>
          <div class="col">
            <input type="password" name="password" id="password" />
          </div>
        </div>
        <div class="row">
          <label class="col-4 col-form-label me-3 text-end" for="confirm-password">Confirm Password</label>
          <div class="col">
            <input type="password" name="confirm-password" id="confirm-password" />
          </div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn-green btn-submit">Update</button>
        </div>
      </form>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>