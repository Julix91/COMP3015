<?php

require ("includes/functions.php");
$message = '';

if(count($_POST) > 0)
{
    $check = checkSignUp($_POST);

    if($check === true)
    {
        $message = '<div class="alert alert-success text-center">
                        Thank you for signing up!
                    </div>';
    }
    else
    {
        $message = '<div class="alert alert-danger text-center">
                        '.$check.'
                    </div>';
    }
}

$fieldnames=['firstName', 'lastName', 'phoneNumber', 'dob'];
$data=[];
$signup_view_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	foreach ($fieldnames as $field) {
		${$field} = !empty($_POST[$field]) ? $_POST[$field] : "";
		setcookie($field, $_POST[$field], time() + 60*60*24*20, "/", "localhost", false, false);
	}

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	foreach ($fieldnames as $field) {
		${$field} = (!empty($_COOKIE[$field]) ? $_COOKIE[$field] : "");
	}
}

var_dump($_COOKIE);



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
				<p>FYI this is my second attempt. The <a href="./signup-single-cookie-edition.php">other solution</a> uses only one cookie based on misreading the lab instructions. Also note the phoneNumber cookie is shared between login and signup, that's on purpose. As expiration time for signup wasn't specified, same value as login was used.</p>
                <?php echo $message; ?>

                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Create Account</h3>
                    </div>
                    <div class="panel-body">
                        <form name="signup" role="form" action="signup.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" name="firstName" placeholder="First Name" type="text" autofocus <?php echo (!empty($firstName) ? "value=\"$firstName\"" : ""); ?> />
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="lastName" placeholder="Last Name" type="text" <?php echo (!empty($lastName) ? "value=\"$lastName\"" : ""); ?>/>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="password" placeholder="Password" type="password"/>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="phoneNumber" placeholder="Phone Number" type="text" <?php echo (!empty($phoneNumber) ? "value=\"$phoneNumber\"" : ""); ?>/>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="dob" placeholder="Date of Birth" type="text" <?php echo (!empty($dob) ? "value=\"$dob\"" : ""); ?> />
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
