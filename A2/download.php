<?php
session_start();
//kill if unauthorized
require ("includes/functions.php");
if(empty($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true){
	redirect('login.php');
	die("Download what? Login <a href=\"./login.php\">here</a> first!");
}

$storageDir="./uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $file = $storageDir . $_GET['filename'];
  if(file_exists($file)){


//http://php.net/manual/en/function.readfile.php#example-2681 and https://stackoverflow.com/a/1968116/2848941
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false); // required for certain browsers
    header('Content-Type: '. filetype($file) .'');

    header('Content-Disposition: attachment; filename="'. basename($file) . '";');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));

    readfile($file);

    exit;
  }
} else {
  echo "nada!";
}
