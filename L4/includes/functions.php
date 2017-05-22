<?php


/********************************************************************/
	//This section handles reading and displaying saved posts//


/*********GET POST DATA********/

ini_set("auto_detect_line_endings", true); //just in case
function get_post_data($filename)
{
//Import CSV data into two-dimensional array with semantic key from first row
//inspired by http://stackoverflow.com/a/8353265/2848941
//I considered to - but didn't - implement support for MS Excel on Mac / iOS http://stackoverflow.com/questions/4348802/how-can-i-output-a-utf-8-csv-in-php-that-excel-will-read-properly as it would have required UTF-16LE and I'm not sure if that has as wide support and won't go further down this rabbit hole for now.

	//Sorry for the ugly error handling. - I had "or exit(...)" on each before, but replaced it so errors don't abort the whole application and I can build resilience. -- I understand in a real application I would never expose that much information about what exactly is going wrong, just in case it could be used by hackers.

	//Does file exist?
	if(!file_exists($filename)){
		$postErr['error']['find'] = "Unable to find the file \"$filename\"! <br> Maybe someone deleted it? <br> <em>Make a new post for a fresh start.</em>";
		//memo to self: make sure the form submitter / csv saver reads all the keys to first row if file is empty pre submit.
		return $postErr;
	}

	$size = filesize($filename);
	//Is it not empty?
	if($size < 1){
		$postErr['error']['empty'] = "The file \"$filename\" is entirely empty! <br> <em>Not to worry, be the first to post!</em>";
		return $postErr;
	}

	$myfile = fopen($filename, "r");
	//Can I open file?
	if(!$myfile){
		$postErr['error']['open'] = "Unable to open the file \"$filename\"! <br> <em>No clue why...</em>";
		return $postErr;
	}

	$csv_data = fread($myfile,$size);//read the entire file
	//Can I read file?
	if(!$csv_data){
		$postErr['error']['read'] = "Unable to read the file \"$filename\"! <br> <em>No clue why...</em>";
		return $postErr;
	}

	$lines = explode("\n", trim($csv_data)); //csv string of all posts to array of posts
	//trimmed to avoid creation of an empty post due to the linebreak at end of file

	//Headers: I'm not checking whether the first line is in fact the headers/keys...
	$post_keys = explode(",", trim($lines[0])); //save first line (headers) w/o linebreak
	unset($lines[0]); //make sure the headers / keys don't become content

	//Is there a non-header line of content?
	if(empty(trim($lines[1]))){
		$postErr['error']['empty'] = 'There are currently <strong>no posts</strong> here. Check again later, or be the first to post!';
		return $postErr;
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
				$result[$post_keys[$post_key]] = 'unknown';
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
	if (is_numeric($seconds) ) {
		return date('l F \t\h\e dS, Y', $seconds);
	} else {
		return "unknown date";
	}
}


function moments($seconds)
//more intuitive date
//in JS switch seem less effective, not sure if that's true server-side, too? http://stackoverflow.com/questions/6665997/switch-statement-for-greater-than-less-than
{
	if($seconds==="unknown"){
		return "no idea when";
	}
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
	//This section handles form submissions and saving them	//

/*Helper functions*/
function clean_input($data)
//inspired by https://www.w3schools.com/php/showphp.asp?filename=demo_form_validation_escapechar
{
	$data = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $data)));//removes unnecessary whitespace
	$data = stripslashes($data); //avoids escaping the intended context
	$data = htmlspecialchars($data); //also to help vs injection
	return $data;
}

function save_as_CSV($what = NULL, $where = NULL){
	if (!isset($where)) {
		$where = 'posts.txt';
	}
	if (!isset($what)){
		$what = $_POST;
	}
	$file = fopen($where, 'a');
	fputcsv($file, $what);
	fclose($file);
}

function trimpty($arg){ //trimmed + empty
	return (empty(trim($arg)));
}

//multivariable-empty-checker from http://stackoverflow.com/a/7798842/2848941
function not_mempty() { //multiple + trimmed + empty
    foreach(func_get_args() as $arg){
		if(!trimpty($arg)){
			continue;
		} else {
			return false;
		}
		return true;
	}
}
$required_fields = ["started", "first_name", "last_name", "title", "img_src", "priority", "comment"];
foreach ($required_fields as $value) {
	if(!(isset($_POST[$value]))){
		$_POST[$value]="";
	}
}

//On submit mark form as started, clean up inputs and namify names
if ($_SERVER["REQUEST_METHOD"] === "POST" && count($_POST) > 0 && $_POST['started'] === "started") {

	var_dump($_POST);
	foreach ($_POST as $key=>$val){
		if(trimpty($val)){
			if($key === "priority"){
				${$key . "Err"} = "You've gotta tell me how <em>important</em> it is to you!";
			} else if ($key === "comment"){
				${$key . "Err"} = "Seriously, if you've got nothing to say, why bother at all?";
			} else {
				${$key . "Err"} = "Your <em>" . namify($key) . "</em> is required!";
			}
		} else {
			echo $key . " " . $val ;
			$_POST[$key] = clean_input($val);
		}
	}



	//check for successfulness of submission
	$message = "";
	$test_success = function($required_fields=NULL)
	{
		if (!isset($required_fields)) {
			$required_fields = ["first_name", "last_name", "priority", "comment"];
		}
		$fail = null;
		foreach ($required_fields as $required_field_value ) {
			if(isset($_POST[$required_field_value]) && !empty($_POST[$required_field_value])){
				continue;
			} else {
				$fail = true;
				break;
			}
		}
		if ($fail) {
			return false;
		} else {
			return true;
		}
	};

	$success = $test_success($required_fields);
	if($success){
		echo "<br>feeling successful<br>";
		$_POST["received"] = time();
		$received_formatted = date('l F \t\h\e dS, Y', $_POST["received"]);
		save_as_CSV($_POST, 'posts.txt');
	}
}

?>
