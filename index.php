<?php
session_start();
print_r($_SESSION);
require 'db_connection.php';
require 'authentication.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News</title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="bg-dark d-flex flex-column min-vh-100">
  <?php
  include "template-header.php";
  include "template-search.php";
  ?>

  <main class="main d-flex justify-content-center flex-grow-1">
    <div class="wrap">
      <div>main</div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>