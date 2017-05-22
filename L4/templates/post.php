<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-info">
			<div class="panel-heading">
				<span>
					<?php echo $post['title']; ?>
				</span>
				<span class="pull-right text-muted">
					<?php echo $post['when']; ?>
				</span>
			</div>
			<div class="panel-body">
				<p class="text-muted">Posted on
					<?php echo $post['posted'] ?>
				</p>
				<p>
					<?php echo $post['comment']; ?>
				</p>
				<div class="img-box">
					<img class="img-thumbnail img-responsive" src="./uploads/<?php echo $post['img_src'] ?>"/>
				</div>
			</div>
			<div class="panel-footer">
				<p> By
					<?php echo $post['who']; ?>
				</p>
			</div>
		</div>
	</div>
</div>
