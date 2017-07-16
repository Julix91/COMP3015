<?php
session_start();
require ("includes/functions.php");

/*Settings*/
$storageFile="logindata.csv";
$requiredFields=['firstName','lastName','password','phoneNumber','dob'];

/*Variable declarations*/
foreach ($requiredFields as $required){
	${$required} = "";
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	//set variables even if invalid
	foreach ($requiredFields as $required){
		if(isset($_POST[$required]) && !empty(trim($_POST[$required]))){
			${$required} = trim($_POST[$required]);
		}
	}

	if(!checkPresenceOfInput($_POST, $requiredFields)){
		$message = wrapAlert("All inputs are required!");
	} elseif(!validateInput($_POST, $requiredFields)) {
		$message = wrapAlert("All inputs have to be valid!");
	} else {
		//inputs present and valid, so save'em
		$data = array();
		$_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
		foreach ($requiredFields as $required) {
			$data[$required] = trim($_POST[$required]);
		}
		saveAsCSV($data,$storageFile);
		$_SESSION['loggedIn']=true;
		$_SESSION['loginMessage'] = wrapAlert('Thank you for joining ' . $_POST['firstName'] . ' ' . $_POST['lastName'] . '!', 'success');
		redirect(glob('lab[0-9].php')[0]);
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
                                           type="text"
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
