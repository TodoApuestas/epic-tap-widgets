<?php
if(!class_exists('Epic_Tags_Cloud_Widget')){
    class Epic_Tags_Cloud_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Tags_Cloud_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_tags', __( 'Tags Cloud', 'epic' ), array(
                    'classname'   => 'widget_epic_tags',
                    'description' => __( 'Use this widget to show a tag cloud.', 'epic' ),
                ) );
        }

        /**
         * Deals with the settings when they are saved by the admin. Here is
         * where any validation should be dealt with.
         *
         * @param array  An array of new settings as submitted by the admin
         * @param array  An array of the previous settings
         * @return array The validated and (if necessary) amended settings
         **/
        public function update( $new_instance, $old_instance )
        {
            $instance = $old_instance;
            $instance['title'] = strip_tags(stripslashes($new_instance['title']));
            $instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
            return $instance;
        }

        /**
         * Displays the form for this widget on the Widgets page of the WP Admin area.
         *
         * @param array  An array of the current settings for this widget
         * @return void
         **/
        public function form( $instance )
        {
            $current_taxonomy = $this->_get_current_taxonomy($instance);
            $title = isset($instance['title'])?sanitize_text_field($instance['title']):__('Tags', 'epic'); ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Column Title', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($title);?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy', 'epic') ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
                    <?php foreach ( get_taxonomies() as $taxonomy ) :
                        $tax = get_taxonomy($taxonomy);
                        if ( !$tax->show_tagcloud || empty($tax->labels->name) )
                            continue;
                        ?>
                        <option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo esc_html($tax->labels->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
<?php   }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget( $args, $instance ) {
            extract($args);
            $current_taxonomy = $this->_get_current_taxonomy($instance);
            if ( !empty($instance['title']) ) {
                $title = $instance['title'];
            } else {
                if ( 'post_tag' == $current_taxonomy ) {
                    $title = __('Tags', 'epic');
                } else {
                    $tax = get_taxonomy($current_taxonomy);
                    $title = $tax->labels->name;
                }
            }

            /** This filter is documented in wp-includes/default-widgets.php */
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

            if(array_key_exists('before_widget', $args)) echo $args['before_widget']; ?>
	        <?php
//            if(array_key_exists('before_title', $args) && array_key_exists('after_title', $args))
//              echo $args['before_title']. $title . $args['after_title']; ?>
            <h3 class="hl"><?php echo $title; ?></h3>
            <hr>
            <p class="tags">
            <?php
	            /**
	             * Filter the taxonomy used in the Tag Cloud widget.
	             *
	             * @since 2.8.0
	             * @since 3.0.0 Added taxonomy drop-down.
	             *
	             * @see wp_tag_cloud()
	             *
	             * @param array $current_taxonomy The taxonomy to use in the tag cloud. Default 'tags'.
	             */
                wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array('taxonomy' => $current_taxonomy, 'format' => 'flat' ) ) );
?>          </p>
<?php       if(array_key_exists('after_widget', $args)) echo $args['after_widget'];

            wp_reset_postdata();
        }

        function _get_current_taxonomy($instance) {
            if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
                return $instance['taxonomy'];

            return 'post_tag';
        }
    }
}
