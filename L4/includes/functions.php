<?php

include 'helpers.php';
//contains: namify(), format_date(), moments(), clean_input(), save_as_CSV(), trimpty(), not_mempty()
include 'display_posts.php';
//contains: get_post_data(), sort_posts_by(), display_posts()
include 'handle_form.php';
//contains: handle_uploads()

$posts = get_post_data("posts.txt");
if(isset($posts['error'])){
	$html_posts = "";
	foreach($posts['error'] as $code){
		$html_posts .=
		"<div class=\"alert alert-info text-center row col-md-6 col-md-offset-3\">
			$code
		</div>";
	}
} else {
	$sorted_posts = sort_posts_by('priority', $posts);
	$html_posts = display_posts($sorted_posts);
}
$formattedCurrentTime = format_date(time());

?>
