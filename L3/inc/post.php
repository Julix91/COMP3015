<?php /*template for single posts*/ ?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-info">
			<div class="panel-heading">
				<span>
					First Post!
				</span>
				<span class="pull-right text-muted">
					<?php echo moments($post["received"]); ?>
				</span>
			</div>
			<div class="panel-body">
				<p class="text-muted">Posted on
					<?php echo format_date($post["received"]); ?>
				</p>
				<p>
					<?php echo $post["comment"]; ?>
				</p>
			</div>
			<div class="panel-footer">
				<p> By
					<?php echo $post["first_name"] . $post["last_name"]; ?>
				</p>
			</div>
		</div>
	</div>
</div>
