<?php
if(!class_exists('Epic_Tipsters_Blog_Widget')){
    class Epic_Tipsters_Blog_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Tipsters_Blog_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_tipsters_blog', __( 'Tipsters del Blog', 'epic' ), array(
                    'classname'   => 'widget_epic_tipsters_blog',
                    'description' => __( 'Use este widget para visualizar los tipsters.', 'epic' ),
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
            $title = isset($instance['title']) ? $instance['title'] : __('Tipsters del Blog', 'epic');
            $limit = isset($instance['limit']) ? $instance['limit'] : 10;?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo del bloque', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Escribir la cantidad de tipster a visualizar', 'epic' ); ?>:</label>
                <input type="text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $limit;?>"/>
            </p><?php
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget( $args, $instance ) {
            global $wpdb, $post;
            $title = $instance['title'];
            $limit = $instance['limit'];

            $query_post_type_tipster = array(
                'post_type' => 'tipster',
                'posts_per_page' => $limit
            );

            $tipsters_blog = array();

            $tipsters = new WP_Query($query_post_type_tipster);
            if($tipsters->have_posts()):
                while($tipsters->have_posts()):
                    $tipsters->the_post();
                    $tipster = $post;

                    $picks_no_pendientes_by_tipster = epic_picks_no_pendientes_by_tipster($tipster->ID, 10);
	                $tipster_aciertos = $tipster_nulos = $tipster_fallos = 0;
	                foreach ( $picks_no_pendientes_by_tipster as $p ):
		                $resultado_pick_no_pendiente = $p['pick_result'];
		                switch($resultado_pick_no_pendiente):
			                case 'acierto':
				                ++ $tipster_aciertos;
				                break;
			                case 'fallo':
				                ++ $tipster_fallos;
				                break;
			                default: // nulo
				                ++ $tipster_nulos;
				                break;
		                endswitch;
	                endforeach;
	
	                $last_stats = get_post_meta($tipster->ID, '_tipster_statistics_last', true);

                    $tipsters_blog[$last_stats['acumulado']['yield'].'-'.$tipster->ID] = array(
                        'tipster' => $tipster,
                        'statistics' => array(
	                        'yield'       => number_format_i18n( $last_stats['acumulado']['yield'], 2 ),
	                        'total_picks' => number_format_i18n( $last_stats['acumulado']['picks'] ),
	                        'corrects'    => number_format_i18n( $tipster_aciertos ),
	                        'wrongs'      => number_format_i18n( $tipster_fallos ),
	                        'voids'       => number_format_i18n( $tipster_nulos )
                        )
                    );
                endwhile;
            endif;

            if(array_key_exists('before_widget', $args)) echo $args['before_widget'];?>
            <!-- Tipsters del Blog BEGIN --><?php
            if(array_key_exists('before_title', $args) && array_key_exists('after_title', $args))
                echo $args['before_title']. $title . $args['after_title']; ?>
            <div class="recent-news margin-bottom-10"><?php
            if(!empty($tipsters_blog)):
                krsort($tipsters_blog, SORT_NATURAL);
                foreach ($tipsters_blog as $tipster): ?>
                <div class="row margin-bottom-10">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <?php if(has_post_thumbnail($tipster['tipster']->ID)):?>
                        <?php echo get_the_post_thumbnail($tipster['tipster']->ID, 'tipster-avatar-50x50', array('alt'=>$tipster['tipster']->post_title, 'title'=>$tipster['tipster']->post_title, 'class' => 'img-thumbnail img-responsive pull-left', 'style'=>"margin: 0 5px 5px 0;", 'loading' => 'lazy' ));?>
                    <?php else:?>
                        <?php echo apply_filters('tipster_tap_default_avatar', $tipster['tipster']->post_title, 'img-thumbnail img-responsive pull-left', 'width: 50px; height: 50px; margin: 0 5px 5px 0;'); ?>
                    <?php endif;?>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 recent-news-inner">
                        <h3>
                            <a href="<?php echo esc_url(get_permalink($tipster['tipster']->ID)) ?>">
                                <?php echo $tipster['tipster']->post_title; ?>
                            </a>
                        </h3>
                    </div>
                </div>
                <div class="row margin-bottom-10">
                    <div class="col-md-12 recent-news-inner">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th><?php _e('Yield', 'epic') ?></th>
                                    <th><?php _e('Picks', 'epic') ?></th>
                                    <th><?php _e('Ult. 10 picks', 'epic') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $tipster['statistics']['yield']; ?>&percnt;</td>
                                    <td><?php echo $tipster['statistics']['total_picks']; ?></td>
                                    <td><span style="color:green"><?php echo $tipster['statistics']['corrects']; ?>A</span> - <span style="color:red"><?php echo $tipster['statistics']['wrongs']; ?>F</span> - <span style="color:blue"><?php echo $tipster['statistics']['voids']; ?>N</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><?php
                endforeach;
            else: ?>
                <p><?php _e('No hay tipster registrado', 'epic')?></p><?php
            endif; ?>
            </div>
            <!-- Tipsters del Blog END --><?php

            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];

            wp_reset_postdata();
        }
    }
}
