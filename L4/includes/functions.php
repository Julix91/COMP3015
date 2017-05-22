<?php

/***Formatting***/

function namify($str)
//e.g. turning h JuLiAn_sLomAN to H. Julian Sloman
{
	$str = str_replace('_', ' ', $str);
	$str = ucwords(strtolower($str));
	if (strlen($str) === 1) {
		return $str . ".";
	}
	//handling multiple name entries
	if(strpos($str, ' ')){
		$arr = explode(' ', $str);
		foreach ($arr as $key => $value) {
			if (strlen($value) === 1) {
				$arr[$key] .= '.';
			}
		}
		$str = implode(' ', $arr);
	}
	return $str;
}

function format_date($seconds)
//e.g. turning 1481808630 to Thursday December the 15th, 2016
{
	date('l F \t\h\e dS, Y', $seconds);
}

$formattedCurrentTime   = format_date(time());

function moments($seconds)
//more intuitive date
//in JS switch seem less effective, not sure if that's true server-side, too? http://stackoverflow.com/questions/6665997/switch-statement-for-greater-than-less-than
{
	$values = array('hour' => (60 * 60), 'day' => (60 * 60 * 24), 'month' => (60 * 60 * 24 * 30) );
	if ($seconds < $values['hour']) {
		return "just now";
	}
	if ($seconds < $values['day']) {
		return "today";
	}
	if ($seconds < $values['month']) {
		return "within the month";
	}
	if ($seconds >= $values['month']) {
		return "a while ago";
	}
}


/*Error Message Handling */
$message = '';
if(count($_POST) > 0){
	if(trim($_POST['firstName']) == '' || trim($_POST['lastName']) == '' || trim($_POST['comment']) == '' || trim($_POST['priority']) == '')
	{
		$message = '<div class="alert alert-warning alert-dismissable text-center">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						All inputs are required!
					</div>';
	}
	else
	{
		$message = '<div class="alert alert-success alert-dismissable text-center">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Thank you ' . $_POST['firstName'] . ' ' . $_POST['lastName'] . '! .
						' . date('F dS, Y', time()).'.
					</div>';
	}
}


/********************************************************************/

/*********GET POST DATA********/

ini_set("auto_detect_line_endings", true); //just in case
function get_post_data($filename)
//Import CSV data into two-dimensional array with semantic key from first row
//inspired by http://stackoverflow.com/a/8353265/2848941
//I considered to - but didn't - implement support for MS Excel on Mac / iOS http://stackoverflow.com/questions/4348802/how-can-i-output-a-utf-8-csv-in-php-that-excel-will-read-properly as it would have required UTF-16LE and I'm not sure if that has as wide support and won't go further down this rabbit hole for now.
{
	$myfile = fopen($filename, "r") or exit("Unable to open file ($filename)!"); //open or express that you can't - though die isn't really sufficient for the web obviously...

	$csv_data = fgetcsv($myfile);
	var_dump($csv_data);

	while (($data = fgetcsv($myfile, 1000, ",", '"', '\'')) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            echo $data[$c];
        }
    }

/*
	$csv_data = fread($myfile,filesize($filename));//read the entire file
	$lines = explode("\n", $csv_data); //string to array
	$post_keys = explode(",", $lines[0]); //save first line (headers)
	unset($lines[0]); //make sure the headers / keys don't become content
	unset($lines[0]); //make sure the headers / keys don't become content

*/
	if(empty($lines[count($lines)]))
	//stops creation of empty line array from having a linebreak at end of the file
	{
		unset($lines[count($lines)]);
	}
	$results = array(); //create a place to store the posts
	foreach ( $lines as $line ) {
		var_dump($line);
		trim($line);
		var_dump($line);
		$parsedLine = str_getcsv( $line, ',' );
		$result = array();

		foreach ( $post_keys as $post_key => $value ) {
		  if(isset($parsedLine[$post_key]) && !empty(trim($parsedLine[$post_key]))) {
			 $result[$post_keys[$post_key]] = rawurldecode(trim($parsedLine[$post_key]));
			 //decode so that it looks normal - was encoded to avoid having to escape characters for CSV.
		  } else {
			  continue;
			 //$result[$post_keys[$index]] = '';
		  }

		}
		$results[] = $result;
	}
	fclose($myfile);
	return $results;
}

/*********SORT POST DATA********/

function sort_posts_by($value='priority', $posts)
//sorts a given array of associative arrays by a given key and returns it
//inspired by http://stackoverflow.com/a/1598385/2848941
{
	$sorting_criteria = array();
	foreach ($posts as $post_number => $post){
	    $sorting_criteria[$post_number] = $post[$value];
	}
	array_multisort($sorting_criteria, SORT_DESC, $posts);

	return $posts;
}

/********************************************************************/



?>
