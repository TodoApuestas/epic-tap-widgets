<?php
if(!class_exists('Epic_Search_Form_Widget')){
    class Epic_Search_Form_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Search_Form_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_search_form', __( 'Buscar', 'epic' ), array(
                    'classname'   => 'widget_epic_search_form',
                    'description' => __( 'Use este widget para mostrar un formulario de busqueda.', 'epic' ),
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
            if(array_key_exists('before_widget', $args)) echo $args['before_widget'];
            get_template_part("searchform");
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
        }
    }
}
