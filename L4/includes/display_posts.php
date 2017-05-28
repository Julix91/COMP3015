<?php
/********************************POST**********************************/
	//This section handles reading and displaying saved posts//

/*functions:
 15ish-75ish get_post_data()
 85ish-100ish sort_posts_by()
 100ish display_posts()


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
			 //used to URL encode to avoid having to CSV escape, not necessary anymore except for support of old posts. ;)
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
/*********************************************************************/
