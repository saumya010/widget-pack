<?php
class Post_Stats_Counter extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'widget_class', 'description' => __('Insert the plugin description here'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Popular Posts'), $widget_ops, $control_ops );
?>
<?php
    }
    function form($instance) { 
        $defaults = array( 'title' => __('Popular Posts'), 'post_count' => __('5'),'sort_radio'=>'0','featured-image'=>'0','post-date'=>'0','post-author'=>'0','post-category'=>'0','comments'=>'0','views'=>'0','post-excerpt'=>'0');
	$instance = wp_parse_args( (array) $instance, $defaults ); 
	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$post_count=$instance['post_count'];
	}
	else {
			$title =$defaults['title'];
			$post_count=$defaults['post_count'];
	}?>
	<p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
        <p>
		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Number of Posts:', 'wp_widget_plugin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" value="<?php echo $post_count; ?>" type="number" >
	</p>
        <p>
                   <input type="radio" id="<?php echo $this->get_field_id('sort_radio'); ?>" 
                   name="<?php echo $this->get_field_name('sort_radio'); ?>"
                   <?php if (isset($instance['sort_radio']) && $instance['sort_radio']=="views") echo "checked";?>
                   value="views">Sort by Views <br>        
                   <input type="radio" id="<?php echo $this->get_field_id('sort_radio'); ?>
                   " name="<?php echo $this->get_field_name('sort_radio'); ?>"
                   <?php if (isset($instance['sort_radio']) && $instance['sort_radio']=="comments")echo "checked";?>
                   value="comments">Sort by Comments
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['featured-image'], 'on'); ?> id="<?php echo $this->get_field_id('featured-image'); ?>" name="<?php echo $this->get_field_name('featured-image'); ?>" /> 
                <label for="<?php echo $this->get_field_id('featured-image'); ?>">Display Featured Image</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['post-date'], 'on'); ?> id="<?php echo $this->get_field_id('post-date'); ?>" name="<?php echo $this->get_field_name('post-date'); ?>" /> 
                <label for="<?php echo $this->get_field_id('post-date'); ?>">Display Post Date</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['post-author'], 'on'); ?> id="<?php echo $this->get_field_id('post-author'); ?>" name="<?php echo $this->get_field_name('post-author'); ?>" /> 
                <label for="<?php echo $this->get_field_id('post-author'); ?>">Display Name of the Author</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['post-category'], 'on'); ?> id="<?php echo $this->get_field_id('post-category'); ?>" name="<?php echo $this->get_field_name('post-category'); ?>" /> 
                <label for="<?php echo $this->get_field_id('post-category'); ?>">Display Post Category</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['comments'], 'on'); ?> id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" /> 
                <label for="<?php echo $this->get_field_id('comments'); ?>">Display Number of Comments</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['views'], 'on'); ?> id="<?php echo $this->get_field_id('views'); ?>" name="<?php echo $this->get_field_name('views'); ?>" /> 
                <label for="<?php echo $this->get_field_id('views'); ?>">Display Number of  Views</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['post-excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('post-excerpt'); ?>" name="<?php echo $this->get_field_name('post-excerpt'); ?>" /> 
                <label for="<?php echo $this->get_field_id('post-excerpt'); ?>">Display Post Excerpt</label>
        </p>                                
<?php }
    function update($new_instance,$old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['post_count'] = strip_tags( $new_instance['post_count'] );
        $instance['sort_radio'] = strip_tags( $new_instance['sort_radio'] );
        $instance['featured-image'] = strip_tags( $new_instance['featured-image']);
        $instance['post-date'] = strip_tags( $new_instance['post-date']);
        $instance['post-category'] = strip_tags( $new_instance['post-category']);
        $instance['comments'] = strip_tags( $new_instance['comments']);
        $instance['views'] = strip_tags( $new_instance['views']);
        $instance['post-excerpt'] = strip_tags( $new_instance['post-excerpt']);
        $instance['post-author'] = strip_tags( $new_instance['post-author']);
        return $instance;
    }
    function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $post_count= apply_filters('widget_title', $instance['post_count']);
        global $author_id;
        // Display the widget title
        echo "<div class='auth-widget widget'>";
            if ( $title ){
                echo "<h3 class='widget-title'>".$title."</h3>";}
            if($instance['sort_radio']=="comments") {  
                $args = array(
                    'author'=>$author_id,
                    "posts_per_page" => $post_count,
                    "post_type" => "post",
                    "post_status" => "publish",
                    "orderby" => "comment_count",
                    "order" => "DESC"
                );
            $asc_list = new WP_Query($args);
            if($asc_list->have_posts()) { echo "<ul class='list'>"; }
                while ( $asc_list->have_posts() ) : $asc_list->the_post();                    
                    echo '<li><h4 class="post-title"><a href="'.get_permalink().'">'.the_title('', '', false)."</h4>";
                            if($instance['featured-image']){
                                echo"<div class='featured-image'>";
                                    if (has_post_thumbnail()){
                                        the_post_thumbnail('featured-thumb');
                                    }
                                    else{
                                        echo '<img src="';
                                        echo catch_that_image();
                                        echo '"alt="Image Not Found"';
                                        echo the_title();
                                        echo '/>';
                                    } 
                                echo"</div>";
                            }
                    echo '</a>';
                    if ($instance['post-date']){
                        echo"<div class='post-date'>";
                            echo"Posted On:";
                            echo get_the_date();
                        echo"</div>";
                    }          
                    if(function_exists("display_post_author_name")){
                        echo "<div class = 'post-by'>";
                            if($instance['post-author']){                        
                                display_post_author_name();
                                    echo"<div class='author-gravatar'>".'<a href=' .get_author_posts_url(get_the_author_meta('ID')).'">'.get_avatar(get_the_author_meta('ID'),65).'</a>'."</div>";
                            }
                        echo "</div>";
                    }         
                    if ($instance['post-category']){
                        echo"<div class='post-category'>";
                            echo "<h4>Post Category: </h4>";
                            echo get_the_category_list();
                        echo"</div>";
                    }
                    if ($instance['comments']){
                        echo"<div class='count'>";
                            echo"This post has:";
                            comments_number();
                        echo"</div>";
                    }
                    if ($instance['views']){
                        echo"<div class='count'>";                 
                            show_views();
                        echo"</div>";
                    }
                    if ($instance['post-excerpt']){
                        echo"<div class='post-excerpt'>";
                            echo"<h4> Post Excerpt </h4>";
                            echo "<p>";
                            the_excerpt();
                            echo"</p>";                  
                        echo"</div>";
                    }
                echo"</li>";
            endwhile;
         }
         else {
            $args = array(
                'author'=>$author_id,
                "posts_per_page" => $post_count,
                "post_type" => "post",
                "meta_key"=>"asc_views",
                "post_status" => "publish",
                "order_by"=>"meta_value_num",
                "order" => "DESC"
            );
            $asc_list = new WP_Query($args);
            if($asc_list->have_posts()) { echo "<ul class='list'>"; }
                while ( $asc_list->have_posts() ) : $asc_list->the_post();                             
                    echo '<li><h4 class="post-title"><a href="'.get_permalink().'">'.the_title('', '', false)."</h4>";
                        if($instance['featured-image']){
                            echo"<div class='featured-image'>";
                                if (has_post_thumbnail()){
                                    the_post_thumbnail('featured-thumb');
                                }
                                else{
                                    echo '<img src="';
                                    echo catch_that_image();
                                    echo '"alt="Image Not Found"';
                                    echo the_title();
                                    echo '/>';
                                }
                            echo "</div>";
                        }
                        echo '</a>';
                        if ($instance['post-date']){
                            echo"<div class='post-date'>";
                                echo"<p>Posted On:";
                                echo get_the_date();
                                echo"</p></div>";
                        }            
                        if(function_exists("display_post_author_name")){
                            echo "<div class = 'post-by'><p>";
                                if($instance['post-author']){
                                    display_post_author_name();
                                    echo"<div class='author-gravatar'>".'<a href=' .get_author_posts_url(get_the_author_meta('ID')).'">'.get_avatar(get_the_author_meta('ID'),65).'</a>'."</div>";
                                }
                            echo "</p></div>";
                        }            
                        if ($instance['post-category']){
                            echo"<div class='post-category'>";
                                echo "<h4>Post Category: </h4>";
                                echo get_the_category_list();
                            echo"</div>";
                        }
                        if ($instance['comments']){
                            echo"<div class='count'>";
                                echo"This post has:";
                                comments_number();
                                echo"</div>";
                        }
                        if ($instance['views']){
                            echo"<div class='count'>";
                                show_views();
                            echo"</div>";
                        }
                        if ($instance['post-excerpt']){
                            echo"<div class='post-excerpt line-space'>";
                                echo"<h4> Post Excerpt </h4>";
                                echo "<p>";
                                the_excerpt();
                                echo"</p>";                  
                            echo"</div>";
                        }
                    echo"</li>";
                endwhile;
           echo "</ul>";
         }        
         echo"</div>";
    }
}