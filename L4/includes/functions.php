<?php

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
	//This section handles reading and displaying saved posts//

/*********GET POST DATA********/

ini_set("auto_detect_line_endings", true); //just in case
function get_post_data($filename)
//Import CSV data into two-dimensional array with semantic key from first row
//inspired by http://stackoverflow.com/a/8353265/2848941
//I considered to - but didn't - implement support for MS Excel on Mac / iOS http://stackoverflow.com/questions/4348802/how-can-i-output-a-utf-8-csv-in-php-that-excel-will-read-properly as it would have required UTF-16LE and I'm not sure if that has as wide support and won't go further down this rabbit hole for now.
{
	//*Considered replacing exits with proper if-then custom error handling so it doesn't quit the whole application
	$myfile = fopen($filename, "r") or exit("Unable to open file ($filename)!");//open file
	//or express that you can't - though die isn't really sufficient for the web obviously...
	$csv_data = fread($myfile,filesize($filename)) or exit("The file ($filename) is empty!");//read the entire file or complain about
	$lines = explode("\n", $csv_data); //csv string of all posts to array of posts
	if(empty(trim($lines[1]))){
		$postErr['error']['empty'] = '<div class="alert alert-info text-center col-md-6 col-md-offset-3">
			There are currently <strong>no posts</strong> here. Check again later, or be the first to post!
		</div>';
		return $postErr;
	}
	$post_keys = explode(",", trim($lines[0])); //save first line (headers)

	unset($lines[0]); //make sure the headers / keys don't become content

	if(empty($lines[count($lines)]))
	//stops creation of empty line array from having a linebreak at end of the file
	{
		unset($lines[count($lines)]);
	}

	$results = array(); //create a place to store the posts
	foreach ( $lines as $line ) {
		$parsedLine = str_getcsv( trim($line), ',' );

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


/***Formatting helper functions***/

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
	return date('l F \t\h\e dS, Y', $seconds);
}


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

/*********DISPLAY POST DATA********/

function display_posts($posts)
//output buffer idea from http://stackoverflow.com/questions/1288623/save-an-includes-output-to-a-string
{
	$lim = count($posts);
	for ($i = 0; $i < $lim; $i++){
		$post = $posts[$i];
		$posts[$i]['posted'] = format_date($post['received']);
		$posts[$i]['when'] = moments($post['received']);
		$posts[$i]['who'] = namify($post['first_name']) . " " . namify($post['last_name']);
		$post = $posts[$i];
		ob_start();
		include "./templates/post.php";
		$html_posts .= ob_get_contents();
		ob_end_clean();
	}
	return $html_posts;
}

/********************************************************************/
	//This section handles form submissions and saving them  //

/*********GET POST DATA********/

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

//On submit mark form as started, clean up inputs and namify names
if ($_SERVER["REQUEST_METHOD"] === "POST" && count($_POST) > 0) {
	$started = true;
	var_dump($_POST);
	foreach ($_POST as $key=>$val){
		if(empty(trim($val))){

			if($key === "priority"){
				${$key . "Err"} = "You've gotta tell me how <em>important</em> it is to you!";
			} else if ($key === "comment"){
				${$key . "Err"} = "Seriously, if you've got nothing to say, why bother at all?";
			} else {
				${$key . "Err"} = "Your <em>" . namify($key) . "</em> is required!";
			}
			echo $key . " " . ${$key} . "<br>";
		} else {
			/*$_POST[$key] = */${$key} = clean_input($val);
			if($key === "first_name" || $key === "last_name"){
				${$key} = namify(${$key});
			}
		}
	}
	//check for successfulness of submission
if (isset($_POST["first_name"], $_POST["last_name"], $_POST["priority"], $_POST["comment"])
//&& (not_mempty($_POST["first_name"], $_POST["last_name"], $_POST["priority"], $_POST["comment"]))
//still working on more elegant multi-not-empty
&& ( (!empty($_POST["first_name"])) && (!empty($_POST["last_name"])) && (!empty($_POST["priority"])) && (!empty($_POST["comment"])) )
) {
		$success = true;
		$_POST["received"] = time();
		$received_formatted = date('l F \t\h\e dS, Y', $_POST["received"]);

		save_as_CSV($_POST, 'file.csv');
	}
}


?>
