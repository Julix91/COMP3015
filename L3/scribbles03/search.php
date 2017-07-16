<?php

// check if username is in the post parameters
// you may wish to look up empty() as well
// keep in mind the index username matches the name attribute from the front end html
if(    isset($_POST['username'])   )
{
    echo $_POST['username'];
	echo 'user name was provided!';
    echo '<br/>';
}

if(    isset($_POST['password'])   )
{
    echo $_POST['password'];
    echo '<br/>';
}
