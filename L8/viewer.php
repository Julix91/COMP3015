<?php
require ("includes/functions.php");

$storageDir="./uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $path = $storageDir . $_GET['name'];
  if(file_exists($path)){
    header("Content-type: image/png");
    readfile($path);
  }
} else {
  echo "nada!";
}
