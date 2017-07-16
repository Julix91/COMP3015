<?php
require ("includes/functions.php");

$message = '';
$posts = getPosts();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if(!checkPresenceOfInput($_POST) || empty($_FILES["file"]['name']))
	{
		$message = '<div class="alert alert-danger alert-dismissable text-center">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						All inputs are required!
					</div>';
	}
	else if(!validateForm())
	{
		$message = '<div class="alert alert-warning alert-dismissable text-center">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						All inputs have to be valid.
					</div>';
	}
	else
	{

		$fileDestination = 'uploads/' . time(). $_FILES['file']['name'];

		move_uploaded_file($_FILES['file']['tmp_name'], $fileDestination);

		$data['firstName']  = trim($_POST['firstName']);
		$data['lastName']   = trim($_POST['lastName']);
		$data['title']      = trim($_POST['title']);
		$data['comment']    = trim($_POST['comment']);
		$data['priority']   = trim($_POST['priority']);
		$data['filename']   = time().$_FILES['file']['name'];

		saveInput($data);

		$message = '<div class="alert alert-success alert-dismissable text-center">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Thank you ' . $_POST['firstName'] . ' ' . $_POST['lastName'] . '! ' . date('F dS, Y', time()).'.
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
			<div class="col-md-6 col-md-offset-3">
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h3 class="login-panel text-center text-muted">It is now <?php echo formatDate(time());?></h3>
				<?php echo $message; ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<button class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-comment"></i> New Post</button>
				<a href="search.php" class="btn btn-default"><i class="fa fa-search"> </i> Search</a>
				<hr/>
			</div>
		</div>

		<?php
	if ($_SERVER['REQUEST_METHOD'] === 'GET') { //not sure why assignment requires this...
		$posts = sort_posts_by($posts, 'priority');
		$valids=0;//for handling if there's no valid ones. might also be useful for adding pagination later.
		foreach($posts as $post)
		{
			if(count($post) == 7){
				foreach ($post as $key => $value) {
					${$key} = $value;
				}
				$author = $firstName . ' ' . $lastName;//these variables were created by the loop above.
				$moment				 = moments(time() - $postedTime);
				$formattedPostedTime	= formatDate($postedTime);
				$formattedAuthor		= ucwords(strtolower($author));
				$valids++;
			} else {
				continue;
			}


			echo '
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel ' . ($priority == 1 ? 'panel-danger' : ($priority == 2 ? 'panel-warning' : 'panel-info' )) .'">
					<div class="panel-heading">
						<span>
							'.$title. '
						</span>
						<span class="pull-right text-muted">
						'.$moment.'
						</span>
					</div>
					<div class="panel-body">
						<p class="text-muted">Posted on
							'.$formattedPostedTime.'
						</p>
						<p>
							'.$comment.'
						</p>
						<div class="img-box">
							<img class="img-thumbnail img-responsive" src="uploads/'.$filename.'"/>
						</div>
					</div>
					<div class="panel-footer">
						<p> By
							' . $formattedAuthor .'
						</p>
					</div>
				</div>
			</div>
		</div>
			';
		} //closing the display foreach post loop
		if ($valids == 0) { ?>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel panel-danger">
						<div class="panel-heading">
							<span>
								Don't panic!
							</span>
						</div>
						<div class="panel-body">
							<p class="text-muted">No valid posts here. Be the first to make one by pressing "New Post" above!
							</p>
						</div>
					</div>
				</div>
			</div>
		<?php } //closing if no valids
	} //closing if(GET)
	else {
		echo "There would be posts here if it was a GET request.";
	}?>
	</div>
</div>




<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
	<form role="form" method="post" action="index.php" enctype="multipart/form-data">
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
					<label for="title">Title</label>
					<input class="form-control" placeholder="" name="title">
				</div>
				<div class="form-group">
					<label for="comment">Comment</label>
					<textarea class="form-control" rows="3" name="comment"></textarea>
				</div>
				<div class="form-group">
					<label for="priority">Priority</label>
					<select class="form-control" name="priority">
						<option value="1">Important</option>
						<option value="2">High</option>
						<option value="3">Normal</option>
					</select>
				</div>
				<div class="form-group">
					<label for="file">Image</label>
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

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
