<?php

require ("includes/functions.php");

?>

<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
	<style>
	.alert {
		padding: 0;
		margin-bottom: 0;
	}</style>
</head>
<body>

<div id="wrapper">

    <div class="container">

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h3 class="login-panel text-center text-muted">It is now <?php echo $formattedCurrentTime; ?></h3>
                <?php echo (isset($message))?($message):""; ?>
            </div>
        </div>

		<div class="row">
            <div class="col-md-6 col-md-offset-3">
				<?php if (isset($_POST['started']) && $_POST['started']==="started"){
					include "./templates/message.php";
				}?>
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><?php echo ( ($_POST['started'] == "started" && !($success) ) ? "Edit Last Post" : "New Post"); ?></button>
				<hr/>
			</div>
		</div>

		<?php echo $html_posts;?>

    </div>
</div>
<?php include "./templates/form.php" ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php/* //this doens't do it yet, but I want to unset upon success without breaking everything
if($success){
	unset($_FILES); unset($_POST);
}*/
?>
