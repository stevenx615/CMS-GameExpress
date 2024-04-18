<?php
session_start();
require 'utilities.php';
require 'authentication.php';
require 'db_connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Express - Find Games</title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  include "template-header.php";
  include "template-search.php";
  ?>

  <main class="main d-flex justify-content-center flex-grow-1">
    <div class="wrap">
      <div class="row mt-5" style="color:#929AA6;">
        <h1 class="text-center">Coming Soon...</h1>
      </div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>