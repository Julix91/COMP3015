<?php

// do not modify these variables
$postedTime = 1481808630;
$author = "   gary TonG  ";
$content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.";

// modify these variables
$formattedAuthor        = "John Doe";
$formattedCurrentTime   = "Wednesday January the 20th, 2016.";
$formattedPostedTime    = "Wednesday January the 20th, 2016.";
$moment                 = "just now";

// there is no need to modify the HTML below
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
                <h3 class="login-panel  text-center text-muted">It is now <?php echo $formattedCurrentTime; ?></h3>
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
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
