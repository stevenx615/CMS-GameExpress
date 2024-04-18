<?php
session_start();
// print_r($_SESSION);
require 'utilities.php';
require 'admin-authentication.php';
require 'admin-user-preference.php';

// check permission
if (!(is_logged_in() && has_role([1, 2]))) {
  redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Center | Game Categories</title>
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
        <div class="mt-4 d-flex justify-content-between align-items-end">
          <h1>Game Categories</h1>
        </div>
        <div>
          Coming Soon...
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-admin-footer.php";
  ?>
</body>

</html>