<?php if($success===true){?>
<p class="alert alert-success alert-dismissable text-center">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	Thank you <?php echo $_POST['who']; ?> for posting! Received <?=$_POST['posted']?> </p>
<?php } else {?>
<p class="alert alert-warning alert-dismissable text-center">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	The post has not yet been successfully submitted. All inputs are required! Click on "Edit Post" to keep editing.
</p>
<?php } ?>
