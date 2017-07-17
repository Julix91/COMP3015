<?php
session_start();
require ("includes/functions.php");
//If already logged in go straight to content
if(!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn']===true){
	redirect('./');
	die("What are you still doing here? Shouldn't you be enjoying the <a href=\"./\">posts</a>?");
}

$phoneNumber = '';
$message = "";
$requiredFields=['password','phoneNumber'];

$remember = (!empty($_COOKIE['remember']) && $_COOKIE['remember'] == true) ? true : false;
if($remember){
	$phoneNumber = $_COOKIE['phoneNumber'];
	$rememberCheckbox = true;
	$message=wrapAlert("Welcome back!",'success');
} else {
	$rememberCheckbox = false;
}

if(isset($_POST['remember']) && $_POST['remember'] == 1) {
	setcookie('phoneNumber', $_POST['phoneNumber'], time() + 60 * 60 * 24 * 20);	// 60 seconds, 60 minutes, 24 hours, 20 days
	$phoneNumber = $_POST['phoneNumber'];
} elseif(isset($_COOKIE['phoneNumber'])) {
	$phoneNumber = $_COOKIE['phoneNumber'];
}

if(isset($_POST['phoneNumber']) && !isset($_POST['remember'])) {
	setcookie('phoneNumber', null, time() - 3600);
	$phoneNumber = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$rememberCheckbox = !empty($_POST['remember']) && $_POST['remember'] == 1 ? true : false;
	$phoneNumber = !empty($_POST['phoneNumber']) ? $_POST['phoneNumber'] :  "wat? no # received!";

	if(!checkPresenceOfInput($_POST, $requiredFields)) {
		$message = wrapAlert('All inputs are required!', 'warning');
	} elseif(!validateLogin($_POST)) {
		$message = wrapAlert('Username or password was wrong. Try again or sign up below.', 'info');
	}
	else //present and user exists -> login
	{

	//code to remember the preference of remembering or forgetting
		if($rememberCheckbox){
			$remember=true;
			setcookie("remember", "true", time() + 60*60*24*20, "/", "localhost", false, false);
			setcookie("phoneNumber", "$phoneNumber", time() + 60*60*24*20, "/", "localhost", false, false);
			$_SESSION['loginMessage']=wrapAlert("You just signed in. Phone number will be remembered for 20 days", 'info');
		} else{
			$remember = false;
			setcookie("remember", "false", 1, "/", "localhost", false, false);
			setcookie("phoneNumber", "$phoneNumber", 1, "/", "localhost", false, false);
			$_SESSION['loginMessage']=wrapAlert("You just signed in. Phone number will not be remembered, and forgotten if it was stored before", 'info');
		}

		$_SESSION["loggedIn"]=true;
		redirect('./');
	}

}
//complain after breakin
if (!empty($_SESSION['breakIn'])){
	echo '<script>alert("What were you trying to do?")</script>';
	$message = $_SESSION['breakIn'];
	unset($_SESSION['breakIn']);
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>COMP 3015</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">

	<div class="container">

		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<h1 class="login-panel text-center text-muted">COMP 3015</h1>
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Please Sign In</h3>
					</div>
					<div class="panel-body">
						<?php echo $message; ?>
						<form name="login" role="form" action="login.php" method="post">
							<fieldset>
								<div class="form-group">
									<input class="form-control"
										   value="<?php echo $phoneNumber;?>"
										   name="phoneNumber"
										   placeholder="Phone Number"
										   type="text"
										<?php echo empty($phoneNumber) ? 'autofocus' : ''; ?>
									/>
								</div>
								<div class="form-group">
									<input class="form-control"
										   name="password"
										   placeholder="Password"
										   type="password"
										<?php echo empty($phoneNumber) ? '' : 'autofocus'; ?>
									/>
								</div>
								<div class="form-group">
									<input type="checkbox"
										   value="1"
										   name="remember"
										<?php echo empty($phoneNumber) ? '' : 'checked'; ?>
									/>
									Remember Me
								</div>
								<input type="submit" class="btn btn-lg btn-success btn-block" value="Login"/>
							</fieldset>
						</form>
					</div>
				</div>
				<a class="btn btn-sm btn-default" href="signup.php">Sign Up</a>
			</div>
		</div>

	</div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
