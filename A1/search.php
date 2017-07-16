<?php
require ("includes/functions.php");
$matches =[];
$message = '';
if($_SERVER['REQUEST_METHOD'] === 'GET') {
	if(empty($_GET['searchKeyword'])){
		$message = '<div class="alert alert-info alert-dismissable text-center">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Enter a keyword to look for it anywhere in the comment text.
					</div>';
	} else if(!preg_match('/^[a-zA-Z]+$/', $_GET['searchKeyword']) > 0){
		$message = '<div class="alert alert-danger alert-dismissable text-center">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						You can only search for one bunch of letters!
					</div>';
	} else {
		$posts = getPosts();//includes non-valids!
		$posts = sort_posts_by($posts, 'priority');
		foreach ($posts as $postNumber => $post) {
			if(count($post) == 7){//only full posts
				//uncomment the following to match in title too!
				/*if(strpos($post['title'], $_GET['searchKeyword']) !== false){
					$matches[] = $post;
				} else */ if(strpos($post['comment'], $_GET['searchKeyword']) !== false){
					$matches[] = $post;
				}
			}
		}
		if(count($matches) == 0){
			$message = '<div class="alert alert-info alert-dismissable text-center">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Your search keyword did not return any results.
						</div>';
		}
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
                <h3 class="login-panel text-center text-muted">Search comment text</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <a href="/" class="btn btn-default"><i class="fa fa-arrow-circle-left"> </i> Back</a>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form role="form" method="GET" action="search.php">
                    <div class="form-group input-group">
                        <input type="text" placeholder="Search keyword" class="form-control" name="searchKeyword">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Results
                    </div>
					<?php echo $message; ?>
                    <!-- /.panel-heading -->
				<?php if(count($matches) > 0){ ?>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Title</th>
										<th>Time</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($matches as $matchNumber => $post) {
										echo '
									<tr class="' . ($post['priority'] == 1 ? 'danger' : ($post['priority'] == 2 ? 'warning' : 'info' )) . '">
										<td>'. $post['firstName'] . ' ' . $post['lastName'] . '</td>
										<td>'. $post['title'] . '</td>
										<td>'. date('M d, \'y', $post['postedTime']) . '</td>
									</tr>';
									} //closing foreach ?>
								</tbody>
							</table>

					<?php } //closing if matches ?>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <p class="text-center text-muted">
                    Total results: <?php echo count($matches);  ?>.
                </p>
            </div>
        </div>

    </div>
</div>

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
