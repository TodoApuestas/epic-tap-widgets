<?php
if(!class_exists('Epic_Banner_Promo_Widget')){
    class Epic_Banner_Promo_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Banner_Promo_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_banner_promo', __( 'Banner Promo', 'epic' ), array(
                    'classname'   => 'widget_epic_banner_promo',
                    'description' => __( 'Use this widget to show a promotion banner.', 'epic' ),
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
            $instance['text_script'] = isset($new_instance['text_script'])?$new_instance['text_script']:'';
            $instance['width'] = isset($new_instance['width'])?$new_instance['width']:'';
            $instance['height'] = isset($new_instance['height'])?$new_instance['height']:'';
            $instance['position'] = isset($new_instance['position'])?$new_instance['position']:'';
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
            $text_script = isset($instance['text_script'])?$instance['text_script']:'';
            $width = isset($instance['width'])?$instance['width']:'';
            $height = isset($instance['height'])?$instance['height']:'';
            $position = isset($instance['position'])?$instance['position']:'center';
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'text_script' ); ?>"><?php _e( 'Codigo html/javascript', 'epic' ); ?>:</label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'text_script' ); ?>" name="<?php echo $this->get_field_name( 'text_script' ); ?>" cols="4" rows="5">
                    <?php echo esc_attr( $text_script ); ?>
                </textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Ancho', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $width;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Alto', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $height;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'position' ); ?>"><?php _e( 'Posicion', 'epic' ); ?>:</label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'position' ); ?>" name="<?php echo $this->get_field_name( 'position' ); ?>">
                    <option value="left" <?php if(strcmp($position, 'left') == 0):?>selected="selected"<?php endif; ?>>Izquierda</option>
                    <option value="center" <?php if(strcmp($position, 'center') == 0):?>selected="selected"<?php endif; ?>>Centro</option>
                    <option value="right" <?php if(strcmp($position, 'right') == 0):?>selected="selected"<?php endif; ?>>Derecha</option>
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
            $text_script = $instance[ 'text_script' ];
            $width = $instance[ 'width' ];
            $height = $instance[ 'height' ];
            $position = $instance[ 'position' ];?>
            <div class="widget widget_epic_banner_promo marB0" style="width: <?php echo $width;?>px; height: <?php echo $height;?>px; <?php if(strcmp($position, 'center') == 0):?>margin:0 auto;<?php else: ?>float:<?php echo $position; ?>;display: inline;<?php endif; ?>"><?php
            echo $text_script;?>
            </div><?php
        }
    }
}
