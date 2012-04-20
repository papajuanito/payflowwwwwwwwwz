<?php
  define('TITLE', 'Guerreros de Luz | Fundación Ricky Martin');
  
  define('DB_HOST', 'www.guerrerosdeluz.org');
  define('DB_USER', 'guerreros_admin');
  define('DB_PASSWORD', '2uTUQyHhbAQMf32f');
  define('DB_NAME', 'guerreros');
  
  $mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->set_charset('utf8');

?>