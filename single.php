<?php
/**
 * The Template for displaying all single posts.
 *
 * Cannepp Theme
 *
 */

if ( have_posts() ) { the_post(); }
register_quicksand_js();
register_slider_js();
get_header();
?>
<div class="content-wrapper">

<div class="content-container news-page">
<div class="page-line">
            <div class="arrow-nav left"><?php previous_post_link('%link', '&#60; Previous Post');?></div>
            <div class="arrow-nav right"><?php  next_post_link('%link', 'Next Post &#62;'); ?></div>
        </div>
<article class="">


<?php $cont = get_the_content();
$meta = get_post_meta($post->ID, '_meta_post_layout', true);

//Full width
if( $meta !== 'half_width_meta' && ($meta === 'full_width_meta' || strlen($cont) > 800 ) || preg_match('/(<img.*>)/', $cont) ){
  //if post not half width and is full width or longer than 800 characters or contains an image in the content area ?>
  <h1 class="red-down-mid"><?php echo get_the_title(); ?> </h1>
  <div class="news-noscroll"><?php echo get_the_post_thumbnail(); echo the_content();?></div>
<?php } /*closes if >800 chars in post*/ else {

//Two halves
?>
<div class="content left-side">

<?php echo get_the_post_thumbnail(); ?>

</div>

<div class="right-side">
    <h1 class="red-down-mid"><?php echo get_the_title(); ?> </h1>
    <div class="news-scroll"><?php echo the_content();?></div>
</div>
<?php } //closes else <= 800 chars ?>
</article>
   <div class="clear"></div>
   <?php echo get_blog(60,100,''); ?>
</div>

   <?php get_footer();?>
