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
  <title>Game Express - Top Games</title>
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
          <h1 class="post-category-title">Top 100 Games</h1>
        </div>
      </div>
      <hr class="post-title-line">
      <div class="row mt-5">
        <table class="game-list-table mx-2 table table-dark table-striped table-hover">
          <thead>
            <tr>
              <th style="width: 4rem;">#</th>
              <th style="width: 13rem;">Game</th>
              <th></th>
              <th style="width: 15rem;">Developer</th>
              <th style="width: 8rem;">Positive<br>Feedback</th>
              <th style="width: 8rem;">Negative<br>Feedback</th>
              <th style="width: 6rem;">Price</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </main>

  <?php
  include "template-footer.php";
  ?>
  <script src="fetch-game-rank.js"></script>
</body>

</html>