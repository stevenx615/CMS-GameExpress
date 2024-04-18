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
  <title>Game Express - About me</title>
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
      <div class="row mt-5">
        <div>
          <h1 class="post-category-title">About me</h1>
        </div>
      </div>
      <div class="row mt-5 profile">
        <div class="col-4">
          <div class="my-avatar">
            <img src="images/avatar.png" alt="My avatar" width="80%">
          </div>
        </div>
        <div class="col">
          <h1 class="t1">Hi, I'm Xiquan Xu,</h1>
          <p>
            a web developer and graphic designer with a programming background in C#, .Net, and Java, and proficiency in web technologies such as HTML, CSS, PHP, and JavaScript. With over 7 years of graphic design experience, I specialize in creating compelling visuals that resonate with audiences using the Adobe Creative Suite. My skills also include professional database management using MySQL and PostgreSQL. I am passionate about new challenges and am committed to ensuring project success and personal and professional growth.
          </p>
          <ul class="contacts mt-4 d-flex gap-3">
            <li class="d-flex align-items-center">
              <a href="http://xiquan-xu.byethost3.com/"><img src="images/icon-account.png" alt="Personal Website" width="30"></a>
            </li>
            <li class="d-flex align-items-center">
              <a href="https://github.com/stevenxu2/GameExpress"><img src="images/icon-github.png" alt="Github" width="30"></a>
            </li>
            <li class="d-flex align-items-center">
              <a href="#"><img src="images/icon-linkedin.png" alt="Linkedin" width="28"></a>
            </li>
            <li class="d-flex align-items-center">
              <a href="mailto: stevenx615@gmail.com"><img src="images/icon-email.png" alt="Email" width="30"></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
</body>

</html>