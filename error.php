<?php
session_start();
require 'utilities.php';
require 'authentication.php';

if (!isset($_GET['action'])) {
  redirect('index.php');
}

$action = $_GET['action'];

switch ($action) {
  case 'error-messages': {
      $error_msgs = [];
      if (!empty($_SESSION['error_msgs'])) {
        $error_msgs = $_SESSION['error_msgs'];
        $_SESSION['error_msgs'] = '';
      } else {
        $error_msgs[] = 'Unknown Error';
      }
      break;
    };
  default:
    redirect('index.php');
    break;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Express - Message</title>
  <?php
  include "template-head.php";
  ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php
  include "template-header.php";
  ?>

  <main class="main d-flex justify-content-center flex-grow-1">
    <div class="wrap row">
      <div class="col ms-5 mt-5 d-flex flex-column align-items-center">
        <?php if ($action == 'error-messages') : ?>
          <h1>An error occurred:</h1>
          <ul class=" ms-4 mt-4" style="list-style: disc; font-size: 1.2rem; color: #DEE0E0;">
            <?php foreach ($error_msgs as $msg) : ?>
              <li><?= $msg ?></li>
            <?php endforeach ?>
          </ul>
        <?php endif ?>
      </div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>