<?php //Opening PHP tag


include 'functions-atib-medlem.php';
include 'functions-atib-slakt_handelser.php';

// Set Favicon link in header.
function favicon_link() {
    echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />' . "\n";
}
add_action( 'wp_head', 'favicon_link' );

// Disable pingbacks
// http://wptavern.com/how-to-prevent-wordpress-from-participating-in-pingback-denial-of-service-attacks
add_filter( 'xmlrpc_methods', 'remove_xmlrpc_pingback_ping' );
function remove_xmlrpc_pingback_ping( $methods ) {
   unset( $methods['pingback.ping'] );
   return $methods;
} 

/**
 * Set up the content width value based on the theme's design.
 * Set $content_width same as in "max content max width" in plugin Fourteen Extended?
 * This will enable creation of larger thumbnails in "Medial upload" process.
 *
 * @see twentyfourteen_content_width()
 *
 */
$content_width = 650;

// Change the default wordpress@ email address
// http://www.daretothink.co.uk/blog/change-default-wordpress-email-address/
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
 
function new_mail_from($old) {
return 'kontakt@annikaochtorkeliberg.se';
}
function new_mail_from_name($old) {
return 'Släktföreningen Annika och Torkel i Berg';
}

// Add shortcodes
// function atib_list_categories() {
// $args = array(
  // 'orderby' => 'id',
  // 'order' => 'ASC',
  // 'hide_empty' => 0
  // );
// $categories = get_categories($args);
  // foreach($categories as $category) { 
    // $out .=  '<div style="float: left; width:100%;">' . "\n";
    // $out .=  '  <div style="float: left; width: 35%;"><p><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> </p></div> ' . "\n";
    // $out .=  '  <div style="float: left; width: 45%;"><p>' . $category->description . '</p></div>' . "\n";
    // $out .=  '  <div style="float: left; width: 10%; height: 100%; text-align: right;"><p>('. $category->count . ')</p></div>' . "\n";  
	// $out .=  '</div>' . "\n";
	// } 
// return $out;
// }
// add_shortcode('atiblistcategories', 'atib_list_categories');


// function atib_list_categories_simple() {
  // $list = wp_list_categories("echo=0&orderby=name&show_count=1&hide_empty=0&title_li=");
  // return $list;
// }
// add_shortcode('atiblistcategoriessimple', 'atib_list_categories_simple');

// function atib_list_tags_simple() {
  // $list = wp_tag_cloud('echo=0&number=0&format=flat&order=ASC&orderby=name'); 
  // return $list;
// }
// add_shortcode('atiblisttagssimple', 'atib_list_tags_simple');

// function atib_list_monthly_archive() {
  // $list = wp_get_archives('echo=0&type=monthly&format=html');
  // return $list;
// }
// add_shortcode('atiblistmonthlyarchive', 'atib_list_monthly_archive');

// Shortcode to display login form for non-logged in users
// Behöver  egentligen kompletteras med "Glömt lösenord, registrera konto". wp_loginout verkar lite bättre då.
// https://pippinsplugins.com/wordpress-login-form-short-code/
// Om använd, lägg ev till nedan css för att få "Username" och ruta på olika rader. 
// Lite halvful styling, borde går göra på något snyggare sättt. 
/* Username wrapper paragraph. */
// .login-username {	
  // width: 40%;
// }
// /* Password wrapper paragraph. */
// .login-password {
  // width: 40%;
// }
function atib_login_form_shortcode( $atts, $content = null ) {
 
	extract( shortcode_atts( array(
      'redirect' => ''
      ), $atts ) );
 
	if (!is_user_logged_in()) {
		if($redirect) {
			$redirect_url = $redirect;
		} else {
			$redirect_url = get_permalink();
		}
		$form = wp_login_form(array('echo' => false, 'redirect' => $redirect_url, 'value_remember' => true ));
	} 
	return $form;
}
add_shortcode('atib_loginform', 'atib_login_form_shortcode');

// Shortcode to display a login or a logout link
// http://justintadlock.com/archives/2011/08/30/adding-a-login-form-to-a-page
function atib_loginlink_shortcode( ) {
	$link = wp_loginout($_SERVER['REQUEST_URI'], false );
	return $link;
}
add_shortcode('atib_loginlink', 'atib_loginlink_shortcode');



// Change style of login page
// From http://andornagy.com/customizing-the-wordpress-login-page/
// Adding the function to the login page
add_action('login_head', 'custom_login');

// Our custom function that includes the custom stylesheet
function custom_login() {
echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo( 'stylesheet_directory' ) . '/custom-login.css" />';
}
// Change the Login Logo URL
// From http://premium.wpmudev.org/blog/create-a-custom-wordpress-login-page/
function my_login_logo_url() {
return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
return 'Släktföreningen Annika och Torkel i Berg';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

// Set "Remember Me" To Checked in login form
function login_checked_remember_me() {
add_filter( 'login_footer', 'rememberme_checked' );
}
add_action( 'init', 'login_checked_remember_me' );

function rememberme_checked() {
echo "<script>document.getElementById('rememberme').checked = true;</script>";
}

// Remove The URL Field From WordPress Comments
// http://premium.wpmudev.org/blog/remove-wordpress-comments-url/
add_filter('comment_form_default_fields', 'unset_url_field');
function unset_url_field($fields){
if(isset($fields['url']))
unset($fields['url']);
return $fields;
}

function featuredtoRSS($content) {
global $post;
if ( has_post_thumbnail( $post->ID ) ){
	//$content = '' . get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'style' => 'float:left; margin:0 15px 15px 0;' ) ) . '' . $content;
	$content = '<p>' . get_the_post_thumbnail( $post->ID, 'medium' ) . '</p>' . $content;
	//$content = $content . get_the_post_thumbnail( $post->ID, 'medium', array( 'style' => 'display:inline; float:left; margin:0 15px 15px 0;' ) );
}
return $content;
}
 
add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

// Redesign the Meta widget
// From http://wordpress.org/support/topic/edit-meta-widget-in-theme-file
function remove_meta_widget() {
    unregister_widget('WP_Widget_Meta');
    register_widget('WP_Widget_Meta_Mod');
}
add_action( 'widgets_init', 'remove_meta_widget' );

/**
 * Meta widget class
 *
 * Displays log in/out, RSS feed links, etc.
 *
 * @since 2.8.0
 */
class WP_Widget_Meta_Mod extends WP_Widget {

function __construct() {
$widget_ops = array('classname' => 'widget_meta', 'description' => __( "Log in/out, admin, feed and WordPress links") );
parent::__construct('meta', __('Meta'), $widget_ops);
}

function widget( $args, $instance ) {
extract($args);
$title = apply_filters('widget_title', empty($instance['title']) ? __('Administration') : $instance['title'], $instance, $this->id_base);

echo $before_widget;
if ( $title )
echo $before_title . $title . $after_title;
?>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<?php wp_meta(); ?>
</ul>
<?php
echo $after_widget;
}

function update( $new_instance, $old_instance ) {
$instance = $old_instance;
$instance['title'] = strip_tags($new_instance['title']);

return $instance;
}

function form( $instance ) {
$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
$title = strip_tags($instance['title']);
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
}
}
