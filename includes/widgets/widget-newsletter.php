<?php
if(!class_exists('Epic_Newsletter_Widget')) {
	class Epic_Newsletter_Widget extends WP_Widget {
		
		/**
		 * Constructor.
		 *
		 * @return Epic_Newsletter_Widget
		 */
		public function __construct() {
			parent::__construct( 'widget_epic_newsletter', __( 'Newsletter', 'epic' ), array(
				'classname'   => 'widget_epic_newsletter',
				'description' => __( 'Use este widget para subscribirse.', 'epic' ),
			) );
		}
		
		/**
		 * Deals with the settings when they are saved by the admin. Here is
		 * where any validation should be dealt with.
		 *
		 * @param array  An array of new settings as submitted by the admin
		 * @param array  An array of the previous settings
		 *
		 * @return array The validated and (if necessary) amended settings
		 **/
		public function update( $new_instance, $old_instance ) {
			$instance               = $old_instance;
			$instance['title']      = isset( $new_instance['title'] ) ? $new_instance['title'] : $old_instance['title'];
			$instance['text_info']  = isset( $new_instance['text_info'] ) ? $new_instance['text_info'] : $old_instance['text_info'];
			$instance['text_label'] = isset( $new_instance['text_label'] ) ? $new_instance['text_label'] : $old_instance['text_label'];
			
			return $instance;
		}
		
		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @param array  An array of the current settings for this widget
		 *
		 * @return void
		 **/
		public function form( $instance ) {
			$title      = isset( $instance['title'] ) ? sanitize_text_field($instance['title']) : __( 'Suscribase al boletin', 'epic' );
			$text_info  = isset( $instance['text_info'] ) ? sanitize_textarea_field($instance['text_info']) : __( 'Mantengase informado de nuestras <br> promociones y concursos!', 'epic' );
			$text_label = isset( $instance['text_label'] ) ? sanitize_textarea_field($instance['text_label']) : __( 'Escriba su direccion de e-mail:', 'epic' );
			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo del bloque', 'epic' ); ?>
                    :</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($title); ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'text_info' ); ?>"><?php _e( 'Texto informativo', 'epic' ); ?>
                    :</label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'text_info' ); ?>"
                          name="<?php echo $this->get_field_name( 'text_info' ); ?>"><?php echo esc_textarea($text_info); ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'text_label' ); ?>"><?php _e( 'Texto del campo', 'epic' ); ?>
                    :</label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'text_label' ); ?>"
                          name="<?php echo $this->get_field_name( 'text_label' ); ?>"><?php echo esc_textarea($text_label); ?></textarea>
            </p>
		<?php }
		
		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			$errors     = array();
			$email      = null;
			$result     = false;
			$newsletter = null;
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['newsletter_conditions'] ) ) {
				
				$newsletter = sanitize_key($_POST['newsletter_conditions']);

				if ( ! isset( $_POST['__newsletter'] ) || ! wp_verify_nonce( sanitize_key($_POST['__newsletter']), sprintf( 'newsletter_form_%s', $newsletter ) ) ) {
					$errors[] = __( 'Token CRSF incorrecto. Recargar la pagina para generar uno nuevo.', 'epic' );
				}
				
				$errors = apply_filters( 'epic_recaptcha_verification', $errors );
				
				$email = sanitize_email($_POST['subscribe']);
				
				$errors = $this->validate_email( $email, $errors );
				if ( ! count( $errors ) ) {
					$email  = trim( $email );
					$result = $this->send_subscribe_email( $email );
				}
				
			}
			
			if ( array_key_exists( 'before_widget', $args ) ) {
				echo $args['before_widget'];
			}
			if ( array_key_exists( 'before_title', $args ) ) {
				echo $args['before_title'];
			}
			echo $instance['title'];
			if ( array_key_exists( 'after_title', $args ) ) {
				echo $args['after_title'];
			} ?>
            <p class="text_info"><?php echo esc_html($instance['text_info']); ?></p>
            <p class="text_label"><?php echo esc_html($instance['text_label']); ?></p>
            <form id="suscribirse-al-newsletter" action="#suscribirse-al-newsletter" role="form" method="post"
                  class="form-horizontal">
                <div class="row">
                    <div class="col-xs-11">
						<?php if ( $result ): ?>
                            <div class="alert alert-success alert-dismissable">
                                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;
                                </button>
								<?php _e( 'Email enviado satisfactoriamente' ) ?>
                            </div>
						<?php endif; ?>
						<?php if ( count( $errors ) ): ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;
                                </button>
                                <p style="color: #3c3c3c;"><?php echo sprintf( _nx( 'Encontrado el siguiente error', 'Encontrados los %1$s errores siguientes', count( $errors ), 'subscribe validate error', 'epic' ), count( $errors ) ); ?>
                                    :</p>
                                <ul>
									<?php foreach ( $errors as $error ): ?>
                                        <li><?php echo esc_html($error); ?></li>
									<?php endforeach; ?>
                                </ul>
                            </div>
						<?php endif; ?>
                        <div class="input-group">
                            <label class="sr-only"
                                   for="subscribe-email"><?php _e( 'Direccion de email', 'epic' ) ?></label>
                            <input id="subscribe-email" class="form-control" type="email" value="" placeholder=""
                                   name="subscribe" required="required">
                            <span class="input-group-btn">
                                <button class="btn btn-newsletter" type="submit"><span
                                            class="fa fa-envelope-o fa-lg"></span></button>
                            </span>
                        </div>
                        <div class="checkbox">
                            <label style="color: #fefefe;">
								<?php $newsletter = sha1( date( 'Ymdhis' ) ); ?>
                                <input type="checkbox" name="newsletter_conditions" required="required"
                                       value="<?php echo esc_attr( $newsletter ); ?>">
                                <input type="hidden" name="__newsletter"
                                       value="<?php echo wp_create_nonce( sprintf( 'newsletter_form_%s', $newsletter ) ); ?>">
                                <a href="<?php echo get_page_link( get_theme_mod( 'epic_newsletter_conditions_page' ) ) ?>"
                                   target="_blank"
                                   rel="bookmark"><?php _e( 'Acepto las condiciones de la newsletter', 'epic' ); ?></a>
                            </label>
                        </div>
                        <div id="reCaptchaNewsletter" style="margin-top: 10px;"></div>
                    </div>
                </div>
            </form>
			<?php
			if ( array_key_exists( 'after_widget', $args ) ) {
				echo $args['after_widget'];
			}
			
		}

		private function validate_email($email, array $errors ){
            $email = trim($email);
            if( empty( $email ) )  {
                $errors[] = __('Por favor proporcione la direccion de correo electronico.', 'epic');
            } elseif ( false === is_email( $email ) )  {
                $errors[] = __('El correo electronico proporcionado no es valido.', 'epic');
            }

            return $errors;
        }

        private function send_subscribe_email($email){
            $emailTo = get_theme_mod('epic_contact_email');
            if (!isset($emailTo) || ($emailTo === '') ){
                $emailTo = get_option('admin_email');
            }
            $site_name = get_bloginfo('name');
            $subject = sprintf(__('[NUEVA SUBSCRIPCION DESDE %s AL BOLETIN] From "%s"', 'epic'), $site_name, $email);
            $body = sprintf(__('Correo electronico', 'epic').": %s\n\n", $email);
            $headers = 'From: '.$email.' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;

            return wp_mail($emailTo, $subject, $body, $headers);
        }
	}
}