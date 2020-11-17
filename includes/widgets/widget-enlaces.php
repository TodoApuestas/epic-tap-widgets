<?php
if(!class_exists('Epic_Enlaces_Widget')){
    class Epic_Enlaces_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Enlaces_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_enlaces', __( 'Enlaces', 'epic' ), array(
                    'classname'   => 'widget_epic_enlaces',
                    'description' => __( 'Use este widget para mostrar los enlaces de paginas asociadas.', 'epic' ),
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

            $instance['name'] = isset($new_instance['name']) ? $new_instance['name'] : $old_instance['name'];

            $instance['orderby'] = 'name';
            if ( in_array( $new_instance['orderby'], array( 'name', 'rating', 'id', 'rand' ) ) )
                $instance['orderby'] = $new_instance['orderby'];

            $instance['category'] = (integer)$new_instance['category'];
            $instance['limit'] = ! empty( $new_instance['limit'] ) ? (integer)$new_instance['limit'] : -1;

            $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : $old_instance['title'];

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
            $limit = 5;
            $link_cats = get_terms( 'link_category' );
            if ( in_array('limit', $instance) && !$limit = (integer)sanitize_text_field($instance['limit']) ) {
	            $limit = -1;
            }
            $title = isset($instance['title']) ? sanitize_text_field($instance['title']) : __('Nuestras Webs','epic'); ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo de la columna', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($title);?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Select Link Category', 'epic' ); ?>:</label>
                <select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
                    <option value=""><?php _ex('All Links', 'links widget', 'epic'); ?></option>
                    <?php
                    foreach ( $link_cats as $link_cat ) {
                        printf('<option value="%s" %s >%s</option>\n', (integer)$link_cat->term_id, selected( $instance['category'], $link_cat->term_id, false ), $link_cat->name);
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by', 'epic' ); ?>:</label>
                <select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
                    <option value="name"<?php if(in_array('orderby', $instance)): selected( $instance['orderby'], 'name' ); endif; ?>><?php _e( 'Link title', 'epic' ); ?></option>
                    <option value="rating"<?php if(in_array('orderby', $instance)): selected( $instance['orderby'], 'rating' ); endif; ?>><?php _e( 'Link rating', 'epic' ); ?></option>
                    <option value="id"<?php if(in_array('orderby', $instance)): selected( $instance['orderby'], 'id' ); endif; ?>><?php _e( 'Link ID', 'epic' ); ?></option>
                    <option value="rand"<?php if(in_array('orderby', $instance)): selected( $instance['orderby'], 'rand' ); endif; ?>><?php _ex( 'Random', 'Links widget', 'epic' ); ?></option>
                </select>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php if(in_array('name', $instance)): checked($instance['name'], true); endif; ?> id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" />
                <label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Show Link Name', 'epic'); ?></label><br />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e( 'Number of links to show', 'epic' ); ?>:</label>
                <input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit === -1 ? 5 : (integer)$limit; ?>" size="3" />
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
            extract($args, EXTR_SKIP);

            $category = isset($instance['category']) ? $instance['category'] : false;
            $orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'name';
            $order = $orderby == 'rating' ? 'DESC' : 'ASC';
            $limit = isset( $instance['limit'] ) ? $instance['limit'] : -1;

            $bookmarks_arg = array(
                'orderby' => $orderby, 'order' => $order,
                'limit' => $limit, 'category' => $category,
            );
            $enlaces = get_bookmarks($bookmarks_arg);
            $enlaces_count = count($enlaces);

            if(array_key_exists('before_widget', $args)) echo $args['before_widget']; ?>
            <h2><?php echo $instance['title']; ?></h2><?php
            if($enlaces_count): ?>
            <ul class="list-unstyled"><?php
            foreach($enlaces as $enlace):
                $enlace_name = isset($instance['name']) ? esc_html($enlace->link_name) : esc_url($enlace->link_url);
                $enlace_description = empty($enlace->link_description) ? '' : 'title="'.esc_attr($enlace->link_description).'"';
                $enlace_href = esc_url($enlace->link_url);
                $enlace_target = empty($enlace->link_target) ? 'target="_blank"' : 'target="'.esc_attr($enlace->link_target).'"';
                $enlace_rel = empty($enlace->link_rel) ? '' : 'rel="'.esc_attr($enlace->link_rel).'"'; ?>
                <li>
                    <a href="<?php echo $enlace_href; ?>" <?php echo $enlace_rel?> <?php echo $enlace_target; ?> <?php echo $enlace_description ?>>
                        <i class="fa fa-check"></i> <?php echo $enlace_name; ?>
                    </a>
                </li><?php
            endforeach; ?>
            </ul><?php
            else: ?>
            <p><?php _e('No hay enlaces publicados', 'epic')?></p><?php
            endif;
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];

            wp_reset_postdata();
        }
    }
}
