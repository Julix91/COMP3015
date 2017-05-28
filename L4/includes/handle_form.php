<?php

/*****************************UPLOAD************************************/
	//This section handles image submissions and related errors	//

$_FILES['userfile']['upload_message']="";
function handle_uploads($success=NULL)
//inspired by https://www.w3schools.com/php/php_file_upload.asp
{
	$success=is_null($success) ? false : $success;
	if(isset($_FILES) && count($_FILES)>0 ){
		$orgname = $_FILES['userfile']['name'];//original
		$upload_dir = './uploads/';
		$upload_file = $upload_dir . basename($orgname);
		$upload_status = 1;
		$upload_file_type = pathinfo($upload_file,PATHINFO_EXTENSION);

		// Check if image file is an actual image or not
		if(isset($_POST["submit"]) && !empty($_FILES["userfile"]["tmp_name"])) {
			$check = getimagesize($_FILES["userfile"]["tmp_name"]);
			if($check !== false) {
				$_FILES['userfile']['upload_message'] .=  "File is an image - " . $check["mime"] . ". ";
				$upload_status = 1;
			} else {
				$_FILES['userfile']['upload_message'] .=  "File is not an image. ";
				$upload_status = 0;
			}
		}

		// Check if file already exists
		if (file_exists($upload_file)) {
			$_FILES['userfile']['upload_message'] .=  "Sorry, file already exists.";
			$upload_status = 0;
		}
		// Check file size
		$max_file_size = pow(10, 7);
		$formatted_max_file_size = formatBytes($max_file_size);//from helpers.php
		if ($_FILES["userfile"]["size"] > $max_file_size) {
			$_FILES['userfile']['upload_message'] .=  "Sorry, your file size ( {$_FILES["userfile"]["size"]} ) is too large. Maximum size is ";
			$upload_status = 0;
		}
		// Allow certain file formats
		if($upload_file_type != "jpg" && $upload_file_type != "png" && $upload_file_type != "jpeg"
		&& $upload_file_type != "gif" ) {
			$_FILES['userfile']['upload_message'] .=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$upload_status = 0;
		}
		// Check if $upload_status is set to 0 by an error
		if ($upload_status == 0) {
			$_FILES['userfile']['upload_message'] .=  "Sorry, your file was not uploaded.";
			return false;
		// if everything is ok, try to upload file else bail
		} else if($success===true) {
			if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $upload_file)) {
				$_FILES['userfile']['upload_message'] =  "The file ". basename( $_FILES["userfile"]["name"]). " has been uploaded.";
				return true;
			} else {
				$_FILES['userfile']['upload_message'] .=  "Sorry, there was a mysterious error uploading your file.";
				return false;
			}
		}
	}
}


/*****************************FORM************************************/
	//This section handles form submissions and saving them	//


//Setting a bunch of variables to avoid undefined notices
$set_fields = ["started", "first_name", "last_name", "title", "img_src", "priority", "comment"];
foreach ($set_fields as $value) {
	if(!(isset($_POST[$value]))){
		$_POST[$value]="";
	}
}

//On submit mark form as started, clean up inputs and namify names
if ($_SERVER["REQUEST_METHOD"] === "POST" && count($_POST) > 0 && $_POST['started'] === "started") {




	foreach ($_POST as $key=>$val){
		if(trimpty($val)){
			if($key === "priority"){
				${$key . "Err"} = "You've gotta tell me how <em>important</em> it is to you!";
			} else if ($key === "comment"){
				${$key . "Err"} = "Seriously, if you've got nothing to say, why bother at all?";
			} else {
				${$key . "Err"} = "Your <em>" . namify($key) . "</em> is required!";
			}
		} else {
			$_POST[$key] = clean_input($val);
		}
	}


	//check for successfulness of submission
	$message = "";
	$test_success = function($required_fields=NULL)
	{
		if (!isset($required_fields)) {
			$required_fields = ["first_name", "last_name", "title", "priority", "comment"];
		}
		$fail = NULL;
		foreach ($required_fields as $required_field_value ) {
			if(!empty($_POST[$required_field_value])){
				continue;
			} else {
				$fail = true;
				break;
			}
		}
		if (isset($fail)) {
			return false;
		} else {
			return true;
		}
	};

	$success="";
	$partial_success = $test_success();
	if($partial_success){
		if(handle_uploads($partial_success)){
			$_POST["received"] = time();
			$_POST["img_src"] = basename($_FILES['userfile']['name']);

			$_POST['posted'] = format_date($_POST['received']);
			$_POST['when'] = moments($_POST['received']);
			$_POST['who'] = namify($_POST['first_name']) . " " . namify($_POST['last_name']);

			//Save only the things required to be saved
			$saveme = [ $_POST['first_name'], $_POST['last_name'], $_POST['title'], $_POST['comment'], $_POST['priority'], $_POST['img_src'], $_POST['received'] ];
			save_as_CSV($saveme, 'posts.txt');
			$success=true;
			unset($_FILES);
			//would like to unset _POST as well, but used it to store stuff...
		}

	}
}
