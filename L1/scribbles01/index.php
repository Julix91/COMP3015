<html>

<?php

// declare some variables and assign strings to them
// the $ is used to declare a variable in PHP
$sentence = 'hello world';
$word = 'gary';

// define PI as 3.14
define('PI', 3.14);

/**
 * this comment blockshould be ignored
 */


// use the . for string concatenation
echo $sentence . ' ' . $word;

// mixing PHP with HTML is possible
echo '<h2>' . $sentence .'</h2>';

// <br/> isn't PHP, it's HTML. echo will send it to output, to client, for rendering
echo '<br/>';

// to use a constant, you don't need to have the $
echo PI;

?>

<br/>

</html>
