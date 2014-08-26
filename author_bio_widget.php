<?php
class Author_Bio extends WP_Widget {
	// Controller
    protected $defaults;
    function __construct() {
        $this->defaults = array(
			'title'          => 'Author Details',
			'alignment'	 => 'left',
			'user'           => '',
			'size'           => '45',
			'author_info'    => '',
			'text-bio'       => '',
			'page'           => '',
			'page_link_text' => __( 'Read More', 'genesis' ) . '&#x02026;',
			'posts_link'     => '0',
                        'sort_radiobox'  => '0',
		);
		$widget_ops = array(
			'classname'   => 'author_bio',
			'description' => __( 'Displays user profile block with Gravatar', 'genesis' ),
		);
		$control_ops = array(
			'id_base' => 'author_bio',
			'width'   => 200,
			'height'  => 250,
		);
		parent::__construct( 'author_bio', __( 'Author Details', 'genesis' ), $widget_ops, $control_ops );

        }       
    function widget($args, $instance) {
        extract ($args);
        $instance = wp_parse_args((array) $instance, $this->defaults); 
        echo $before_widget;        
            //echo $args['before_widget'];
            if ( $instance['title']){
                echo $args['before_title'] .$instance['title']. $args['after_title'];}
            $text='';
            if($instance['alignment']){
		$text .= '<span class="auth-grav align' . esc_attr( $instance['alignment'] ) . '">';
            }
            $text.=get_avatar($instance['user'],$instance['size']);
            if($instance['alignment'])
                $text.='</span>';
            if($instance['sort_radiobox']=="bio")
                $text.="<div class='details'><p>".get_the_author_meta('description',$instance['user'])."</p>";
            else
                $text.="<p>".$instance['text-bio']."</p>";
            $text .= $instance['page'] ? sprintf( ' <a class="pagelink" href="%s">%s</a>', get_page_link( $instance['page'] ), $instance['page_link_text'] ) : '';
            echo wpautop($text);
            if ( $instance['posts_link'] )
		printf( '<div class="posts-link"><a href="%s">%s</a></div>', get_author_posts_url( $instance['user'] ), __( 'View My Blog Posts', 'genesis' ) );           
            echo $after_widget;                      
    }
    function form($instance) {
	$instance = wp_parse_args( (array) $instance, $this->defaults );  ?>
	<p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</p>
        <p>
                Select a user. the email address for this account will be used to pull the Gravatar image. 
        </p>
                <?php wp_dropdown_users( array( 'who' => 'authors', 'name' => $this->get_field_name( 'user' ), 'selected' => $instance['user'] ) ); ?>
        <p>            
                <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Gravatar Size', 'genesis' ); ?>:</label>
		<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
		<?php
                    $sizes = array( __( 'Small', 'genesis' ) => 45, __( 'Medium', 'genesis' ) => 65, __( 'Large', 'genesis' ) => 85, __( 'Extra Large', 'genesis' ) => 125 );
                    $sizes = apply_filters( 'solo_gravatar_sizes', $sizes );
                    foreach ( (array) $sizes as $label => $size ) { ?>
                    <option value="<?php echo absint( $size ); ?>" <?php selected( $size, $instance['size'] ); ?>><?php printf( '%s (%spx)', $label, $size ); ?></option>
		<?php } ?>
		</select>
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Gravatar Alignment', 'genesis' ); ?>:</label>
                <select id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
                <option value="">- <?php _e( 'None', 'genesis' ); ?> -</option>
                <option value="left" <?php selected( 'left', $instance['alignment'] ); ?>><?php _e( 'Left', 'genesis' ); ?></option>
                <option value="right" <?php selected( 'right', $instance['alignment'] ); ?>><?php _e( 'Right', 'genesis' ); ?></option>
                </select>
        </p>
        <p>
                Select the text you want to use as author description.
        </p>
        <p>            
                <input type="radio"
                id="<?php echo $this->get_field_id('sort_radiobox'); ?>"
                name="<?php echo $this->get_field_name('sort_radiobox'); ?>"
                <?php if (isset($instance['sort_radiobox']) && $instance['sort_radiobox']=="bio") echo "checked";?>
                       value="bio">Author Bio<br>
                <input type="radio"
                id="<?php echo $this->get_field_id('sort_radiobox'); ?>"
                name="<?php echo $this->get_field_name('sort_radiobox'); ?>"
                <?php if (isset($instance['sort_radiobox']) && $instance['sort_radiobox']=="text") echo "checked";?>
                value="text">Text           
                <textarea class="widefat" rows="6" cols="4" id="<?php echo $this->get_field_id('text-bio'); ?>" 
                name="<?php echo $this->get_field_name('text-bio'); ?>" value="<?php echo $instance['text-bio']; ?>"></textarea>
        </p>
        <p>
                Choose your "about me" page from the list below. this will be the page linked at the end of the about me section.
                <?php wp_dropdown_pages( array( 'name' => $this->get_field_name( 'page' ), 'show_option_none' => __( 'None', 'genesis' ), 'selected' => $instance['page'] ) ); ?>
        </p>
        <p>            
                <label for="<?php echo $this->get_field_id('page_link_text'); ?>"><?php _e('Extended page link text', 'wp_widget_plugin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('page_link_text'); ?>" name="<?php echo $this->get_field_name('page_link_text'); ?>" type="text" value="<?php echo $instance['page_link_text']; ?>" />
        </p>
        <p> 
                <input class="checkbox" type="checkbox" <?php checked($instance['posts_link'], 'on'); ?> id="<?php echo $this->get_field_id('posts_link'); ?>" name="<?php echo $this->get_field_name('posts_link'); ?>" /> 
                <label for="<?php echo $this->get_field_id('posts_link'); ?>">Show Author Archive link?</label>
        </p>
<?php }

    function update($new_instance,$old_instance){
            $new_instance['title']          = strip_tags( $new_instance['title'] );
            $new_instance['bio_text']       = current_user_can( 'unfiltered_html' ) ? $new_instance['bio_text'] : solo_formatting_kses( $new_instance['bio_text'] );
            $new_instance['page_link_text'] = strip_tags( $new_instance['page_link_text'] );
            return $new_instance;
    }
}