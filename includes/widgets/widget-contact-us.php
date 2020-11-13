<?php
if(!class_exists('Epic_Contact_Us_Widget')){
    class Epic_Contact_Us_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Contact_Us_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_contact_us', __( 'Contact Us', 'epic' ), array(
                    'classname'   => 'widget_epic_contact_us',
                    'description' => __( 'Use this widget to contact us.', 'epic' ),
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
            $instance['phone'] = isset($new_instance['phone']) ? $new_instance['phone'] : $old_instance['phone'];
            $instance['fax'] = isset($new_instance['fax']) ? $new_instance['fax'] : $old_instance['fax'];
            $instance['email'] = isset($new_instance['email']) ? $new_instance['email'] : $old_instance['email'];
            $instance['address'] = isset($new_instance['address']) ? $new_instance['address'] : $old_instance['address'];
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
            $title = isset($instance['title']) ? $instance['title'] : __('Contact Us', 'epic');
            $phone = isset($instance['phone']) ? $instance['phone'] : get_theme_mod('epic_contact_phone');
            $fax = isset($instance['fax']) ? $instance['fax'] : get_theme_mod('epic_contact_fax');
            $email = isset($instance['email']) ? $instance['email'] : get_theme_mod('epic_contact_email');
            $address = isset($instance['address']) ? $instance['address'] : get_theme_mod('epic_contact_address');?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Block title', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e( 'Phone', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $phone;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'fax' ); ?>"><?php _e( 'Fax', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'fax' ); ?>" name="<?php echo $this->get_field_name( 'fax' ); ?>" value="<?php echo $fax;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Email', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $email;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e( 'Address', 'epic' ); ?>:</label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>"><?php echo $address;?></textarea>
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
        public function widget( $args, $instance ) { ?>
            <!-- Contact Us --><?php
            if(array_key_exists('before_widget', $args)) echo $args['before_widget'];
            if(array_key_exists('before_title', $args)) echo $args['before_title'];
            echo $instance['title'];
            if(array_key_exists('after_title', $args)) echo $args['after_title']; ?>
            <div class="content">
                <p>
                    <?php echo $instance['address']; ?><br />
                    Phone: +<?php echo $instance['phone']; ?><br />
                    Fax: +<?php echo $instance['fax']; ?><br />
                    Email: <a href="mailto:<?php echo $instance['email']; ?>"><?php echo $instance['email']; ?></a>
                </p>
            </div>
            <?php
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
        }
    }
}
