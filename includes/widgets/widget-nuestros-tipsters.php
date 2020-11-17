<?php
if(!class_exists('Epic_Nuestros_Tipsters_Widget')){
    class Epic_Nuestros_Tipsters_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Nuestros_Tipsters_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_nuestros_tipsters', __( 'Nuestros Tipsters', 'epic' ), array(
                    'classname'   => 'widget_epic_nuestros_tipsters',
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
            $title = isset($instance['title']) ? sanitize_text_field($instance['title']) : __('Nuestros Tipsters', 'epic');
            $limit = isset($instance['limit']) ? sanitize_text_field($instance['limit']) : 10;?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo del bloque', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($title);?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Escribir la cantidad de tipster a visualizar', 'epic' ); ?>:</label>
                <input type="text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo esc_attr($limit);?>"/>
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

            $ours_tipsters = array();

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

                    $ours_tipsters[$last_stats['acumulado']['yield'].'-'.$tipster->ID] = array(
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

            if(array_key_exists('before_widget', $args)) echo $args['before_widget']; ?>
            <!-- Nuestros Tipsters BEGIN -->
            <h2><?php echo $title ?></h2>
            <div id="nuestros-tipsters" class="tipsters carousel slide no-margin">
                <!-- Carousel items -->
                <div class="carousel-inner">
                    <div class="active item"><?php
                if(!empty($ours_tipsters)):
                    krsort($ours_tipsters, SORT_NATURAL);
                    $i = 1; $total_ours_tipsters = count($ours_tipsters);
                    foreach ($ours_tipsters as $tipster): ?>
                        <div class="row clearfix<?php if($i % 2 != 0 && $i < $total_ours_tipsters): ?> margin-bottom-10px<?php endif;?>" style="color:white !important;">
                            <div class="col-md-4 no-padding no-margin">
                                <a href="<?php echo esc_url(get_permalink($tipster['tipster']->ID)) ?>">
                                    <?php if(has_post_thumbnail($tipster['tipster']->ID)):?>
                                        <?php echo get_the_post_thumbnail($tipster['tipster']->ID, 'tipster-avatar-75x75', array('alt'=>$tipster['tipster']->post_title, 'title'=>$tipster['tipster']->post_title, 'class' => 'img-thumbnail img-responsive pull-left', 'loading' => 'lazy'));?>
                                    <?php else:?>
                                        <?php echo apply_filters('tipster_tap_default_avatar', $tipster['tipster']->post_title, 'img-thumbnail img-responsive pull-left', 'width: 75px; height: 75px;'); ?>
                                    <?php endif;?>
                                </a>
                            </div>
                            <div class="col-md-8 no-padding-right">
                                <h4><a href="<?php echo esc_url(get_permalink($tipster['tipster']->ID)) ?>"><?php echo esc_html($tipster['tipster']->post_title); ?></a></h4>
                            </div>
                            <div class="col-md-12 clearfix">
                                <table class="table table-responsive no-margin-bottom" style="color:white !important;">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Yield', 'epic') ?></th>
                                            <th><?php _e('Picks', 'epic') ?></th>
                                            <th><?php _e('Ult. 10 picks', 'epic') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo esc_html($tipster['statistics']['yield']); ?>&percnt;</td>
                                            <td><?php echo esc_html($tipster['statistics']['total_picks']); ?></td>
                                            <td><span ><?php echo esc_html($tipster['statistics']['corrects']); ?>A</span> - <span ><?php echo esc_html($tipster['statistics']['wrongs']); ?>F</span> - <span><?php echo esc_html($tipster['statistics']['voids']); ?>N</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><?php
                        if ( $i % 2 == 0 && $i < $total_ours_tipsters): ?>
                        </div><div class="item"><?php
                        endif;
                        $i ++;
                    endforeach; ?>
                    </div><?php
                else: ?>
                    <div class="active item">
                        <p><?php _e('No hay tipster registrado', 'epic')?></p>
                    </div><?php
                endif; ?>
                </div>
                <!-- Carousel nav -->
                <a class="left-btn" href="#nuestros-tipsters" data-slide="prev"></a>
                <a class="right-btn" href="#nuestros-tipsters" data-slide="next"></a>
            </div><?php
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];

            wp_reset_postdata();
        }
    }
}
