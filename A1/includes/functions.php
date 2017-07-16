<?php

function moments($seconds)
{
    if($seconds < 60 * 60 * 24 * 30)
    {
        return "within the month";
    }

    return "a while ago";
}

function getPosts()
{
    $posts = []; //create a place to store the posts
    if(file_exists("posts.txt")){
        $lines = file("posts.txt");

		$postKeys = ['firstName', 'lastName', 'title', 'comment', 'priority', 'filename', 'postedTime'];
		foreach ( $lines as $line ) {
			$parsedLine = str_getcsv( trim($line), '|' );
			$post = [];//place for properly indexed associative post array

			foreach ($postKeys as $key => $postKey) {
				if(!trimpty($parsedLine[$key])){
					$post[$postKey] = trim($parsedLine[$key]);
				} /*else {
					$post[$postKey] = "unknown";
				}*/
			}
			$posts[]=$post;//append post to posts
		}
    }
    return $posts;
}

function sort_posts_by($posts, $sortByValue)
//sorts a given array of associative arrays by a given key and returns it
//inspired by http://stackoverflow.com/a/1598385/2848941
{
	$sorting_criteria = [];//stores the order to be sorted
	foreach ($posts as $postNumber => $post){
		$sorting_criteria[$postNumber] = $post[$sortByValue];
	}
	array_multisort($sorting_criteria,SORT_ASC, $posts);
	return $posts;
}

function trimpty(&$arg) //trimmed + empty
{
	return (empty(trim($arg)));
}

function isKnown(&$arg){
	if(!trimpty($arg)){
		return trim($arg);
	} else {
		return "unknown";
	}
}

function formatDate($seconds)
//e.g. turning 1481808630 to Thursday December the 15th, 2016
{
	if (is_numeric($seconds) ) {
		return date('l F \t\h\e dS, Y', $seconds);
	} else {
		return "unknown date";
	}
}

function checkPresenceOfInput($input)
{
    if(trim($input['firstName']) == '' ||
        trim($input['lastName']) == '' ||
        trim($input['title'])    == '' ||
        trim($input['comment'])  == '' ||
        trim($input['priority']) == '')
    {
        return false;
    }
    return true;
}

function saveInput($data)
{
    $line  = $data['firstName'] . '|';
    $line .= $data['lastName']  . '|';
    $line .= $data['title']     . '|';
    $line .= $data['comment']   . '|';
    $line .= $data['priority']  . '|';
    $line .= $data['filename']  . '|';
    $line .= time();
    $line .= PHP_EOL;

    $fp = fopen("posts.txt", "a+");
    fwrite($fp, $line);
    fclose($fp);
}

function validateForm()
{

//Regex for inputs
	$pattern['name'] = '/^[ a-zA-Z]+$/';
	$pattern['text'] = '/^[ a-zA-Z0-9\.\'\"\:\;\[\]\(\)\@\!\*\&\?\+\-\_\%\$\#]+$/';
	//not allowing | or < or > -- better solution: HTML-escape everything instead
	$pattern['priority'] = '/^[1-3]$/';//narrower than specified, but better.

	foreach ((['firstName', 'lastName', 'title']) as $key) {
		if(!preg_match($pattern['name'], $_POST[$key]) > 0){
			return false;
		}
	}
	if(!preg_match($pattern['text'], $_POST['comment']) > 0){
		return false;
	}
	if(!preg_match($pattern['priority'], $_POST['priority']) > 0){
		return false;
	}

//Checking the uploaded file for being an image
	if(!getimagesize($_FILES["file"]["tmp_name"])){
		return false;
	}

	return true;
}
