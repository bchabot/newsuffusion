<?php
/**
 * Invoked when no matches are found
 *
 * @package Suffusion
 * @subpackage Templates
 */

global $suffusion_404_title, $suffusion_404_content, $suf_404_title, $suf_404_content;

get_header();
?>
    <div id="main-col">
  	<div id="content">

    <article class="post">
	    <header class="post-header">
		    <h2 class="posttitle">
<?php
if (trim($suf_404_title) == '') {
	echo esc_html($suffusion_404_title);
}
else {
	$title = stripslashes($suf_404_title);
	$title = do_shortcode($title);
	echo wp_kses_post($title);
}
?>
		    </h2>
	    </header>

		<div class="entry">
		<p>
<?php
if (trim($suf_404_content) == '') {
	echo esc_html($suffusion_404_content);
}
else {
	$content = stripslashes($suf_404_content);
	$content = wp_specialchars_decode($content, ENT_QUOTES);
	$content = do_shortcode($content);
	echo wp_kses_post($content);
}
?>
		</p>
			<?php get_search_form(); ?>
		</div><!--/entry -->

		</article><!--/post -->
      </div><!-- /content -->
    </div><!-- main col -->
<?php get_footer(); ?>
