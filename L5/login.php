<?php
session_start();
	require ("includes/functions.php");
	$message = '';
	$posts = getPosts();
	$requiredFields=['password','phoneNumber'];
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
		elseif(!validateUser($_POST, $storageFile))
		{
			$message = '<div class="alert alert-warning alert-dismissable text-center">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        The input didn\'t match any users. Try again or sign up below.
	                    </div>';
		}
	    else
	    {
			$_SESSION["loggedIn"]=true;
			redirect('./lab5.php');
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
						<?php echo $message; ?>
                        <form name="login" role="form" action="login.php" method="post">
                            <fieldset>
                                <div class="form-group">
									<label for="phoneNumber">Phone Number</label>
                                    <input class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Exact format used when signing up!" type="text" autofocus />
                                </div>
                                <div class="form-group">
									<label for="password">Password</label>
                                    <input class="form-control" name="password" id='password' placeholder="You do remember you password, right?" type="password"/>
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
