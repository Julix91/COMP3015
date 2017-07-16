<?php

$message = "";

function remember($pnum) {
	setcookie("remember", "true", time() + 60*60*24*20, "/", "localhost", false, false);
	setcookie("phoneNumber", "$pnum", time() + 60*60*24*20, "/", "localhost", false, false);
}

function forget($pnum) {
	setcookie("remember", "false", 1, "/", "localhost", false, false);
	setcookie("phoneNumber", "$pnum", 1, "/", "localhost", false, false);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$rem_chkbx = !empty($_POST['remember']) && $_POST['remember'] == 1 ? true : false;
	$pnum = !empty($_POST['phoneNumber']) ? $_POST['phoneNumber'] :  "wat? no # received!";

	if($rem_chkbx){
		remember($pnum);
		$rem=true;
		$message="You just signed in. Phone number will be remembered for 20 days";
	} else{
		forget($pnum);
		$rem = false;
		$message="You just signed in. Phone number will not be remembered, and forgotten if it was stored before";
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$rem = !empty($_COOKIE['remember']) && $_COOKIE['remember'] == true ? true : false;
	if($rem){
		$pnum = $_COOKIE['phoneNumber'];
		$rem_chkbx = true;
		$message="Welcome back!";
	} else {
		$rem_chkbx = false;
		$message="No phone cookie yet, eh?";
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
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
						<div class="panel panel-info">
							<?php echo $message; ?>
						</div>
                        <form name="login" role="form" action="login.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" name="phoneNumber" placeholder="Phone Number" type="text" <?php echo $rem ? "value=\"$pnum\"" : "autofocus"; ?> />
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="password" placeholder="Password" type="password" <?php echo $rem ? "autofocus" : ""; ?> />
                                </div>
                                <div class="form-group">
									<input type='hidden' value='0' name='remember'>
                                    <input type="checkbox" value="1" name="remember" <?php echo ($rem_chkbx)?"checked":"" ?> /> Remember Me
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
