<?php

function moments($seconds)
{
    if($seconds < 60 * 60 * 24 * 30)
    {
        return "within the month";
    }

    return "a while ago";
}


// do not modify these variables
$postedTime = 1481808630;
$author = "   gary TonG  ";
$content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.";

// modify these variables
$formattedAuthor        =  trim(ucwords(strtolower($author)));
$formattedCurrentTime   = date('l F \t\h\e dS, Y', time());
$formattedPostedTime    = date('l F \t\h\e dS, Y', $postedTime);
$moment                 = moments(time() - $postedTime);

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
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost">New Post</button>
                <hr/>

            </div>
        </div>

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

            <form role="form">
                <div class="form-group">
                    <input class="form-control" placeholder="First Name">
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Last Name">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <select class="form-control">
                        <option value="1">Important</option>
                        <option value="2">High</option>
                        <option value="3">Normal</option>
                    </select>
                </div>
            </form>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Submit Post</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
