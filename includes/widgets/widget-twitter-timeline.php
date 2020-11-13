<?php
if(!class_exists('Epic_Twitter_Timeline_Widget')){
    class Epic_Twitter_Timeline_Widget extends WP_Widget {
        /**
         * Constructor.
         *
         * @return Epic_Twitter_Timeline_Widget
         */
        public function __construct() {
            parent::__construct( 'epic_twitter_timeline_widget', __( 'Twitter Timeline', 'epic' ), array(
                    'classname'   => 'epic_twitter_timeline_widget',
                    'description' => __( 'Use this widget to show Twitter Timeline.', 'epic' ),
                ) );
        }

        /**
         * Output the HTML for this widget.
         *
         * @access public
         *
         * @param array $args     An array of standard parameters for widgets in this theme.
         * @param array $instance An array of settings for this widget instance.
         * @return void Echoes it's output
         */
        public function widget( $args, $instance ) {
            if(array_key_exists('before_widget', $args)) echo $args['before_widget']; ?>
            <a class="twitter-timeline" data-lang="es" data-theme="<?php echo $instance['twitter_theme'];?>" data-link-color="<?php echo $instance['twitter_link_color'];?>" href="<?php echo $instance['twitter_url'];?>">Tweets por <?php echo $instance['twitter_account'];?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            <?php
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
        }

        /**
         * Deals with the settings when they are saved by the admin. Here is
         * where any validation should be dealt with.
         *
         * @param array $new_instance An array of new settings as submitted by the admin
         * @param array $old_instance An array of the previous settings
         * @return array The validated and (if necessary) amended settings
         */
        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['twitter_account'] = isset($new_instance['twitter_account']) ? $new_instance['twitter_account'] : $old_instance['twitter_account'];
            $instance['twitter_url'] = isset($new_instance['twitter_url']) ? $new_instance['twitter_url'] : $old_instance['twitter_url'];
            $instance['twitter_theme'] = isset($new_instance['twitter_theme']) ? $new_instance['twitter_theme'] : $old_instance['twitter_theme'];
            $instance['twitter_link_color'] = isset($new_instance['twitter_link_color']) ? $new_instance['twitter_link_color'] : $old_instance['twitter_link_color'];
            return $instance;
        }

        /**
         * Displays the form for this widget on the Widgets page of the WP Admin area.
         *
         * @param array $instance An array of the current settings for this widget
         * @return void
         **/
        public function form( $instance ) {
            $twitter_account = isset($instance['twitter_account']) ? $instance['twitter_account'] : __('todoapuestas', 'epic');
            $twitter_url = isset($instance['twitter_url']) ? esc_url($instance['twitter_url']) : esc_url(get_theme_mod('epic_social_twitter'));
            $twitter_theme = isset($instance['twitter_theme']) ? $instance['twitter_theme'] : 'light';
            $twitter_link_color = isset($instance['twitter_link_color']) ? $instance['twitter_link_color'] : '#111111';
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'twitter_account' ); ?>"><?php _e( 'Cuenta de Twitter', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_account' ); ?>" name="<?php echo $this->get_field_name( 'twitter_account' ); ?>" value="<?php echo $twitter_account;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'twitter_url' ); ?>"><?php _e( 'Escribir la url de la pagina en Twitter', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_url' ); ?>" name="<?php echo $this->get_field_name( 'twitter_url' ); ?>" value="<?php echo $twitter_url;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'twitter_theme' ); ?>"><?php _e( 'Seleccionar el color del bloque', 'epic' ); ?>:</label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'twitter_theme' ); ?>" name="<?php echo $this->get_field_name( 'twitter_theme' ); ?>">
                    <option value="light" <?php if(strcmp($twitter_theme, 'light') == 0):?>selected="selected"<?php endif; ?>>Claro</option>
                    <option value="dark" <?php if(strcmp($twitter_theme, 'dark') == 0):?>selected="selected"<?php endif; ?>>Oscuro</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'twitter_link_color' ); ?>"><?php _e( 'Color para enlaces', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_link_color' ); ?>" name="<?php echo $this->get_field_name( 'twitter_link_color' ); ?>" value="<?php echo $twitter_link_color;?>"/>
            </p><?php
        }
    }
}
