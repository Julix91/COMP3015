<?php

function moments($s){
	if($s <= 30*24*60*60){
		return "within the month";
	} else {
		return "a while ago";
	}
}

// do not modify these variables
$postedTime = 1481808630;
$author = "   gary TonG  ";
$content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.";

// modify these variables
$formattedAuthor        = ucwords(strtolower(trim($author))); //no unnecessary white space, no uppercase mid word, all first letters uppercase
$now = time();
$passed = $now - $postedTime;
$formattedCurrentTime   = date("l F \\t\h\\e dS\, Y", $now);
$formattedPostedTime    = date("l F \\t\h\\e dS\, Y", $postedTime);
$moment = moments($passed);


//second post (modified HTML only to make this one work)
$postedTime2 = 1493860847;
$author2 = "H. Julian Sloman";
$content2 = 
"<p style=\"color: red\">This can safely be ignored, optional content!</p>
<p>I made the ternary solution above before realizing I was supposed to write a function, so I commented it out and added the function (top).</p>
<p>I also was curious about other ways of displaying the distance between dates and found <a href='http://stackoverflow.com/questions/676824/how-to-calculate-the-difference-between-two-dates-using-php'>a stackoverflow question</a></p>
<p>Of course now I'd like it to not show 'years,' if it's 0 and after that not in plural if it's 1 and after that I'd like to get the 'and' to the right spot...</p>
<p>I faced a similar problem in JavaScript before: <a href='http://stackoverflow.com/questions/38983702/how-to-add-commas-and-and-at-the-right-place'>my stackoverflow question</a></p>
<p>But that was then. So I went ahead and made a second post (this one) and tried to get a bit closer. But of course since it's very recent all I was getting initially was \"or to be precise \" - because even days were 0...</p>";

$passed2 = $now - $postedTime2;
$duration_ish = ($passed2 > 30*24*60*60 /*month worth of seconds*/) ? "a while ago" : "within the month";
$old =  new DateTime(date('Y-m-d', $postedTime2));
$new =  new DateTime(date('Y-m-d', $now));
$interval = $old->diff($new);

$moment2 = ($passed2 >= 2000) ? ($duration_ish /*terenary version of moments() - I get that since I already made the propre function above, I might as well use it instead, but eh*/ . 
	" or to be precise " .
	//years
		(($interval->y > 0) ? ($interval->y . " years, ") : "") .
 	//months
		(($interval->m > 0) ? ($interval->m . " months, ") : "") .
	//there should be a nice "and" here, with some logic
	//days
		(($interval->d > 0) ? ($interval->d . " days") : "") .
	".") : /*else*/ "just moments ago";
	

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
	<?php
	
	 ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h3 class="login-panel  text-center text-muted">It is now <?php echo $formattedCurrentTime; ?></h3>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>
                            Second Post!
                        </span>
                        <span class="pull-right text-muted">
                            <?php echo $moment2; ?>
                        </span>
                    </div>
                    <div class="panel-body">
                        <p class="text-muted">Posted on
                            <?php echo $formattedPostedTime; ?>
                        </p>
                        <p>
                            <?php echo $content2; ?>
                        </p>
                    </div>
                    <div class="panel-footer">
                        <p> By
                            <?php echo $author2; ?>
                        </p>
                    </div>
                </div>
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
