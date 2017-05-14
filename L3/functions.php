<?php

//guestimating age of something
function moments($seconds)
{
    if($seconds < 60 * 60 * 24 * 30)
    {
        return "within the month";
    }
    return "a while ago";
}

//Keeping my inputs clean
function test_input($data) {
  $data = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $data)));//removes unnecessary whitespace
  $data = stripslashes($data); //avoids escaping
  $data = htmlspecialchars($data); //vs injection
  return $data;
}

// check if name only contains letters and whitespace
//doesn't yet work

function check_wordness ($input, $label){
	if (!preg_match("/^[a-zA-Z ]*$/",$input)) {
		return ${$label . "Err"} += " Only letters and white space allowed";
	}
}


?>
