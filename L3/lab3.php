<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('auto_detect_line_endings',TRUE);

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

// define variables and set to empty values
$first_name = $last_name = $gender = $comment = $priority = $received = "";
$first_nameErr = $last_nameErr = $commentErr = $priorityErr = $success = "";

//receive_form();

//On submit mark form as started, clean up inputs and namify names
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$started = true;
	foreach ($_POST as $key=>$val){
		if(empty(trim($val))){

			if($key === "priority"){
				${$key . "Err"} = "You've gotta tell me how <em>important</em> it is to you!";
			} else if ($key === "comment"){
				${$key . "Err"} = "Seriously, if you've got nothing to say, why bother at all?";
			} else {
				${$key . "Err"} = "Your <em>" . namify($key) . "</em> is required!";
			}
			echo $key . " " . ${$key} . "<br>";
		} else {
			/*$_POST[$key] = */${$key} = clean_input($val);
			if($key === "first_name" || $key === "last_name"){
				${$key} = namify(${$key});
			}
		}
	}
	//check for successfulness of submission
if (isset($_POST["first_name"], $_POST["last_name"], $_POST["priority"], $_POST["comment"])
//&& (not_mempty($_POST["first_name"], $_POST["last_name"], $_POST["priority"], $_POST["comment"]))
//still working on more elegant multi-not-empty
&& ( (!empty($_POST["first_name"])) && (!empty($_POST["last_name"])) && (!empty($_POST["priority"])) && (!empty($_POST["comment"])) )
) {
		$success = true;
		$_POST["received"] = time();
		$received_formatted = date('l F \t\h\e dS, Y', $_POST["received"]);

		save_as_CSV($_POST, 'file.csv');
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
	<style>.alert{padding: 5px; margin-bottom: 2px;}</style>
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
				<?php if (isset($started) && $started===true && (!isset($success) || $success!==true)): ?>
					<p class="alert alert-danger">The post has not yet been successfully submitted. Click on "New Post" to keep editing.</p>
				<?php endif; ?>
				<?php if (isset($success) && $success===true): ?>
					<p class="alert alert-success">Thank you <?php echo "$first_name $last_name"; ?> for posting! Received <?=$received_formatted?> </p>
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
								<?php echo "$first_name $last_name"; ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php
		//read CSV string to array of posts (and decode)
		$posts = get_post_data();
		var_dump($posts);
		function write_post($post){

		}
		foreach ($posts as $post) { ?>
			<?php /*template for single posts*/ ?>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel panel-info">
						<div class="panel-heading">
							<span>
								First Post!
							</span>
							<span class="pull-right text-muted">
								<?php echo moments($post["received"]); ?>
							</span>
						</div>
						<div class="panel-body">
							<p class="text-muted">Posted on
								<?php echo format_date($post["received"]); ?>
							</p>
							<p>
								<?php echo $post["comment"]; ?>
							</p>
						</div>
						<div class="panel-footer">
							<p> By
								<?php echo $post["first_name"] . $post["last_name"]; ?>
							</p>
						</div>
					</div>
				</div>
			</div>
<?php		}
		 ?>
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
					<label for="first_name">First Name</label>
					<?php echo ( (!empty($first_nameErr)) ? '<div class="alert alert-warning">' . $first_nameErr . '</div>' : "") ;?>
                    <input id="first_name" class="form-control" name="first_name" placeholder="First Name" value="<?php echo (isset($first_name) ? $first_name : "") ?>" >
                </div>
                <div class="form-group">
					<label for="last_name">Last Name</label>
					<?php echo ((!empty($last_nameErr)) ? '<div class="alert alert-warning">' . $last_nameErr . '</div>' : "") ;?>
                    <input id="last_name" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo $last_name ?>" >
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
					<?php echo ((!empty($commentErr)) ? '<div class="alert alert-warning">' . $commentErr . '</div>' : "") ;?>
                    <textarea id="comment" class="form-control" name="comment" rows="3" placeholder="Your message for the world" ><?php echo $comment ?></textarea>
                </div>
                <div class="form-group">
                    <label for="priority">Priority</label>
					<?php echo ((!empty($priorityErr)) ? '<div class="alert alert-warning">' . $priorityErr . '</div>' : "") ;?>
                    <select id="priority" class="form-control" name="priority" >
						<option value="" selected style="color: gray">Choose here</option>
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
