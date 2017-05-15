<?php


//HELPER FEUNCTIONS
//multivariable-empty-checker from http://stackoverflow.com/a/7798842/2848941
function not_mempty() {
	$sentinel = false;
    foreach(func_get_args() as $arg){
		if(!empty(trim($arg))){
			continue;
		} else {
			$sentinel = false;
		}
		$sentinel = true;
	}
	return $sentinel;
}

function save_as_CSV($what = NULL, $where = NULL){
	if (!isset($where)) {
		$where = 'file.csv';
	}
	if (!isset($what)){
		$what = $_POST;
	}
	$file = fopen($where, 'a');
	fputcsv($file, $what);
	fclose($file);
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

/********************************************************************/

/********* FORM DATA ********/

//clean inputs - see https://www.w3schools.com/php/showphp.asp?filename=demo_form_validation_escapechar
function clean_input($data) {
  $data = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $data)));//removes unnecessary whitespace
  $data = stripslashes($data); //avoids escaping the intended context
  $data = htmlspecialchars($data); //also to help vs injection
  return $data;
}

//e.g. turning gARy_toNG to Gary Tong
function namify($str){
	$str = str_replace('_', ' ', $str);
	return ucwords(strtolower($str));
}

function format_date($time){
	date('l F \t\h\e dS, Y', $time);
}

function receive_form() {
	global $first_name, $last_name, $gender, $comment, $priority, $received, $first_nameErr, $last_nameErr, $commentErr, $priorityErr, $success, $started;


}



//Import CSV data into two-dimensional array with semantic key
//inspired by http://stackoverflow.com/a/8353265/2848941


//guestimating age of something
function moments($seconds)
{
    if($seconds < 60 * 60 * 24 * 30)
    {
        return "within the month";
    }
    return "a while ago";
}

?>
