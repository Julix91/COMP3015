<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Post</h4>
        </div>
		<div class="modal-body">

				<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="make-new-post" id="new-post-form">
				<div class="panel panel-info text-center">FYI: All inputs are required!</div>
				<div class="form-group">
					<label for="first_name">First Name</label>
					<?php echo ( (!empty($first_nameErr)) ? '<div class="alert alert-warning">' . $first_nameErr . '</div>' : "") ;?>
					<input id="first_name" class="form-control" name="first_name" placeholder="First Name" value="<?php echo (isset($_POST['first_name']) ? $_POST['first_name'] : "") ?>" >
				</div>
				<div class="form-group">
					<label for="last_name">Last Name</label>
					<?php echo ((!empty($last_nameErr)) ? '<div class="alert alert-warning">' . $last_nameErr . '</div>' : "") ;?>
					<input id="last_name" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo $_POST['last_name'] ?>" >
				</div>
				<div class="form-group">
					<label>Title</label>
					<input class="form-control" placeholder="" name="title">
				</div>
				<div class="form-group">
					<label for="comment">Comment</label>
					<?php echo ((!empty($commentErr)) ? '<div class="alert alert-warning">' . $commentErr . '</div>' : "") ;?>
					<textarea id="comment" class="form-control" name="comment" rows="3" placeholder="Your message for the world" ><?php echo $_POST['comment'] ?></textarea>
				</div>
				<div class="form-group">
					<label for="priority">Priority</label>
					<?php echo ((!empty($priorityErr)) ? '<div class="alert alert-warning">' . $priorityErr . '</div>' : "") ;?>
					<select id="priority" class="form-control" name="priority" >
						<option value="" selected style="color: gray">Choose here</option>
						<option value="1" <?php echo  ($_POST['priority'] == 1) ? "selected" : ""?> >Crucial</option>
						<option value="2" <?php echo  ($_POST['priority'] == 2) ? "selected" : ""?> >Important</option>
						<option value="3" <?php echo  ($_POST['priority'] == 3) ? "selected" : ""?> >High</option>
						<option value="4" <?php echo  ($_POST['priority'] == 4) ? "selected" : ""?> >Normal</option>
						<option value="5" <?php echo  ($_POST['priority'] == 5) ? "selected" : ""?> >Un-high</option>
					</select>
				</div>
				<div class="form-group">
					<label>Image</label>
					<input type="file" name="img_src" value="<?php echo $_POST['img_src']; ?>" />
				</div>
				<input type="checkbox" name="started" value="started" checked hidden>
			</form>

        </div>
        <div class="modal-footer">
			<?php /* reset didn't work <input form="new-post-form" type="submit" value="Reset" class="btn btn-danger" name="Reset"  onclick="document.getElementById("new-post-form").reset(); " /> */?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input form="new-post-form" type="submit" value="Submit Post" class="btn btn-primary" />
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
