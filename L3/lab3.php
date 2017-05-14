<?php

var_dump($_POST);

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('functions.php');

// do not modify these variables
$postedTime = 1481808630;
$author = "   gary TonG  ";
$content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.";

// modify these variables
$formattedAuthor        =  trim(ucwords(strtolower($author)));
$formattedCurrentTime   = date('l F \t\h\e dS, Y', time());
$formattedPostedTime    = date('l F \t\h\e dS, Y', $postedTime);
$moment                 = moments(time() - $postedTime);

/********************************************************************/

// define content variables and set to empty values
$name = $l_name = $gender = $comment = $priority = $received = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	foreach ($_POST as $key=>$val){
		/* $_POST[$key] = */ ${$key} = test_input($val);
		//considered overwriting the values after cleaning, but risked changing it upon each submission, i.e. & becomes &amp; which becomes &amp;amp; etc due to escaping special characters...
		var_dump(${$key});
	}
}
/*There probably is a way to itterate through the keys of the $_POST objects and write a fancy for loop that creates variables from them, right? Seems like a lot of repetition here... - Imagine if there was more variables!*/

//same for error messages, though I guess since all are required there could just be one message, but slightly better UX
$f_nameErr = $l_nameErr = $commentErr = $priorityErr = "";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
	//echo "GTFO you little hacker!";
	//realized that also happens when you first access the post.
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
/* //Reset functionality still in progress - not digging deeper, since probably session management is the best solution.
	if (isset($_POST["Reset"])){
  	  unset($_POST);
  	  echo "Successfully reset!";
	  echo '<script> $("#new-post-form").find("input, textarea").val("") </script>';
    }
*/
  if (empty($_POST["f_name"])) {
    $f_nameErr = "Your first name is required";
  } else {
    $f_name = ucwords(strtolower(test_input($_POST["f_name"])));
	//wanted to check for non-numeric characters, but didn't work...

	check_wordness($f_name, 'f_name');

  }

  if (empty($_POST["l_name"])) {
    $l_nameErr = "Your last name is required";
  } else {
    $l_name = ucwords(strtolower(test_input($_POST["l_name"])));
	/*
	check_wordness($l_name, 'l_name');
	*/
  }

  if (empty($_POST["priority"])) {
    $priorityErr = "You've gotta tell me how important it is";
  } else {
    $priority = test_input($_POST["priority"]);
  }

  if (empty($_POST["comment"])) {
    $commentErr = "Seriously, if you've got nothing to say, why bother at all?";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (isset($_POST["f_name"], $_POST["l_name"], $_POST["priority"], $_POST["comment"]) && empty($success)) {
	  $success = true;
	  $received = time();
	  $received_formatted = date('l F \t\h\e dS, Y', $received);
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
            <div class="col-md-6 col-md-offset-3">
                <h3 class="login-panel text-center text-muted">It is now <?php echo $formattedCurrentTime; ?></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
				<?php if (isset($success) && $success===true): ?>
					<p class="panel panel-info">Thank you <?php echo "$f_name $l_name"; ?> for posting! Received <?=$received_formatted?> </p>
				<?php endif; ?>
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><?php echo ((empty($success)) ? "New Post" : "Edit Last Post"); ?></button>
                <hr/>

            </div>
        </div>
		<?php if (isset($success) && $success===true): ?>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel panel-info">
						<div class="panel-heading">
							<span>
								Your post is being reviewed by a moderator.
							</span>
							<span class="pull-right text-muted">
								<?php echo moments(time() - $received); ?>
							</span>
							<?php if ($priority >= 4): ?>
								<i><br>Please be patient, it might take a while!</i>
							<?php endif; ?>
						</div>
						<div class="panel-body">
							<p class="text-muted">Posted on
								<?php echo $received_formatted ?>
							</p>
							<p>
								<?php echo $comment; ?>
							</p>
						</div>
						<div class="panel-footer">
							<p> By
								<?php echo "$f_name $l_name"; ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>
                            First Post!
                        </span>
                        <span class="pull-right text-muted">
                            <?php echo $moment; ?>
                        </span>
                    </div>
                    <div class="panel-body">
                        <p class="text-muted">Posted on
                            <?php echo $formattedPostedTime; ?>
                        </p>
                        <p>
                            <?php echo $content; ?>
                        </p>
                    </div>
                    <div class="panel-footer">
                        <p> By
                            <?php echo $formattedAuthor; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Post</h4>
        </div>
        <div class="modal-body">

	            <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="make-new-post" id="new-post-form">
				<div class="panel panel-info text-center">FYI: All inputs are required!</div>
                <div class="form-group">
					<label for="f_name">First Name</label>
                    <input id="f_name" class="form-control" name="f_name" placeholder="First Name" value="<?php echo (isset($f_name) ? $f_name : "") ?>" >
						<span class="error"><?php echo $f_nameErr;?></span>
                </div>
                <div class="form-group">
					<label for="l_name">Last Name</label>
                    <input id="l_name" class="form-control" name="l_name" placeholder="Last Name" value="<?php echo $l_name ?>" >
					<span class="error"><?php echo $l_nameErr;?></span>
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
					<span class="error">* <?php echo $commentErr;?></span>
                    <textarea id="comment" class="form-control" name="comment" rows="3" ><?php echo $comment ?></textarea>
                </div>
                <div class="form-group">
                    <label for="priority">Priority</label>
					<span class="error">* <?php echo $priorityErr;?></span>
                    <select id="priority" class="form-control" name="priority" >
						<?php if ($priority == "") { ?>
							<option selected disabled>Choose here</option>
						<?php } ?>
                        <option value="1" <?php echo  ($priority == 1) ? "selected" : ""?> >Crucial</option>
                        <option value="2" <?php echo  ($priority == 2) ? "selected" : ""?> >Important</option>
                        <option value="3" <?php echo  ($priority == 3) ? "selected" : ""?> >High</option>
                        <option value="4" <?php echo  ($priority == 4) ? "selected" : ""?> >Normal</option>
                        <option value="5" <?php echo  ($priority == 5) ? "selected" : ""?> >Un-high</option>
                    </select>
                </div>
            </form>

        </div>
        <div class="modal-footer">
			<?php /* reset didn't work <input form="new-post-form" type="submit" value="Reset" class="btn btn-danger" name="Reset"  onclick="document.getElementById("new-post-form").reset(); " /> */?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input form="new-post-form" type="submit" value="Submit Post" class="btn btn-primary" />
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>
