<?php
class Featured_Posts extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'post_plugin', 'description' => __('Widget to display post details', 'wp_post_plugin'));
	$control_ops = array('width' => 200, 'height' => 250);
	parent::WP_Widget(false, $name = __('Featured Posts', 'wp_post_plugin'), $widget_ops, $control_ops );
    }
    public function form($instance) { 
        $defaults = array(
            'title' => __('Featured Posts'), 
            'post_count' => '5' ,
            'check_image'=> '0',
            'check_category'=> '0',            
            'check_author'=> '0',            
            'check_comments'=> '0',            
            'check_excerpt'=> '0',  
            'check_views'=> '0',            
            'check_date'=> '0', 
            'posts_category'=> ''
        );
	$instance = wp_parse_args( (array) $instance, $defaults );
	if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
            $post_count=$instance['post_count'];
	}
	else {
            $title =$defaults['title'];
            $post_count=$defaults['post_count'];
	}
        
        $category = $instance['posts_category'];
        ?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_post_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
        <p>
		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('No of posts to display', 'wp_post_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="number" value="<?php echo $post_count; ?>" />
	</p>
        <p> 
                <input class="img_check" type="checkbox" <?php checked($instance['check_image'], 'on'); ?> id="<?php echo $this->get_field_id('check_image'); ?>" name="<?php echo $this->get_field_name('check_image'); ?>" /> 
                <label for="<?php echo $this->get_field_id('check_image'); ?>">Display post thumbnail</label>
        </p>
     
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['check_date'], 'on'); ?> id="<?php echo $this->get_field_id('check_date'); ?>" name="<?php echo $this->get_field_name('check_date'); ?>" /> 
                <label for="<?php echo $this->get_field_id('check_date'); ?>">Display Post Date</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['check_author'], 'on'); ?> id="<?php echo $this->get_field_id('check_author'); ?>" name="<?php echo $this->get_field_name('check_author'); ?>" /> 
                <label for="<?php echo $this->get_field_id('check_author'); ?>">Display Post Author</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['check_category'], 'on'); ?> id="<?php echo $this->get_field_id('check_category'); ?>" name="<?php echo $this->get_field_name('check_category'); ?>" /> 
                <label for="<?php echo $this->get_field_id('check_category'); ?>">Display Post Category</label>
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['check_comments'], 'on'); ?> id="<?php echo $this->get_field_id('check_comments'); ?>" name="<?php echo $this->get_field_name('check_comments'); ?>" /> 
                <label for="<?php echo $this->get_field_id('check_comments'); ?>">Display Number of Comments</label>
        </p>         
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['check_excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('check_excerpt'); ?>" name="<?php echo $this->get_field_name('check_excerpt'); ?>" /> 
                <label for="<?php echo $this->get_field_id('check_excerpt'); ?>">Display Post Excerpt with Read More link</label>
       </p>        
       <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['check_views'], 'on'); ?> id="<?php echo $this->get_field_id('check_views'); ?>" name="<?php echo $this->get_field_name('check_views'); ?>" /> 
                <label for="<?php echo $this->get_field_id('check_views'); ?>">Display number of views</label>
       </p>          
       
       <p>
           <label for="<?php echo $this->get_field_id( 'posts_category' ); ?>"><?php _e( 'Category' ); ?>:</label>
					<?php
					$categories_args = array(
						'name'            => $this->get_field_name( 'posts_category' ),
						'selected'        => $category,
						'orderby'         => 'Name',
						'hierarchical'    => 1,
						'show_option_all' => __( 'All Categories', 'genesis' ),
						'hide_empty'      => '0',
					);
					wp_dropdown_categories( $categories_args ); ?>
        </p>
<?php }
    public function update($new_instance,$old_instance){
        $instance = $old_instance;
        $instance['posts_category'] = $new_instance['posts_category'];
        $instance['title'] = strip_tags( $new_instance['title'] );  
        $instance['post_count'] = strip_tags( $new_instance['post_count'] );
        $instance['check_image'] =strip_tags($new_instance['check_image']);
        $instance['check_date'] =strip_tags($new_instance['check_date']);
        $instance['check_author'] =strip_tags($new_instance['check_author']);
        $instance['check_category'] =strip_tags($new_instance['check_category']);
        $instance['check_comments'] =strip_tags($new_instance['check_comments']);
        $instance['check_excerpt'] =strip_tags($new_instance['check_excerpt']);
        $instance['check_views'] =strip_tags($new_instance['check_views']);    
        return $instance;
    }       
    public function widget($args, $instance) {   
        global $category;
        $post_count = $instance['post_count'];
        $title = apply_filters('widget_title', $instance['title']);
	//Display the widget title
        echo $args['before_widget'];
            if ( $title )                
                echo $args['before_title'] . $title . $args['after_title'];
            $arg = new WP_Query(
                array(
                    "posts_per_page" => $post_count,
                    "post_type" => "post",
                    "post_status" => "publish",
                    "order" => "DESC",
                    'cat' => $category
                )
            );
            global $post;
                if($arg->have_posts()) { echo "<ul class='awp-list'>"; }
                    while ( $arg->have_posts() ) : $arg->the_post();
                        echo'<li class="featured-post-list">';
                            echo'<div class="featured-post-data">';
                                echo'<div class="featured-image">';
                                    if($instance['check_image']){                          
                                        display_featured_image();
                                    }   
                                echo'</div>';
                                echo'<div class="post-meta">';
                                    echo'<h4 class="awp-post-title">';
                                        echo '<a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a>';
                                    echo'</h4>';
                                    if($instance['check_date']){
                                        echo "<p>";
                                        the_date('','','',true);
                                        echo "</p>";
                                    }
                                    if($instance['check_author']){
                                        echo "<p>";
                                        display_post_author_name();
                                        echo "</p>";
                                    }                                                                           
                                echo'</div>';
                            echo'</div>';
                            echo'<div class="post_stats">';
                                if($instance['check_category']){
                                        echo "<strong>Category:</strong>";
                                        echo get_the_category_list();                                        
                                    } 
                                if($instance['check_comments']){
                                    echo "<p>This post has ";
                                    comments_number();
                                    echo "</p>";
                                }
                                if($instance['check_views']){                                    
                                    echo "<p>";
                                    show_views();
                                    echo "</p>";
                                }
                            echo'</div>';
                            echo'<div class="post_excerpt">';
                                if($instance['check_excerpt']){
                                    the_excerpt();
                                }
                            echo'</div>';                             
                    echo'</li>';
                endwhile;
                if($arg->have_posts()) { echo "</ul>"; }
            echo $args['after_widget'];
    }
}
?>