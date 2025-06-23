<?php
define('DB_DSN', 'mysql:host=[IP Address and Port];dbname=game_express;charset=utf8');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
  $db_conn = new PDO(DB_DSN, DB_USER, DB_PASS);
} catch (PDOException $e) {
  print 'Error: ' . $e->getMessage();
  die();
}
