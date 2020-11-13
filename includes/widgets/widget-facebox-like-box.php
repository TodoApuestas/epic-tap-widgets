<?php
if(!class_exists('Epic_FB_Like_Box_Widget')){
    class Epic_FB_Like_Box_Widget extends WP_Widget {
        /**
         * Constructor.
         *
         * @return Epic_FB_Like_Box_Widget
         */
        public function __construct() {
            parent::__construct( 'epic_fb_like_box_widget', __( 'Facebook Like Box', 'epic' ), array(
                    'classname'   => 'epic_fb_like_box_widget',
                    'description' => __( 'Use this widget to show Facebook Like Box.', 'epic' ),
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
            <div id="fb-root"></div>
            <div class="fb-like-box" data-href="<?php echo $instance['link_facebook']; ?>" data-width="<?php echo $instance['width']; ?>" data-height="<?php echo $instance['height']; ?>" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false" style="width:100%"></div>
            <script>(function(d, s, id) {

                    var js, fjs = d.getElementsByTagName(s)[0];

                    if (d.getElementById(id))

                        return;

                    js = d.createElement(s);

                    js.id = id;

                    js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";

                    fjs.parentNode.insertBefore(js, fjs);

                }(document, 'script', 'facebook-jssdk'));</script>
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
         **/
        public function update( $new_instance, $old_instance )
        {
            $instance = $old_instance;
            $instance['link_facebook'] = isset($new_instance['link_facebook']) ? $new_instance['link_facebook'] : $old_instance['link_facebook'];
            $instance['width'] = isset($new_instance['width']) ? $new_instance['width'] : $old_instance['width'];
            $instance['height'] = isset($new_instance['height']) ? $new_instance['height'] : $old_instance['height'];
            return $instance;
        }

        /**
         * Displays the form for this widget on the Widgets page of the WP Admin area.
         *
         * @param array $instance An array of the current settings for this widget
         * @return void
         **/
        public function form( $instance )
        {
            $link_facebook = esc_url(isset($instance['link_facebook']) ? $instance['link_facebook'] : get_theme_mod('epic_social_facebook'));
            $width = isset($instance['width']) ? (integer)$instance['width'] : 258;
            $height = isset($instance['height']) ? (integer)$instance['height'] : 300;
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'link_facebook' ); ?>"><?php _e( 'Escribir la url de pagina en Facebook', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'link_facebook' ); ?>" name="<?php echo $this->get_field_name( 'link_facebook' ); ?>" value="<?php echo $link_facebook;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Escribir el ancho del bloque', 'epic' ); ?>:</label>
                <input type="text" class="" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $width;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Escribir el alto del bloque', 'epic' ); ?>:</label>
                <input type="text" class="" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $height;?>"/>
            </p><?php
        }
    }
}
