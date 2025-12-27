<?php
/**
 * Template Name: Log In
 *
 * Displays a "log in" page to your users.
 *
 * @package Suffusion
 * @subpackage Templates
 */

if (isset($_SERVER['REQUEST_METHOD']) && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'login' ) {
	if ( ! isset( $_POST['login_nonce'] ) || ! wp_verify_nonce( $_POST['login_nonce'], 'suffusion_login_action' ) ) {
		wp_die( esc_html__( 'Security check failed', 'suffusion' ) );
	}
	global $error;
	$login_data = array(
		'user_login'    => sanitize_user($_POST['user-name']),
		'user_password' => $_POST['password'], // wp_signon handles password safety
		'remember'      => isset($_POST['remember-me']) ? true : false,
	);
	$login = wp_signon($login_data, is_ssl());
}

get_header();
?>
<div id="main-col">
<?php
suffusion_page_navigation();
suffusion_before_begin_content();
?>
	<div id="content">
<?php
global $post;
if (have_posts()) {
	while (have_posts()) {
		the_post();
		$original_post = $post;
		do_action('suffusion_before_post', $post->ID, 'blog', 1);
?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<?php
suffusion_after_begin_post();
?>
			<div class="entry-container fix">
				<div class="entry fix">
<?php
suffusion_content();
if (is_user_logged_in()) {
	global $user_ID;
	$login_user = get_userdata($user_ID);
	printf(__('You are currently logged in as <a href="%1$s" title="%2$s">%2$s</a>.', 'suffusion'), esc_url(get_author_posts_url($login_user->ID)), esc_html($login_user->display_name));
?>
	<a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>" title="<?php esc_attr_e('Log out', 'suffusion'); ?>"><?php _e('Log out', 'suffusion'); ?></a>
<?php
}
else if (isset($login) && !is_wp_error($login)) {
	$login_user = get_userdata($login->ID);
	printf(__('You have successfully logged in as <a href="%1$s" title="%2$s">%2$s</a>.', 'suffusion'), esc_url(get_author_posts_url($login_user->ID)), esc_html($login_user->display_name));
}
else {
	if (is_wp_error($login)) {
		echo '<div class="error">' . $login->get_error_message() . '</div>';
	}
?>
	<form name="loginform" id="loginform" action="<?php echo esc_url(get_permalink()); ?>" method="post">
		<?php wp_nonce_field('suffusion_login_action', 'login_nonce'); ?>
		<p class="login-username">
			<label for="user-name"><?php _e('Username', 'suffusion'); ?></label>
			<input type="text" name="user-name" id="user-name" class="input" value="" size="20" />
		</p>
		<p class="login-password">
			<label for="password"><?php _e('Password', 'suffusion'); ?></label>
			<input type="password" name="password" id="password" class="input" value="" size="20" />
		</p>
		<p class="login-remember"><label><input name="remember-me" type="checkbox" id="remember-me" value="forever" /> <?php _e('Remember Me', 'suffusion'); ?></label></p>
		<p class="login-submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Log In', 'suffusion'); ?>" />
			<input type="hidden" name="action" value="login" />
		</p>
	</form>
<?php
}
?>
				</div><!--/entry -->
			</div><!-- .entry-container -->
		<?php
			suffusion_before_end_post();
			comments_template();
		?>

		</article><!--/post -->
		<?php
		do_action('suffusion_after_post', $post->ID, 'blog', 1);
	}
}
		?>
	</div>
</div>
<?php
get_footer();
?>