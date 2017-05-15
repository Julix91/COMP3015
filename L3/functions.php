<?php


//HELPER FEUNCTIONS
//multivariable-empty-checker from http://stackoverflow.com/a/7798842/2848941
function mempty() {
    foreach(func_get_args() as $arg){
		if(empty($arg)){
			continue;
		} else {
			return false;
		}
		return true;
	}
}


//HOMEWORK SPECIFIC

/*********GET POST DATA********/
//Import CSV data into two-dimensional array with semantic key
//inspired by http://stackoverflow.com/a/8353265/2848941
function get_post_data(){
	$myfile = fopen("file.csv", "r") or die("Unable to open file!");
	$csv_data = fread($myfile,filesize("file.csv"));
	$lines = explode("\n", $csv_data); //string to array
	$formatting = explode(",", $lines[0]); //save first line (headers)
	unset($lines[0]); //make sure the headers / keys don't become content
	$results = array(); //create a place to store the posts
	foreach ( $lines as $line ) {
		$parsedLine = str_getcsv( $line, ',' );
		$result = array();
		foreach ( $formatting as $index => $value ) {
		  if(isset($parsedLine[$index])) {
			 $result[$formatting[$index]] = trim(rawurldecode($parsedLine[$index]));
			 //decode so that it looks normal - was encoded to avoid having to escape characters for CSV.
		  } else {
			 $result[$formatting[$index]] = '';
		  }

		}
		$results[] = $result;
	}
	fclose($myfile);
	return $results;
}
/*************************************/


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

//validate for success
function validate_form (){

}

?>
