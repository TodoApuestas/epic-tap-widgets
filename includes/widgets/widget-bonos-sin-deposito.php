<?php
if(!class_exists('Epic_Bonos_Sin_Deposito_Widget')){
    class Epic_Bonos_Sin_Deposito_Widget extends WP_Widget {
        private $track_category;
        private $track_domain;

        /**
         * Constructor.
         */
        public function __construct() {
            parent::__construct( 'widget_epic_bonos_sin_deposito', __( 'Casas de Apuestas (Top)', 'epic' ), array(
                'classname'   => 'widget_epic_casas_apuestas_top',
                'description' => __( 'Use este widget para mostrar las casas de apuestas.', 'epic' ),
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
            $title = isset($instance['title']) ? $instance['title'] :  __('Casas de apuestas', 'epic');
            $track = isset($instance['track']) ? $instance['track'] : $this->track_domain;
            $track_category = isset($instance['track_category']) ? $instance['track_category'] : $this->track_category;
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo de la columna:', 'epic' ); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($title);?>"/>
            </p>
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
            if(array_key_exists('before_widget', $args)) echo $args['before_widget']; ?>
            <div class="block_bookies"><?php
            $list_blocks_bookies = array();
            if(!empty($result_from_api) && isset($result_from_api['blocks_bookies'])){
                $list_blocks_bookies = $result_from_api['blocks_bookies'];
            }
            if(!empty($list_blocks_bookies)):?>
                <div class="block-bookies-flex-table">
                    <?php foreach($list_blocks_bookies as $key => $block_bookie):?>
                        <div class="block-bookies-flex-item">
                            <a href="<?php echo esc_url($block_bookie['accion']) ?>" class="bookies-info" data-toggle="tooltip" data-placement="top" target="_blank">
                                <img class="img-responsive" src="<?php echo esc_url($block_bookie['logo']); ?>" >
                                Reg&iacute;strate
                            </a>
                            <?php if(strcmp($key, 'bet365') !== 0): ?>
                                <div style="display: none;" class="tt-bookie text-center">
                                    <?php echo sprintf(__('<p>Casa de apuestas</p><p class="bookie_name">%s</p>', 'epic'), $block_bookie['nombre']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-center marT10" style="font-size: 12px;"><?php echo _e('Publicidad | +18 Juega con responsabilidad', 'epic') ?></p>
            <?php else: ?>
                <p class="text-center marT10"><?php _e('No hay casas de apuestas publicadas', 'epic'); ?></p><?php
            endif; ?>
            </div><?php
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
        }
    }
}
