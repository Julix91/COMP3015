<?php

function moments($seconds)
{
    if($seconds < 60 * 60 * 24 * 30)
    {
        return "within the month";
    }

    return "a while ago";
}

function checkInput($inputs, $requiredFields)
{
	foreach ($requiredFields as $required) {
		if(trim($inputs[$required]) == ''){
			return false;
		}
	}
	return true;
}

function validateInput($inputs, $requiredFields)
{
	foreach ($requiredFields as $required) {
		if(!regex_validate_input($inputs[$required], $required)){
			return false;
		}
	}
	return true;
}

function validateUser($inputs, $storageFile)
{
	$handle = fopen($storageFile, "r");
	$result = false;
	while ($row = fgetcsv($handle)) {
		if($row[3]==$inputs['phoneNumber'] && $row[2]==$inputs['password']){
			$result=true;
			break;
		}
	}
	if($result != true){
		$result=false;
	}
	fclose($handle);
	return $result;
}

function saveInput($data)
{
    $line  = $data['firstName'] . ',';
    $line .= $data['lastName']  . ',';
    $line .= $data['title']     . ',';
    $line .= $data['comment']   . ',';
    $line .= $data['priority']  . ',';
    $line .= $data['filename']  . ',';
    $line .= time();
    $line .= PHP_EOL;

    $fp = fopen("posts.txt", "a+");
    fwrite($fp, $line);
    fclose($fp);
}

function save_as_CSV($what = NULL, $where = NULL)
{
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

function getPosts()
{
    $posts = [];

    if(file_exists("posts.txt"))
    {
        $posts = file("posts.txt");
    }

    return $posts;
}

//Login signup validation
function regex_validate_input($input, $type)
{
	if ($type == 'firstName' || $type == 'lastName') {
		$type = 'name';
	}
	$pattern['name'] = '/^[a-zA-Z]+$/'; //but what about Jürgen Heinsaß? :P
	$pattern['password'] = '/(.*[a-zA-Z].*[0-9].*)|(.*[0-9].*[a-zA-Z].*)/'; //a9 and 9a are valid
	$pattern['phoneNumber'] = '/^((\([0-9]{3}\))|([0-9]{3}))?(\-?|\s?)([0-9]{3})(\-?|\s?)([0-9]{4})$/';
	//7 or 10 digets, optionally: brackets around first 3, space OR hyphen between each group
	$pattern['dob'] = '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)\-(0[1-9]|[1-2][0-9]|3[0-1])\-19[0-9]{2}/'; //doesn't account for leap years or e.g. Feb not having 31 days.
	if(isset($pattern[$type])){
		if(preg_match($pattern[$type], $input) > 0){
			return true;//matches
		} else {
			return false;//doesn't match
		}
	} else {
		var_dump("Validate what? There's no pattern by that name!");
		return false;
	}
}


/* //found myself using the foreach a lot and was wondering whether it would be better to combine...
function itterateFields($input, $requiredFields, $array_of_callbacks){
	foreach ($requiredFields as $required) {
		//loop through $array_of_callback and run each?
	}
}
*/

//from https://stackoverflow.com/a/23542578/2848941
function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}


//from https://stackoverflow.com/a/3512570/2848941
//comments left in, but I don't necessarily understand them...
function logout($whereto=NULL)
{
	$whereto = (empty($whereto) ? './login.php' : $whereto );
	// Unset all of the session variables.
	$_SESSION = [];

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

	// Finally, destroy the session.
	session_destroy();

	redirect($whereto);
	die;
}
