<?php
if(!class_exists('Epic_Bonos_Sin_Deposito_Widget')){
    class Epic_Bonos_Sin_Deposito_Widget extends WP_Widget {
        private $track_category;
        private $track_domain;

        /**
         * Constructor.
         */
        public function __construct() {
            parent::__construct( 'widget_epic_bonos_sin_deposito', __( 'Bonos sin Deposito', 'epic' ), array(
                    'classname'   => 'widget_epic_bonos_sin_deposito',
                    'description' => __( 'Use este widget para mostrar los bonos sin depositos.', 'epic' ),
                ) );
            $this->track_domain = get_theme_mod('tap_tracker_domain');
            $this->track_category = get_theme_mod('tap_tracker_web_category', 'apuestas');
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
            $instance['title'] = $new_instance['title'];
//            $instance['limit'] = $new_instance['limit'];
            $instance['track'] = $new_instance['track'];
            $instance['track_category'] = $new_instance['track_category'];
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
            $title = isset($instance['title'])?sanitize_text_field($instance['title']): __('Bonos sin Deposito', 'epic');
//            $limit = isset($instance['limit'])?$instance['limit']:'blog';
            $track = isset($instance['track']) ? sanitize_text_field($instance['track']) : $this->track_domain;
            $track_category = isset($instance['track_category']) ? sanitize_text_field($instance['track_category']) : $this->track_category;
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo de la columna:', 'epic' ); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($title);?>"/>
            </p>
<!--            <p>-->
<!--                <label for="--><?php //echo $this->get_field_id( 'limit' ); ?><!--">--><?php //_e( 'Cantidad a mostrar:', 'epic' ); ?><!--</label>-->
<!--                <input type="text" class="widefat" id="--><?php //echo $this->get_field_id( 'limit' ); ?><!--" name="--><?php //echo $this->get_field_name( 'limit' ); ?><!--" value="--><?php //echo $limit;?><!--"/>-->
<!--            </p>-->
            <p>
                <label for="<?php echo $this->get_field_id( 'track' ); ?>"><?php _e( 'Web a trackear:', 'epic' ); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'track' ); ?>" name="<?php echo $this->get_field_name( 'track' ); ?>" value="<?php echo esc_attr($track);?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'track_category' ); ?>"><?php _e( 'Categoria de tracking:', 'epic' ); ?></label>
                <select name="<?php echo $this->get_field_name('track_category'); ?>" id="<?php echo $this->get_field_id('track_category'); ?>" class="widefat">
                    <option value="apuestas"<?php selected( $track_category, 'apuestas' ); ?>><?php _e( 'Apuestas', 'epic' ); ?></option>
                    <option value="casinos"<?php selected( $track_category, 'casinos' ); ?>><?php _e( 'Casinos', 'epic' ); ?></option>
                    <option value="poker"<?php selected( $track_category, 'poker' ); ?>><?php _e( 'Poker', 'epic' ); ?></option>
                    <option value="bingo"<?php selected( $track_category, 'bingo' ); ?>><?php _ex( 'Bingo', 'epic' ); ?></option>
                </select>
            </p>
        <?php
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
            $track_site = null !== $instance['track'] ? $instance['track'] : $this->track_domain;
            $track_category = null !== $instance['track_category'] ? $instance['track_category'] : $this->track_category;
            $result_from_api = apply_filters('rest_client_tap_request_block_bookies', $track_site, $track_category);
            if(array_key_exists('before_widget', $args)) echo $args['before_widget'];
            if(array_key_exists('before_title', $args)) echo $args['before_title'];
            echo $instance['title'];
            if(array_key_exists('after_title', $args)) echo $args['after_title']; ?>
            <div class="block_bookies"><?php
            $bonos_no_deposit = array();
            $link_more = null;
            if(!empty($result_from_api) && isset($result_from_api['bonos_no_deposit'])){
                $bonos_no_deposit = $result_from_api['bonos_no_deposit'];
                $link_more = $result_from_api['link_more_no_deposit'];
            }
            if(!empty($bonos_no_deposit)):?>
                <table class="table table-responsive table-block-bookies">
                    <tbody>
                    <?php foreach($bonos_no_deposit as $key => $bono_no_deposit):?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url($bono_no_deposit['accion']) ?>" class="bookies-info" data-toggle="tooltip" data-placement="top" target="_blank">
                                    <img class="img-resposive" src="<?php echo esc_url($bono_no_deposit['logo']); ?>" >
                                </a>
                                <?php if(strcmp($key, 'bet365') !== 0): ?>
                                <div style="display: none;" class="tt-bookie text-center">
                                    <?php echo sprintf(__('<p>Casa de apuestas</p><p class="bookie_name">%s</p>', 'epic'), $bono_no_deposit['nombre']); ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($bono_no_deposit['accion']) ?>" class="bookies-bono" target="_blank">
                                    <?php echo esc_html($bono_no_deposit['bono']); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!--<a class="button-tip-link" href="http://www.foro-apuestas.com/index.php?threads/%C2%A1reg%C3%ADstrate-desde-todoapuestas-con-un-dep%C3%B3sito-m%C3%ADnimo-y-te-regalamos-10.12323/" target="_blank">
                    <div class="button-tip bottom">
                        <div class="button-tip-inner button-tip-inner2"> <?php /*_e('Como obtener el pin', 'epic') */?> </div>
                    </div>
                </a>-->
                <?php if(null !== $link_more): ?>
                <a class="button-tip-link" href="<?php echo esc_url($link_more) ?>" target="_blank">
                    <div class="button-tip bottom">
<!--                        <div class="button-tip-arrow"></div>-->
                        <div class="button-tip-inner"> <?php _e('Ver mas bonos', 'epic') ?> </div>
                    </div>
                </a>
                <?php endif;
            else: ?>
                <p class="text-center"><?php _e('No hay bonos publicados', 'epic')?></p><?php
            endif; ?>
            </div><?php
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
        }
    }
}
