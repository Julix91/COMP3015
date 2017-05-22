<?php

require ("includes/functions.php");

$posts = get_post_data("posts.txt");
if(isset($posts['error'])){
	$html_posts = "";
	foreach($posts['error'] as $code){
		$html_posts .= $code . "<br>";
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
                <?php echo $message; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost">New Post</button>
                <hr/>
            </div>
        </div>

		<?php echo $html_posts;?>

    </div>
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="lab4.php" enctype="multipart/form-data">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Post</h4>
        </div>
        <div class="modal-body">
                <div class="form-group">
                    <input class="form-control" placeholder="First Name" name="firstName">
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Last Name" name="lastName">
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" placeholder="" name="title">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <textarea class="form-control" rows="3" name="comment"></textarea>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <select class="form-control" name="priority">
                        <option value="1">Important</option>
                        <option value="2">High</option>
                        <option value="3">Normal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="file" />
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Post!"/>
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
