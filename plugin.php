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
function ab_get_author_list($noauth,$exc){
    echo "<ul>";
    wp_list_authors(array('number'=>$noauth,'exclude'=>$exc));
    echo "</ul>";
}
function catch_that_image() {
  		global $post;
  		$first_img = '';
  		ob_start();
  		ob_end_clean();
  		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  		$first_img = $matches [1] [0];

  		if(empty($first_img)){ //Defines a default image
  			$first_img = bloginfo('template_directory');
    		$first_img .= "http://picbook.in/wp-content/uploads/2014/07/puppy_images_in_hd.jpg";
  		}
  		return $first_img;
}
function display_featured_image(){    
    global $post;
    $post_id=$post->ID;
    if ( has_post_thumbnail($post_id) ) {
            the_post_thumbnail(array(100,100));
        }
        else {
            echo '<img src="';
            echo catch_that_image();
            echo '" alt="Unable to load" width="100px" height="100px" class="featuredImage" />';
        }    
}
function display_post_author_name(){
    global $post;
    echo "<strong>Author:  </strong>";
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
function show_views($singular = "view", $plural = "views", $before = "This post has: ") {
    global $post;
    
    echo"<div class='post-views'>";
        $current_views = get_post_meta($post->ID, "wp_views", true);  
        $views_text = $before . $current_views . " ";
        if ($current_views == 1) {
            $views_text .= $singular;
        }
        else {
            $views_text .= $plural;
        }
        echo $views_text;
    echo"</div>";
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
add_action('widgets_init',create_function('', 'return register_widget("wp_recent_posts");'));
add_action('widgets_init',create_function('', 'return register_widget("Author_List");'));
add_action('widgets_init',create_function('', 'return register_widget("Author_Bio");'));