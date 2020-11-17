<?php
if(!class_exists('Epic_Random_News_Widget')){
    class Epic_Random_News_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Random_News_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_random_news', __( 'Articulos Aleatorios', 'epic' ), array(
                    'classname'   => 'widget_epic_random_news',
                    'description' => __( 'Use este widget para mostrar articulos aleatorios.', 'epic' ),
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
            $title = isset($instance['title']) ? sanitize_text_field($instance['title']) : __('Visita muchos mas articulos','epic');?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo del bloque', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($title);?>"/>
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
            $query_args = array(
                'post_type' => 'post',
                'orderby' => 'rand',
                'posts_per_page' => 4
            );
            $query = new WP_Query($query_args);

            if(array_key_exists('before_widget', $args)) echo $args['before_widget'];
            if(array_key_exists('before_title', $args)) echo $args['before_title'];
            echo $instance['title'];
            if(array_key_exists('after_title', $args)) echo $args['after_title'];
            if($query->have_posts()): ?>
                <div class="row">
                    <?php while($query->have_posts()): $query->the_post(); ?>
                    <div class="col-xs-6 col-md-3">
                        <article class="text-center">
                            <a href="<?php the_permalink() ?>" class="thumbnail">
                                <?php if(has_post_thumbnail($post->ID)):?>
                                    <?php the_post_thumbnail('post-205x150', array('alt'=>$post->post_title, 'title'=>$post->post_title, 'class' => 'img-responsive center-block', 'loading' => 'lazy'));?>
                                <?php else:?>
                                    <?php $default_image = get_theme_mod('epic_blog_default_image') ?>
                                    <img src="<?php echo esc_url($default_image);?>" class="img-responsive center-block" alt="<?php echo $post->post_title; ?>" style="width: 205px; height: 150px;" loading="lazy">
                                <?php endif;?>
                            </a>
                            <?php the_title(sprintf('<a href="%s">', get_the_permalink()), '</a>'); ?>
                        </article>
                    </div>
                    <?php endwhile ?>
                </div>
            <?php endif; ?>
            <?php wp_reset_query()?>
            <?php
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
        }
    }
}
