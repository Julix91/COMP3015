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

	$posts = array();

	$link = connectToDatabase();
	$sql_s = "SELECT * FROM posts ORDER BY priority ASC;";
	$result = mysqli_query($link, $sql_s);

	if (mysqli_num_rows($result) > 0) {
	    // add nest post array into posts array
	    while($row = mysqli_fetch_assoc($result)) {
			if (validatePost($row)) {
				$posts[] = $row;
			}
	    }
	} else {
	    echo "0 results";
	}
	return $posts;
}

function searchPosts($term)
{
	$link =  connectToDatabase();
	$term =  mysqli_real_escape_string($link, $term);
	$sql_s = "SELECT * FROM `posts` WHERE concat(`firstName`, '', `lastName`, '', `title`, '', `comment`, '' ) LIKE '%$term%' ORDER BY `priority` DESC";
	$result = mysqli_query($link, $sql_s);

	$matches = [];

	if (mysqli_num_rows($result) > 0) {
		// add nest post array into matches array
		while($row = mysqli_fetch_assoc($result)) {
			if (validatePost($row)) {
				$matches[] = $row;
			}
		}
	}
	return $matches;


	/* //my attempt at prepared statement... I was getting a single result, but got frustrated...
	$matches ="";
	$term = "%$term%";
	$link = mysqli_connect("localhost","root","","A01014626_A2");

	// create a prepared statement
	$stmt = mysqli_stmt_init($link);
	if (mysqli_stmt_prepare($stmt, "SELECT firstName, lastName, title, comment FROM posts WHERE concat(firstName, '', lastName, '', title, '', comment, '' ) LIKE ?")) {

		// bind parameters for markers
		mysqli_stmt_bind_param($stmt, "s", $term);

		// execute query
		mysqli_stmt_execute($stmt);
		$col1 = $col2 = $col3 = $col4 = '';
		// bind result variables
		mysqli_stmt_bind_result($stmt, $col1, $col2, $col3, $col4);

		// fetch value
		mysqli_stmt_fetch($stmt);

		echo "$col1, <br> $col2, <br>  $col3, <br> $col4 <br><br>";

		// close statement
		mysqli_stmt_close($stmt);
	} else {
		echo "Something's wrong";
		var_dump($link);
	}

	//*/
}

function validatePost($post)
{
	$fields = $post;
	$valid = [];

	if(count($fields) !== 8) {
		return false;
	} else {
		foreach ($fields as $field => $value) {
			if( isset($field) && !empty(trim($value)) ){
				$valid[$field] = trim($value);
			} else {
				return false;
			}
		}
		if(!file_exists('uploads/'.$valid['filename'])){
			return false;
		}

		//all good and not yet returned as false -> return success
		return $valid;
	}

/* // slightly more explicit version, if necessary for security? don't think so...
	$requiredFields = ['id', 'firstName', 'lastName', 'title', 'comment', 'filename', 'time'];
	$fields = $post; //i.e. actual fields
	$valid = [];

	foreach ($requiredFields as $required) {
		$field = $fields[$required];
		if (!isset($field) || empty(trim($field)) ) {
			return false;
		} else {
			$valid[$required] = trim($field);
		}
	}
	if(!file_exists('uploads/'.$valid['filename'])){
		return false;
	}

	//not yet returned as false thus valid
	return $valid;
*/
}

function filterPost($post)
{
	$author	 = trim($post['firstName']) . ' ' . trim($post['lastName']);
	$title	  = trim($post['title']);
	$comment	= trim($post['comment']);
	$priority   = trim($post['priority']);
	$filename   = trim($post['filename']);
	$postedTime = trim($post['time']);

	$filteredPost['id'] = $post['id'];
	$filteredPost['author']	 = ucwords(strtolower($author));
	$filteredPost['moment']	 = moments(time() - $postedTime);
	$filteredPost['title']	  = trim($title);
	$filteredPost['comment']	= trim($comment);
	$filteredPost['priority']   = trim($priority);
	$filteredPost['filename']   = trim($filename);
	$filteredPost['postedTime'] = date('l F \t\h\e dS, Y', $postedTime);
	$filteredPost['searchResultsPostedTime'] = date('M d, \'y', $postedTime);

	return $filteredPost;
}

function validateFields($input)
{
	$valid = [];

	$firstName  = trim($input['firstName']);
	$lastName   = trim($input['lastName']);
	$title	  = trim($input['title']);
	$comment	= trim($input['comment']);
	$priority   = trim($input['priority']);

	if($firstName == '' ||
		$lastName == '' ||
		$title	== '' ||
		$comment  == '' ||
		$priority == '' )
	{
		$valid = false;
	}
	elseif(!preg_match("/^[A-Z]+$/i", $firstName) || !preg_match("/^[A-Z]+$/i", $lastName) || !preg_match("/^[A-Z]+$/i", $title))
	{
		$valid = false;
	}
	elseif(preg_match("/<|>/", $comment))
	{
		$valid = false;
	}
	elseif(!preg_match("/^[0-9]{1}$/i", $priority))
	{
		$valid = false;
	}
	else
	{
		$valid['firstName'] = $firstName;
		$valid['lastName'] = $lastName;
		$valid['title'] = $title;
		$valid['comment'] = $comment;
		$valid['priority'] = $priority;
	}

	return $valid;
}

function isValidFile($fileInfo)
{
	if($fileInfo['type'] == 'image/jpeg')
	{
		return true;
	}

	return false;
}

function isValidSearchTerm($term)
{
	if(preg_match("/^[A-Z]+$/i", $term))
	{
		return true;
	}

	return false;
}

function insertPost($data)
{
	// md5 is a hashing function http://php.net/manual/en/function.md5.php
	$filename = md5(time().$data['firstName'].$data['lastName']) . '.jpg';
	$data['filename'] = $filename;
	move_uploaded_file($data['file'], 'uploads/'.$filename);
	unset($data['file']);
	$data['time'] = time();
	$link =  connectToDatabase();
	saveToDB($data, $link, 'posts');
	$id = mysqli_fetch_row(mysqli_query($link, 'SELECT id FROM posts ORDER BY id DESC LIMIT 1'))[0];
	redirect('?#post-'. $id);
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

function checkSignUp($data, $requiredFields, $connection=NULL)
{
	$valid = false;
//dislike that only getting one error message at a time, but won't fix that now.
	if(!checkPresenceOfInput($data, $requiredFields)) {
		$valid = "All inputs are required.";
	} else { // everything's there
		//since number is effectively the username / the part that needs to be unique check for duplicates
		if(!empty($connection)) {
			$tel = preg_replace('/\D/', '', $data["phoneNumber"]); //strip to digets only
			if($result = mysqli_query($connection, "SELECT count(*) FROM logins WHERE phoneNumber='" . $tel . "'")){
				$user_count = mysqli_fetch_row($result)[0];
				if($user_count>0){
					$valid = 'That number already exists! Did you mean to <a href="./login.php">login</a>?';
					return $valid;
				} //else unique :)
			} else {
				$valid = 'Couldn\'t confirm uniqueness of phone number!';
				return $valid;
			};
		} else {
			//connection wasn't provided - again maybe automatic email to admin and system lockdown with maintenance message
		}
		if(!preg_match("/^[A-Z]+$/i", trim($data['firstName']))) {
			$valid = 'First Name needs to be alphabetical only.';
		}
		elseif(!preg_match("/^[A-Z]+$/i", trim($data['lastName']))) {
			$valid = 'Last Name needs to be alphabetical only';
		}
		elseif(!preg_match("/^.*([0-9]+.*[A-Z])|([A-Z]+.*[0-9]+).*$/i", trim($data['password'])) || preg_match('/\s+/',$data['password'])  ) {
			$valid = 'Password must contain at least a number and a letter and NO whitespace.';
		}
		elseif(!preg_match("/^((\([0-9]{3}\))|([0-9]{3}))?( |-)?[0-9]{3}( |-)?[0-9]{4}$/", trim($data['phoneNumber']))) {
			$valid = 'Phone Number must be in a valid format, e.g. (000) 000 0000.';
		}
		elseif(!preg_match("/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)-[0-9]{2}-[0-9]{4}$/i", trim($data['dob']))) {
			$valid = 'Date of Birth must be in the format of MMM-DD-YYYY.';
		}
	//passed all tests
		else {
			$valid = true;
		}
	}
	return $valid;
}

function killBadSpace($data, $requiredFields)
{
	foreach ($requiredFields as $required) {
		$data[$required] = trim(preg_replace('/\s+/', ' ',$data[$required]));//remove leading, trailing and double spaces, tabs and returns
	}
	return $data;
}

function saveToDB($what=NULL, $connection, $table){
	$data = empty($what) ? $_POST : $what; //array
	$link = $connection; //connection
	$table = empty($table) ? 'posts' : $table; //string

	$attribute = array(); //relational database terminology equivalent of column
	$value = array();

	foreach ($data as $key => $val) {
		$attribute[] = $key;
		$value[] = $val;
	}

	$sql_i = 'INSERT INTO ' . $table .' (' . implode(', ', $attribute) . ')
	VALUES (\'' . implode('\', \'', $value) . '\')';

	if(mysqli_query($link, $sql_i)) {
		return true;
	} else {
		return mysqli_error($link);
	}

}

function createUser($data, $requiredFields, $link){

	//format data
	$data['phoneNumber'] = preg_replace('/\D/', '', $data['phoneNumber'] );
	$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
	$data = killBadSpace($data, $requiredFields);

	//save data
	if (saveToDB($data, $link, 'logins') === true) {
		return true;
	} else {
		//error handling ?
		return false;
	}
}

function validateLogin($inputs)
{
	$num = preg_replace('/\D/', '', $inputs['phoneNumber']); //strips non-digits from input

	$link = connectToDatabase();
	$sql_s = "SELECT firstName, lastName, password FROM logins WHERE phoneNumber = $num;";
	$user = mysqli_fetch_array(mysqli_query($link, $sql_s));
	$stored_password = $user['password'];
	if(password_verify($inputs['password'], $stored_password)){
		$_SESSION['firstName'] = $user['firstName'];
		$_SESSION['lastName'] = $user['lastName'];

		return true;
	} else {
		return false; //pw didn't match
	}
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


function connectToDatabase()
{
	require ("includes/dbsettings.php");
	//contains $servername, $username, $password, $db

	$status = '';

	// Create connection
	$link = mysqli_connect($servername, $username, $password);

	// Check connection
	if (!$link) {
	    die("Connection failed: " . mysqli_connect_error());
	} else {
	    $status .= "<br>Connected to MySQL server.";
	}

	//Check presence / selectability of db
	$db_selected = mysqli_select_db($link, $db);


	if ($db_selected) {
		$status .=  "<br>Connected to database '$db'.";
		//echo($status);
		return $link;
	} else {
	  //Version without creation
		//$status .=  "<br>Error connecting to database: '$db'<br>" . mysqli_error($link);
		//die("<br>Error connecting to database: " . mysqli_error($link));

	  //Version with creation
	    $status .= "<br>Couldn't find database '$db', will attempt to create and use it.";
	    $sql_c = "CREATE DATABASE $db;";
		if(mysqli_query($link, $sql_c)) {
			$status .= "<br>Database created successfully";
			//try again to select
			if($db_selected = mysqli_select_db($link, $db)){ //i.e. now it should work
	            $status .=  "<br>And connected to database.";
	        } else {
	            $status .= " <br>Error connecting to database: " . mysqli_error($link);
			//	echo($status);
				return false;
	        }
	    } else {
	        $status .= (" <br>Error creating database: " . mysqli_error($link));
			//echo($status);
			return false;
	    }

	}
}

//debbugging
function checkForPresenceOfTables($link)
{
	//make sure connection still works, else create connection again.
	if (!mysqli_ping($link)) {
		echo "<br> Needed to reconnect";
		connectToDatabase();
	}

	//here would be code for checking for the presence of the data, else attempt to contact administrator I guess? Send like a "OMG the user data is gone!" email to myself - and maybe activate a 'site under maintenance' mode...

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
function logout($whereto=NULL)
{
	$whereto = (empty($whereto) ? './login.php' : $whereto );
	// Unset all of the session variables.
	$_SESSION = [];

	// This will destroy the session, and not just the session data
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
