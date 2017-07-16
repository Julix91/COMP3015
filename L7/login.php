<?php
session_start();
//If already logged in go straight to content
require ("includes/functions.php");
if(!empty($_SESSION['loggedIn'])){
	redirect(glob('lab[0-9].php')[0]);
} else {

		$requiredFields=['password','phoneNumber'];
		$storageFile="logindata.csv";

	function remember($phoneNumber) {
		setcookie("remember", "true", time() + 60*60*24*20, "/", "localhost", false, false);
		setcookie("phoneNumber", "$phoneNumber", time() + 60*60*24*20, "/", "localhost", false, false);
	}

	function forget($phoneNumber) {
		setcookie("remember", "false", 1, "/", "localhost", false, false);
		setcookie("phoneNumber", "$phoneNumber", 1, "/", "localhost", false, false);
	}

	$remember = (!empty($_COOKIE['remember']) && $_COOKIE['remember'] == true) ? true : false;
	if($remember){
		$phoneNumber = $_COOKIE['phoneNumber'];
		$rememberCheckbox = true;
		$message="Welcome back!";
	} else {
		$rememberCheckbox = false;
		$message="No phone cookie yet, eh?";
	}

	$message = "";
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$rememberCheckbox = !empty($_POST['remember']) && $_POST['remember'] == 1 ? true : false;
		$phoneNumber = !empty($_POST['phoneNumber']) ? $_POST['phoneNumber'] :  "wat? no # received!";

		if(!checkPresenceOfInput($_POST, $requiredFields))
		{
			$message = '<div class="alert alert-warning alert-dismissable text-center">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							All inputs are required!
						</div>';
		}
		elseif(!validateUser($_POST, $storageFile))
		{
			$message = '<div class="alert alert-warning alert-dismissable text-center">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							The input didn\'t match any users. Try again or sign up below.
						</div>';
		}
		else //present and real user -> login
		{
			if($rememberCheckbox){
				remember($phoneNumber);
				$remember=true;
				$_SESSION['loginMessage']=wrapAlert("You just signed in. Phone number will be remembered for 20 days", 'info');
			} else{
				forget($phoneNumber);
				$remember = false;
				$_SESSION['loginMessage']=wrapAlert("You just signed in. Phone number will not be remembered, and forgotten if it was stored before", 'info');
			}
			$_SESSION["loggedIn"]=true;
			redirect(glob('lab[0-9].php')[0]);
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
					<p>Added more loose checking of phoneNumber by stripping input and stored value of non-digits when comparing, so that you can login with any input that contains the right numbers in the right order (if your password is an exact match).</p>
	                <div class="login-panel panel panel-default">
	                    <div class="panel-heading">
	                        <h3 class="panel-title">Please Sign In</h3>
	                    </div>
	                    <div class="panel-body">
								<?php echo $message; ?>
	                        <form name="login" role="form" action="login.php" method="post">
	                            <fieldset>
	                                <div class="form-group">
	                                    <input class="form-control" name="phoneNumber" placeholder="Phone Number" type="text" <?php echo $remember ? "value=\"$phoneNumber\"" : "autofocus"; ?> />
	                                </div>
	                                <div class="form-group">
	                                    <input class="form-control" name="password" placeholder="Password" type="password" <?php echo $remember ? "autofocus" : ""; ?> />
	                                </div>
	                                <div class="form-group">
										<input type='hidden' value='0' name='remember'>
	                                    <input type="checkbox" value="1" name="remember" <?php echo ($rememberCheckbox)?"checked":"" ?> /> Remember Me
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
<?php } //closing else (not already logged in)
