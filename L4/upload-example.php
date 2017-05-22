<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<form class="" action="upload-example.php" method="POST" enctype="multipart/form-data">
			<!--(basic C4-encode = sending text over the internet)-->
			<input type="file" name="userfile" />
			<input type="text" name="username" />
			<input type="submit" value="Upload!" />
		</form>
		<?php (isset($_FILES) && count($_FILES) >0) ? var_dump($_FILES) : ""; ?>
		<?php var_dump($_FILES['userfile']['type']) ?>
	</body>
</html>
