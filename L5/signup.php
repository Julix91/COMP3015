<?php
session_start();
require ("includes/functions.php");


$message = '';
$posts = getPosts();
$requiredFields=['firstName','lastName','password','phoneNumber','dob'];
$storageFile="logindata.csv";

if(count($_POST) > 0)
{
    if(!checkInput($_POST, $requiredFields))
    {
        $message = '<div class="alert alert-warning alert-dismissable text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        All inputs are required!
                    </div>';
    }
	elseif(!validateInput($_POST, $requiredFields))
	{
		$message = '<div class="alert alert-warning alert-dismissable text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        All inputs have to be valid!
                    </div>';
	}
    else
    {
		foreach ($requiredFields as $required) {
			$data[$required] = trim($_POST[$required]);
		}
        save_as_CSV($data,$storageFile);

        $message = '<div class="alert alert-success alert-dismissable text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Thank you for joining ' . $_POST['firstName'] . ' ' . $_POST['lastName'] . '!
                    </div>';
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
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Create Account</h3>
                    </div>
                    <div class="panel-body">
						<?php echo $message; ?>
                        <form name="signup" role="form" action="signup.php" method="post">
                            <fieldset>
                                <div class="form-group">
									<label for="">Name</label>
                                    <input class="form-control" name="firstName" placeholder="First Name" type="text" autofocus />
                                    <input class="form-control" name="lastName" placeholder="Last Name" type="text"/>
                                </div>
                                <div class="form-group">
									<label for="password">Password</label>
                                    <input class="form-control" name="password" id="password" placeholder="At least one letter and one digit" type="password"/>
                                </div>
                                <div class="form-group">
									<label for="phoneNumber">Phone Number</label>
                                    <input class="form-control" name="phoneNumber" id="phoneNumber" placeholder="(XXX)-XXX-XXX" type="text" />
                                </div>
                                <div class="form-group">
									<label for="dob">Date of Birth</label>
                                    <input class="form-control" name="dob" id="dob" placeholder="MMM-DD-YYYY" type="text" />
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
