<?php
if(!class_exists('Epic_Social_Links_Widget')){
    class Epic_Social_Links_Widget extends WP_Widget {

        /**
         * Constructor.
         *
         * @return Epic_Social_Links_Widget
         */
        public function __construct() {
            parent::__construct( 'widget_epic_social_links', __( 'Social Links', 'epic' ), array(
                    'classname'   => 'widget_epic_social_links',
                    'description' => __( 'Use this widget to share in social networks.', 'epic' ),
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
            $instance['title'] = isset($new_instance['title'])?$new_instance['title']:$old_instance['title'];
            $instance['text'] = isset($new_instance['text'])?$new_instance['text']:$old_instance['text'];
            $instance['display_in'] = isset($new_instance['display_in'])?$new_instance['display_in']:$old_instance['display_in'];
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
            $title = isset($instance['title']) ? $instance['title']: __('Go Social', 'epic');
            $text = isset($instance['text']) ? $instance['text'] : __('Stay in touch with us', 'epic');
            $display_in = isset($instance['display_in']) ? $instance['display_in'] : 'footer';?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Block title', 'epic' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title;?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Description text', 'epic' ); ?>:</label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text;?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'display_in' ); ?>"><?php _e( 'Display in?', 'epic' ); ?>:</label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'display_in' ); ?>" name="<?php echo $this->get_field_name( 'display_in' ); ?>">
                    <option value="footer" <?php echo $display_in == 'footer' ? 'selected="selected"' : ''; ?>><?php _e( 'Footer area', 'epic' ); ?></option>
                    <option value="sidebar" <?php echo $display_in == 'sidebar' ? 'selected="selected"' : ''; ?>><?php _e( 'Sidebar area', 'epic' ); ?></option>
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
            $display_in = $instance['display_in'];
            if(array_key_exists('before_widget', $args)) echo $args['before_widget'];

            $query = array('post_type' => 'social-link', 'orderby' => 'rand');
            $social_links = new WP_Query($query);

            switch($display_in){
                case "sidebar": // sidebar
                    $this->content_sidebar($instance, $social_links);
                    break;
                default: // footer
                    if(array_key_exists('before_title', $args)) echo $args['before_title'];
                    echo $instance['title'];
                    if(array_key_exists('after_title', $args)) echo $args['after_title'];
                    $this->content_footer($instance, $social_links);
                    break;
            }
            if(array_key_exists('after_widget', $args)) echo $args['after_widget'];
            wp_reset_query();
        }

        private function content_footer($instance, $social_links){
            global $post; ?>
            <div class="content social">
            <?php if($social_links->have_posts()): ?>
                <p><?php echo $instance['text']; ?>:</p>
                <ul>
                <?php while($social_links->have_posts()):?>
                    <?php $social_links->the_post(); $social_link = $post; ?>
                    <?php $social_link_display_in = get_post_meta($social_link->ID, '_social_link_display_in', true); ?>
                    <?php if(in_array("footer", $social_link_display_in)):?>
                        <?php $social_link_url = get_post_meta($social_link->ID, '_social_link_url_href', true); ?>
                        <?php $social_link_url_target = get_post_meta($social_link->ID, '_social_link_url_target', true); ?>
                        <?php $social_link_icon = get_post_meta($social_link->ID, '_social_link_icon', true); ?>
                        <li>
                            <a href="<?php echo esc_url($social_link_url); ?>" target="<?php echo $social_link_url_target; ?>">
                                <i class="fa fa-<?php echo $social_link_icon; ?>"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endwhile; ?>
                </ul>
                <div class="clearfix"></div>
            <?php else: ?>
                <p><?php _e('No social links available', 'epic')?></p>
            <?php endif; ?>
            </div><?php
        }

        private function content_sidebar($instance, $social_links){
            global $post; ?>
            <!-- Social Links -->
            <h3 class="hl"><?php echo $instance['title']; ?></h3>
            <hr>
            <div class="social-icons social-icons-default">
            <?php if($social_links->have_posts()): ?>
                <ul>
                <?php while($social_links->have_posts()):?>
                    <?php $social_links->the_post(); $social_link = $post; ?>
                    <?php $social_link_display_in = get_post_meta($social_link->ID, '_social_link_display_in', true); ?>
                    <?php if(in_array("sidebar", $social_link_display_in)):?>
                        <?php $social_link_url = get_post_meta($social_link->ID, '_social_link_url_href', true);?>
                        <?php $social_link_url_target = get_post_meta($social_link->ID, '_social_link_url_target', true);?>
                        <?php $social_link_icon = get_post_meta($social_link->ID, '_social_link_icon', true);?>
                        <li class="<?php echo $social_link_icon; ?>">
                            <a href="<?php echo esc_url($social_link_url); ?>" target="<?php echo $social_link_url_target; ?>">
                                <i class="fa fa-<?php echo $social_link_icon; ?>"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endwhile; ?>
                </ul>
                <div class="clearfix"></div>
            <?php else: ?>
                <p><?php _e('No social links available', 'epic')?></p>
            <?php endif; ?>
            </div>
        <?php
        }
    }
}
