<?php
require ("includes/functions.php");

/*Settings*/
$storageFile="logindata.csv";
$requiredFields=['firstName','lastName','password','phoneNumber','dob'];

/*Declaring variables and if present set their value in cookie*/
foreach ($requiredFields as $required){
	${$required} = empty($_COOKIE[$required]) ? "" : $_COOKIE[$required];
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	//Set variables to their posted content (if any) even if invalid
	//Also set cookie
	foreach ($requiredFields as $key => $required){
		if(isset($_POST[$required]) && !empty(trim($_POST[$required]))){
			${$required} = trim($_POST[$required]);
			if ($key !== 2) { //i.e. for anything but the password
				setcookie($required, $_POST[$required], time() + 60 * 60);
			}
		}
	}

	$link = connectToDatabase();
	//var_dump(checkForPresenceOfTables($link));

	$check = checkSignUp($_POST, $requiredFields, $link);//true on passing, else message

	if ($check !== true) {
		$message = wrapAlert($check);
	} else { //data is present and matches patterns

		//try to create user
		if(createUser($_POST, $requiredFields, $link)){
			$_SESSION['loggedIn']=true;
			$_SESSION['firstName'] = $_POST['firstName'];
			$_SESSION['lastName'] = $_POST['lastName'];
			$message = $_SESSION['loginMessage'] = wrapAlert('Thank you for joining ' . $_POST['firstName'] . ' ' . $_POST['lastName'] . '!', 'success');
			redirect('./');
		} else {
			$message = wrapAlert('Something went wrong... Sorry, eh!', 'danger');
		}
	}
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

				<?php echo $message; ?>

				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Create Account</h3>
					</div>
					<div class="panel-body">
						<form name="signup" role="form" action="signup.php" method="post">
							<fieldset>
								<div class="form-group">
									<input class="form-control"
										   value="<?php echo $firstName;?>"
										   name="firstName"
										   placeholder="First Name"
										   type="text"
										   autofocus
									/>
								</div>
								<div class="form-group">
									<input class="form-control"
										   value="<?php echo $lastName;?>"
										   name="lastName"
											placeholder="Last Name"
											type="text"
									/>
								</div>
								<div class="form-group">
									<input class="form-control"
										   name="password"
										   placeholder="Password"
										   type="password"
									/>
								</div>
								<div class="form-group">
									<input class="form-control"
										   value="<?php echo $phoneNumber;?>"
										   name="phoneNumber"
										   placeholder="Phone Number"
										   type="tel"
									/>
								</div>
								<div class="form-group">
									<input class="form-control"
										   value="<?php echo $dob;?>"
										   name="dob"
										   placeholder="Date of Birth"
										   type="text"
									/>
								</div>
								<input type="submit" class="btn btn-lg btn-info btn-block" value="Sign Up!"/>
							</fieldset>
						</form>
					</div>
				</div>
				<a class="btn btn-sm btn-default" href="login.php">Login</a>
			</div>
		</div>

	</div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
