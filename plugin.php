<?php
   /*
   Plugin Name: Widget Pack
   Plugin URI: http://www.ideaboxthemes.com
   Description: A plugin to display author bio, author list, popular post, featured posts, recent posts and recent comments.
   Version: 1.0
   Author: Saumya Sharma,Purva Jain, Nidarshana Sharma, Nikita Pariyani, Shruti Taldar
   Author URI: http://ideaboxthemes.com
   License: GPL2 or later
   License URI: http://www.gnu.org/licenses/gpl-2.0.html
   */
?>
<?php
function widplg_enqueue_style(){
   wp_register_style( 'style', plugins_url( 'style.css', __FILE__ ) );
   wp_enqueue_style( 'style',plugins_url( 'style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'widplg_enqueue_style');
add_action('wp_head', 'wp_add_view');
function ab_get_author_list($noauth,$exc){
    echo "<ul>";
    wp_list_authors(array('number'=>$noauth,'exclude'=>$exc));
    echo "</ul>";
}
function catch_that_image() {
  		global $post, $posts;
  		$first_img = '';
                $matches[1][0] = ''; 
  		ob_start();
  		ob_end_clean();
  		if($output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches)) {
                    $first_img = $matches[1][0];
                }
  		if(empty($first_img)){ //Defines a default image
                    $first_img= "http://www.hdwallpapersfootball.com/wp-content/uploads/2014/06/Manchester-United-FC-Logo-HD-Wallpaper.jpg";                    
  		}
  		return $first_img;
}
function display_featured_image(){    
    global $post;
    $post_id=$post->ID;
    if ( has_post_thumbnail($post_id) ) {
        the_post_thumbnail('featured-thumb');
    }
    else {
       // echo '<img src="'.catch_that_image().'"alt="sorry could not load the image/>';
    }    
}
function display_post_author_name(){
    global $post;
    $author_id= $post->post_author;
    echo get_the_author_meta('first_name',$author_id);
    echo " ";
    echo get_the_author_meta('last_name',$author_id);
}
function display_author_description($post_id=0){
        $post = get_post( $post_id );
        $auth_id=$post->post_author;
        echo get_the_author_meta( 'description', $auth_id);
}
function wp_add_view(){
    if(is_single()){
        global $post;    
        $current_views=get_post_meta($post->ID, "wp_views", true);
        if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
            $current_views = 0;
        }
        $new_views = $current_views + 1;
        update_post_meta($post->ID, "wp_views", $new_views);
        return $new_views;
    }
}
function wp_get_view_count() {
    global $post;            
    $current_views = get_post_meta($post->ID, "wp_views", true);
    if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
        $current_views = 0;
    }
    return $current_views;
}
function show_views($singular = "view", $plural = "views", $before = "This post has: ") {
    global $post;
    $current_views = get_post_meta($post->ID, "wp_views", true);  
    $views_text = $before . $current_views . " ";
    if ($current_views == 1) {
        $views_text .= $singular;
    }
    else {
        $views_text .= $plural;
    }
    echo $views_text;
}
function custom_excerpt_length( $length ) {
	return 15;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
function new_excerpt_more($more) {
        global $post;
        return '<a class="moretag" href="'. get_permalink($post->ID) . '">   Read More..</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');
include 'feature_posts.php';
include 'popular-post-stat-widget.php';
include 'recent_posts.php';
include 'recents.php';
include 'author_list.php';
include 'author_bio_widget.php';
add_action('widgets_init',create_function('', 'return register_widget("Recent_Comments");'));
add_action('widgets_init',create_function('', 'return register_widget("Featured_Posts");'));
add_action('widgets_init',create_function('', 'return register_widget("Post_Stats_Counter");'));
add_action('widgets_init',create_function('', 'return register_widget("awp_recent_posts");'));
add_action('widgets_init',create_function('', 'return register_widget("Author_List");'));
add_action('widgets_init',create_function('', 'return register_widget("Author_Bio");'));