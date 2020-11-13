<?php
if(!class_exists('Epic_Articulos_Relacionados_Widget')){
    class Epic_Articulos_Relacionados_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Articulos_Relacionados_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_articulos_relacionados', __( 'Articulos Relacionados', 'epic' ), array(
                    'classname'   => 'widget_epic_articulos_relacionados',
                    'description' => __( 'Use este widget para mostrar los articulos relacionados.', 'epic' ),
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
            $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : $old_instance['title'];
            $instance['limit'] = isset($new_instance['limit']) ? $new_instance['limit'] : $old_instance['limit'];
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
            $title = isset($instance['title'])?$instance['title']:__('Articulos Relacionados','epic');
            $limit = isset($instance['limit'])?$instance['limit']: 5; ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo de la columna', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Cantidad de articulos a mostrar', 'epic' ); ?>:</label>
                <select id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>">
                <?php for($i = 1; $i<=10; $i++):?>
                    <?php $selected = ($i == $limit) ? ' selected="selected"' : '' ?>
                    <?php printf('<option value="%1$s"%2$s>%1$s</option>', $i, $selected)?>
                <?php endfor; ?>
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
            global $post;
            $articulo = get_post();
            $limit = $instance['limit'];

	        $categories = wp_get_post_categories($articulo->ID);

	        $tags = wp_get_post_tags($articulo->ID);
	        $tag_in = array();
	        foreach ( $tags as $tag ) {
		        $tag_in[] = $tag->term_id;
	        }

	        $query = null;
	        $articulos_relacionados = null;
	        if(!empty($tags) && !empty($categories)){
		        $query = array(
			        'post_type'      => 'post',
			        'posts_per_page' => $limit,
			        'tax_query'      => array(
				        array(
					        'taxonomy' => 'category',
					        'field'    => 'term_id',
					        'terms'    => $categories
				        )
			        ),
			        'tag__in'        => $tag_in,
			        'order'          => 'DESC',
			        'orderby'        => 'date',
			        'post__not_in'   => array( $articulo->ID )
		        );
		        $articulos_relacionados = new WP_Query($query);
	        }
	        if(empty($articulos_relacionados) && !empty($tags)) {
		        $query = array(
			        'post_type'      => 'post',
			        'posts_per_page' => $limit,
			        'tag__in'        => $tag_in,
			        'order'          => 'DESC',
			        'orderby'        => 'date',
			        'post__not_in'   => array( $articulo->ID )
		        );
		        $articulos_relacionados = new WP_Query($query);
	        }
	        if(empty($articulos_relacionados) && !empty($categories)){
		        $query = array(
			        'post_type'      => 'post',
                    'posts_per_page' => $limit,
			        'tax_query'      => array(
				        array(
					        'taxonomy' => 'category',
					        'field'    => 'term_id',
					        'terms'    => $categories
				        )
			        ),
			        'order'          => 'DESC',
			        'orderby'        => 'date',
			        'post__not_in'   => array( $articulo->ID )
		        );
		        $articulos_relacionados = new WP_Query($query);
	        }
	        if(!empty($articulos_relacionados)):
                if(array_key_exists('before_widget', $args)) echo $args['before_widget'];
                if(array_key_exists('before_title', $args)) echo $args['before_title'];
                echo $instance['title'];
                if(array_key_exists('after_title', $args)) echo $args['after_title'];
                if($articulos_relacionados->have_posts()): ?>
                    <div class="row"><?php $i = 1;
                    while($articulos_relacionados->have_posts()): $articulos_relacionados->the_post(); $articulo_relacionado = get_post(); ?>
                        <div class="col-xs-12 margin-bottom-20">
                        <?php
                        $tipo_publicacion = get_post_meta($articulo_relacionado->ID, '_post_tipo_publicacion', true);
                        switch ( $tipo_publicacion ){
                            case "pick":
                                get_template_part( 'content', 'pick' );
                                break;
                            default:
                                get_template_part( 'content', get_post_format() );
                                break;
                        }
                        ?>
                        </div><?php
                        if ($i % 3 == 0 && $i < $articulos_relacionados->post_count ): ?>
                        </div><div class="row margin-top-50"><?php
                        endif;
                        $i++;
                    endwhile; ?>
                    </div><?php
                else: ?>
                <p><?php _e('No hay articulos relacionados publicados', 'epic')?></p><?php
                endif;
                if(array_key_exists('after_widget', $args)) echo $args['after_widget']; ?>
                <div class="clearfix"></div><hr><?php
			endif;
            wp_reset_postdata();
        }
    }
}
