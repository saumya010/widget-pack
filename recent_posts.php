<?php
class wp_recent_posts extends WP_Widget {
    function __construct() {
        parent::__construct(
            // Base ID of your widget
            'wp_recent_posts', 
            // Widget name will appear in UI
            __('Recent Posts', 'wp_widget_plugin'), 
            // Widget description
            array( 'description' => __( 'Widget to display recent posts', 'wp_widget_plugin' ),'classname'=> 'recent_posts_widget' ) 
        );
    }
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $post_count = $instance['post_count'];
        // before and after widget arguments are defined by themes
        
        echo '<div id="recent-posts" class="widget">';
        if ( ! empty( $title ) )
            echo "<h3 class='widget-title'>" . $title  . "</h3>";
        
        $args = new WP_Query(
            array(
                "posts_per_page" => $post_count,
                "post_type" => "post",
                "post_status" => "publish",
                "order" => "DESC"
            )
        );
        global $post;                   
        if($args->have_posts()) { echo '<ul class="list">'; }
        while ( $args->have_posts() ) : $args->the_post();
            echo'<li class="widget-list-item">';
                echo '<h4 class="post-title"><a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a></h4>';
                if($instance['show_image']){
                    echo '<div class="featured-image">';
                        display_featured_image();
                    echo '</div>';
                }
                echo "<div class='details'>";
                    if($instance['show_date']){
                        echo "<p><strong>Date:</strong>";
                        echo the_date('','','',true);
                        echo "</p>";
                    }
                    if($instance['show_author']){
                        echo "<p>";
                        display_post_author_name();
                        echo "</p>";
                    }
                    if($instance['show_category']){
                        echo "<p><strong>Category:</strong>";
                        get_the_category_list();
                        echo "</p>";
                    }
                    if($instance['show_comment_number']){
                        echo "<p>";
                        comments_number();
                        echo "</p>";
                    }
                    if($instance['show_excerpt']){
                        echo "<p><strong>Excerpt:</strong>";
                        the_excerpt();
                        echo "</p>";
                    }
                echo "</div>";
            echo "</li>";
        endwhile;
        echo "</ul>";        
        echo '</div>';
    }
		
    public function form( $instance ) {
        $defaults = array( 'title' => __('Recent Posts'), 'post_count' => __('5'), 'show_image' => __('0')
            , 'show_date' => __('0'), 'show_author' => __('0'), 'show_category' => __('0')
            , 'show_comment_number' => __('0'), 'show_excerpt' => __('0'), 'read_more' => __('Read More..'));
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
            <input class="checkbox" type="checkbox" <?php checked($instance['show_image'], 'on'); ?> id="<?php echo $this->get_field_id('show_image'); ?>" name="<?php echo $this->get_field_name('show_image'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_image'); ?>">Display Featured Image</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_date'], 'on'); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_date'); ?>">Display Post Date</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_author'], 'on'); ?> id="<?php echo $this->get_field_id('show_author'); ?>" name="<?php echo $this->get_field_name('show_author'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_author'); ?>">Display Post Author</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_category'], 'on'); ?> id="<?php echo $this->get_field_id('show_category'); ?>" name="<?php echo $this->get_field_name('show_category'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_category'); ?>">Display Post Category</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_comment_number'], 'on'); ?> id="<?php echo $this->get_field_id('show_comment_number'); ?>" name="<?php echo $this->get_field_name('show_comment_number'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_comment_number'); ?>">Display Number of Comments</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Display Post Excerpt with Read More link</label>
        </p>
        
	<p>
            <label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Number of Posts', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="number" value="<?php echo $post_count;?>">
	</p>        
    <?php
    }
	
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['post_count'] = strip_tags( $new_instance['post_count'] );
        $instance['show_image'] = $new_instance['show_image'];
        $instance['show_date'] = $new_instance['show_date'];
        $instance['show_author'] = $new_instance['show_author'];
        $instance['show_category'] = $new_instance['show_category'];
        $instance['show_comment_number'] = $new_instance['show_comment_number'];
        $instance['show_excerpt'] = $new_instance['show_excerpt'];
        return $instance;
    }
} 

// Register and load the widget
function wp_load_widget() {
	register_widget( 'wp_recent_posts' );
}
add_action( 'widgets_init', 'wp_load_widget' );
?>