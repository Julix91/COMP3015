<?php
require ("includes/functions.php");
session_start();
//kill if unauthorized
if(empty($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true){
	redirect('login.php');
	die("What are you still doing here!? I thought I redirected you <a href=\"./login.php\">here</a>...");
}

$message = !empty($_SESSION['loginMessage']) ? $_SESSION['loginMessage'] : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$fieldInput = validateFields($_POST);
	$fileInput  = isValidFile($_FILES['file']);

	if($fieldInput != false && $fileInput != false) {
		$fieldInput['file'] = $_FILES['file']['tmp_name'];
		insertPost($fieldInput);
	} else {
		$message = wrapAlert('Invalid input!', 'warning');
	}
}

$posts = getPosts();

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
				<h3 class="login-panel text-center text-muted">It is now <?php echo date('l F \t\h\e dS, Y', time());?></h3>
				<?php echo $message; ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<button class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-comment"></i> New Post</button>
				<a href="search.php" class="btn btn-default"><i class="fa fa-search"> </i> Search</a>
				<a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>
				<hr/>
			</div>
		</div>

		<?php
			if(count($posts) > 0)
			{
				foreach($posts as $post)
				{
					$filteredPost = filterPost($post);

					$panelTag = '';
					if($filteredPost['priority'] == 1)
					{
						$panelTag = 'panel-danger';
					}
					elseif($filteredPost['priority'] == 2)
					{
						$panelTag = 'panel-warning';
					}
					else
					{
						$panelTag = 'panel-info';
					}

					echo '
						<div class="row" id="post-'. $filteredPost['id'] .'">
							<div class="col-md-6 col-md-offset-3">
								<div class="panel ' . $panelTag . '">
									<div class="panel-heading">
										<span>
											'. $filteredPost['title'] .'
										</span>
										<span class="pull-right text-muted">
											' . $filteredPost['moment'] . '
										</span>
									</div>
									<div class="panel-body">
										<p class="text-muted">
											Posted on ' . $filteredPost['postedTime'] .'
										</p>
										<p>
											' . $filteredPost['comment'] . '
										</p>
										<div class="img-box">
											<img class="img-thumbnail img-responsive" src="uploads/'.$filteredPost['filename'].'"/>
											<a href="download.php?filename='.$filteredPost['filename'].'" class="btn btn-default pull-right" ><i class="fa fa-download"> </i> Download</a>
										</div>
									</div>
									<div class="panel-footer">
										<p>
											By ' . $filteredPost['author'] . '
										</p>
									</div>
								</div>
							</div>
						</div>
					';
				}
			}
			else
			{
				echo '
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<div class="panel panel-info">
								<div class="panel-heading">
									<span class="pull-right text-muted">
									</span>
								</div>
								<div class="panel-body">
									<span class="text-muted">
										No posts found!
									</span>
								</div>
								<div class="panel-footer">
								</div>
							</div>
						</div>
					</div>
				';
			}
		?>
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
					<label>First Name
						<input class="form-control" placeholder="First Name" name="firstName" value="<?php echo $_SESSION['firstName']?>">
					</label>
				</div>
				<div class="form-group">
					<label>Last Name
						<input class="form-control" placeholder="Last Name" name="lastName" value="<?php echo $_SESSION['lastName'] ?>">
					</label>
				</div>
				<div class="form-group">
					<label>Title
						<input class="form-control" placeholder="" name="title">
					</label>
				</div>
				<div class="form-group">
					<label>Comment
						<textarea class="form-control" rows="3" name="comment"></textarea>
					</label>
				</div>
				<div class="form-group">
					<label>Priority
						<select class="form-control" name="priority">
							<option value="1">Important</option>
							<option value="2">High</option>
							<option value="3">Normal</option>
						</select>
					</label>
				</div>
				<div class="form-group">
					<label>Image
						<input type="file" name="file" />
					</label>
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
