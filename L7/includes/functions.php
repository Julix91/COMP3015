<?php

function moments($seconds)
{
    if($seconds < 60 * 60 * 24 * 30)
    {
        return "within the month";
    }

    return "a while ago";
}

function checkPresenceOfInput($inputs, $requiredFields)
{
	foreach ($requiredFields as $required) {
		if(!isset($inputs[$required]) || trim($inputs[$required]) == ''){
			return false;
		}
	}
	return true;
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
	$pattern['dob'] = '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)\-(0[1-9]|[1-2][0-9]|3[0-1])\-19[0-9]{2}$/'; //doesn't account for leap years or e.g. Feb not having 31 days.
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


function validateInput($inputs, $requiredFields)
{
	foreach ($requiredFields as $required) {
		if(!regex_validate_input($inputs[$required], $required)){
			return false;
		}
	}
	return true;
}

function saveAsCSV($what = NULL, $where = NULL)
{
	$where = is_null($where) ? 'posts.txt' : $where;
	$what = is_null($what) ? $_POST : $what;
	$file = fopen($where, 'a');
	fputcsv($file, $what);
	fclose($file);
}

function validateUser($inputs, $storageFile)
{
	$num = preg_replace('/\D/', '', $inputs['phoneNumber']); //strips non-digits from input
	$handle = fopen($storageFile, "r");
	$result = false;
	while ($row = fgetcsv($handle)) {
		$stored_num = preg_replace('/\D/', '', $row[3]);//strips non-digits from stored value
		if($stored_num == $num && password_verify($inputs['password'], $row[2])){
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

function getPosts()
{
    $posts = [];

    if(file_exists("posts.txt"))
    {
        $posts = file("posts.txt");
    }

    return $posts;
}

function catchBreakIn(){
	//header("HTTP/1.0 404 Not Found");
	//"MUST include a WWW-Authenticate header field" -  https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.4.2
	//but have no clue what that is or how...
	$_SESSION['breakIn'] = wrapAlert("You think you can see these wonderful posts without loging in first? 'Pha!' I say!", 'danger');
	$ipAddress = $_SERVER['REMOTE_ADDR'];
	if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
		$ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
	}
	saveAsCSV(array($ipAddress), 'unauthorized.txt');
}

//from https://stackoverflow.com/a/23542578/2848941
function redirect($url, $code=302) {
    ob_start();
	if($code === 401){
		catchBreakIn();
	}
    header('Location: '.$url, true, 302);
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
}

/*Presentation*/
function wrapAlert($message, $type = NULL)
{
	$type = is_null($type)?"warning":$type;
	ob_start(); ?>
	<div class="alert alert-<?php echo $type; ?> alert-dismissable text-center">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo $message?>
	</div>
	<?php return ob_get_clean();
}
