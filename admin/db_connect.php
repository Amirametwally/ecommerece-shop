<?php
$dsn = 'mysql:host=localhost;dbname=shop';
$user = 'phpmyadmin';
$pass = 'phpmyadmin_root';

try {
  $connect = new PDO($dsn, $user, $pass);
  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
  echo 'Failed To Connect To The Database' . $err->getMessage();
}
