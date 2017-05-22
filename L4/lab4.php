<?php

require ("includes/functions.php");

$posts = get_post_data("posts.txt");
if(isset($posts['error'])){
	$html_posts = "";
	foreach($posts['error'] as $code){
		$html_posts .=
		"<div class=\"alert alert-info text-center row col-md-6 col-md-offset-3\">
			$code
		</div>";
	}
} else {
	$sorted_posts = sort_posts_by('priority', $posts);
	$html_posts = display_posts($sorted_posts);
}
$formattedCurrentTime = format_date(time());

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
                <?php echo (isset($message))?($message):""; ?>
            </div>
        </div>

		<div class="row">
            <div class="col-md-6 col-md-offset-3">
				<?php if (isset($_POST['started']) && $_POST['started']==="started"){
					include "./templates/message.php";
				}?>
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><?php echo ($_POST['started'] !== "started" && $_POST['started']  !== "completed" ? "New Post" : "Edit Last Post"); ?></button>
				<hr/>
			</div>
		</div>

		<?php echo $html_posts;?>

    </div>
</div>
<?php include "./templates/newpost.php" ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
