<?php
define('DB_DSN', 'mysql:host=localhost;port=3306;dbname=game_express;charset=utf8');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

try {
  $db_conn = new PDO(DB_DSN, DB_USER, DB_PASS);
} catch (PDOException $e) {
  print 'Error: ' . $e->getMessage();
  die();
}