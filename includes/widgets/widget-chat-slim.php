<?php
if(!class_exists('Epic_Chat_Slim_Widget')){
    class Epic_Chat_Slim_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Chat_Slim_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_chat_slim', __( 'Chat Slim', 'epic' ), array(
                    'classname'   => 'widget_epic_chat_slim',
                    'description' => __( 'Use este widget para visualizar las salas de chat.', 'epic' ),
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
            $instance['url'] = isset($new_instance['url']) ? $new_instance['url'] : $old_instance['url'];
            $instance['height'] = isset($new_instance['height']) ? $new_instance['height'] : $old_instance['height'];
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
            $url = isset($instance['url']) ? $instance['url'] : 'http://www.foro-apuestas.com/chat_slim/?pagina=top100apuestas';
            $height = isset($instance['height']) ? $instance['height'] : 400;
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Url del Chat Slim', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $url;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Alto del bloque', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $height;?>"/>
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
            $url = $instance[ 'url' ];
            $height = $instance[ 'height' ];

            if(array_key_exists('before_widget', $args)) echo $args['before_widget']; ?>
            <iframe frameborder="0" src="<?php echo $url; ?>" marginheight="2" marginwidth="2" allowtransparency="yes" style="border: 0px solid; width: 100%; height: <?php echo $height;?>px"></iframe><?php
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
        }
    }
}
