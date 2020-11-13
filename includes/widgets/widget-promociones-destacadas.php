<?php
if(!class_exists('Epic_Promociones_Destacadas_Widget')){
    class Epic_Promociones_Destacadas_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Promociones_Destacadas_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_promociones_destacadas', __( 'Promociones Destacadas', 'epic' ), array(
                    'classname'   => 'widget_epic_promociones_destacadas',
                    'description' => __( 'Use este widget para mostrar las promociones destacadas.', 'epic' ),
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
            $instance['category'] = isset($new_instance['category']) ? $new_instance['category'] : $old_instance['category'];
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
            $title = isset($instance['title'])?$instance['title']:__('Promociones Destacadas','epic');
            $limit = isset($instance['limit'])?$instance['limit']: 3;
            $category = isset($instance['category'])?$instance['category']: '';
            $categories = get_categories(); ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo de la columna', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Seleccionar categoria relacionada:', 'epic' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
                    <?php foreach($categories as $cat): ?>
                        <?php $selected = ($cat->slug == $category)?'selected="selected"':''?>
                        <option value="<?php echo $cat->slug?>" <?php echo $selected;?>><?php echo $cat->name?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Cantidad de noticias a mostrar', 'epic' ); ?>:</label>
                <input type="text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $limit;?>"/>
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
            $limit = $instance['limit'];
            $category = $instance['category'];

            $query = array(
                'post_type' => 'post',
                'posts_per_page' => $limit,
                'category_name' => $category,
                'order' => 'DESC',
                'orderby' => 'date',
            );
            $promociones_destacadas = new WP_Query($query);
            if(array_key_exists('before_widget', $args)) echo $args['before_widget']; ?>
            <h2><?php echo $instance['title']; ?></h2><?php
            if($promociones_destacadas->have_posts()):?>
                <div class="row"><?php $i = 1;
                while($promociones_destacadas->have_posts()): $promociones_destacadas->the_post(); $promocion_destacada = get_post(); ?>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 margin-bottom-20">
                        <a href="<?php the_permalink() ?>" title="<?php echo $promocion_destacada->post_title; ?>">
                            <?php if(has_post_thumbnail($promocion_destacada->ID)):?>
                                <?php the_post_thumbnail('promocion-destacada-248x98', array('alt'=>$promocion_destacada->post_title, 'title'=>$promocion_destacada->post_title, 'class' => 'img-responsive', 'loading' => 'lazy'));?>
                            <?php else:?>
                                <img src="<?php echo get_theme_mod('epic_blog_default_image')?>" class="img-responsive" alt="<?php echo $promocion_destacada->post_title; ?>" title="<?php echo $promocion_destacada->post_title; ?>" style="width: 248px; height: 98px;" loading="lazy">
                            <?php endif;?>
                        </a>
                        <h4>
                            <a href="<?php echo esc_url(get_permalink($promocion_destacada->ID)); ?>" title="<?php echo sprintf(__('Leer mas sobre $1%s', 'epic'), $promocion_destacada->post_title);  ?>" rel="bookmark">
                                <?php echo $promocion_destacada->post_title; ?>
                            </a>
                        </h4>
                        <ul class="blog-info">
                            <li><i class="fa fa-calendar"></i> <?php the_time(get_option('date_format')); ?></li>
                            <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
                                <li><i class="fa fa-comments"></i> <?php comments_popup_link(__('0', 'epic'), __('1', 'epic'), __('%', 'epic')); ?></li>
                            <?php endif; ?>
                            <li><i class="fa fa-tags"></i> <?php echo sprintf(__('Por %s', 'epic'), '<span class="vcard">' . get_the_author() . '</span>') ?></li>
                        </ul>
                        <p><?php echo epic_truncate(strip_tags($promocion_destacada->post_content), 450, true); ?></p>
                        <a class="more" href="<?php the_permalink() ?>" rel="nofollow"><?php _e('Leer mas', 'epic') ?> <i class="fa fa-angle-right"></i></a>
                    </div><?php
                    if ($i % 3 == 0 && $i < $promociones_destacadas->post_count ): ?>
                        </div><div class="row margin-top-50"><?php
                    endif;
                    $i++;
                endwhile; ?>
                </div><?php
            else: ?>
            <p><?php _e('No hay promociones destacadas publicadas', 'epic')?></p><?php
            endif;
            if(array_key_exists('after_widget', $args)) echo $args['after_widget']; ?>
            <div class="clearfix"></div><hr><?php

            wp_reset_postdata();
        }
    }
}
