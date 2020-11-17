<?php
global $post;

$tipo_publicacion = get_post_meta($post->ID, '_post_tipo_publicacion', true);
$tipster_id = $evento = $pronostico = $cuota = $stake = $casa = $fecha = $hora = $resultado = null;
$pronostico_pago = false;
$suscripcion_activa = false;

$tipster_id = get_post_meta($post->ID, '_pick_tipster', true);
$evento =  wp_specialchars_decode(get_post_meta($post->ID, '_pick_evento', true), ENT_QUOTES);
$pronostico = wp_specialchars_decode(get_post_meta($post->ID, '_pick_pronostico', true), ENT_QUOTES);
$cuota = get_post_meta($post->ID, '_pick_cuota', true);
$stake = get_post_meta($post->ID, '_pick_stake', true);
$casa = sanitize_key(get_post_meta($post->ID, '_pick_casa_apuesta', true));
$bookies = get_option('TAP_BOOKIES');
$fecha = get_post_meta($post->ID, '_pick_fecha_evento', true);
$hora = get_post_meta($post->ID, '_pick_hora_evento', true);
$pronostico_pago = false;
$suscripcion_activa = true;
$resultado = get_post_meta($post->ID, '_pick_resultado', true);
$info = 'completa';
if($pronostico_pago && !$suscripcion_activa){
    $resultado = 'pendiente';
    $info = 'resumen';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class("entry-summary"); ?>>
	<header class="entry-header row">
		<div class="col-xs-12">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<div class="entry-meta">
				<?php epic_posted_on(); ?>
			</div><!-- .entry-meta -->
		</div>
	</header><!-- .entry-header -->

	<section class="entry-content row">
        <div class="col-xs-12 col-md-5">
	        <div class="pick-thumbnail">
            <?php $default_image = get_theme_mod('epic_blog_default_image') ?>
            <?php $image = sprintf('<img src="%s" class="img-responsive center-block" alt="%s" style="height: 263px;" loading="lazy">', $default_image, get_the_title())?>
            <?php if(has_post_thumbnail()): ?>
                <?php $image = get_the_post_thumbnail(null, 'post-266x263', array( 'class' => 'img-responsive center-block', 'alt' => get_the_title(), 'loading' => 'lazy'))?>
            <?php endif; ?>
            <?php echo sprintf('<a href="%s" rel="bookmark" title="%s">%s</a>', esc_url( get_permalink() ), get_the_title(), $image)?>
	        </div>
        </div>
        <div class="col-xs-12 col-md-7">
            <div class="pick-content color-<?php echo $resultado?> list-<?php echo $info; ?>">
	            <?php if(!empty($evento)):?>
	            <p><?php echo sprintf(__('<span>Evento</span> --- %s'), $evento); ?></p>
	            <?php endif;?>

	            <?php if(!empty($pronostico)):?>
	            <p><?php echo sprintf(__('<span>Pronostico</span> --- %s'), $pronostico); ?></p>
	            <?php endif;?>

	            <?php if(!empty($cuota)):?>
                <p><?php echo sprintf(__('<span>Cuota</span> --- %s'), $cuota); ?></p>
	            <?php endif;?>

	            <?php if(!empty($stake)):?>
                <p><?php echo sprintf(__('<span>Stake</span> --- %s'), $stake); ?></p>
	            <?php endif;?>
    
                <?php
                $bookie = $bookies[ $casa ][ "nombre" ];
                try{
	                $bookie = apply_filters('alink_tap_execute_linker', '<span>' . $bookies[ $casa ][ "nombre" ] . '</span>', false, true );
                }catch (\Exception $e){
	                $bookie = $bookies[ $casa ][ "nombre" ]; // si hay algun problema volver al valor inicial
                } ?>
	            <?php if(!empty($bookie)):?>
                <p><?php echo sprintf( __( '<span>Bookie</span> --- %s' ), $bookie ); ?></p>
	            <?php endif;?>

	            <?php if(!empty($fecha)):?>
                <p><?php echo sprintf(__('<span>Fecha</span> --- %s'), $fecha . ' ' . $hora); ?></p>
	            <?php endif;?>

	            <label class="text-center bold color-<?php echo $resultado?>">
		            <?php switch($resultado):
			            case 'acierto':?>
				            <span class="fa fa-smile-o fa-5x"></span><?php
				            break;
			            case 'fallo':?>
				            <span class="fa fa-frown-o fa-5x"></span><?php
				            break;
			            default: // nulo ?>
				            <span class="fa fa-meh-o fa-5x"></span><?php
				            break;
		            endswitch;?></label>
                <p>&nbsp;</p>
                <p class="text-uppercase read-more"><?php echo sprintf(__('<a href="%s" rel="bookmark"><span>Explicaci&oacute;n del pick</span></a>'), esc_url( get_permalink() ))?></p>
            </div>
            <ul class="nav social-share list-inline">
                <li>
                    <div class="facebook text-center" data-url="<?php echo esc_url( get_permalink() ) ?>" data-text="<?php echo $post->post_title; ?>"></div>
                </li>
                <li>
                    <div class="googleplus text-center" data-url="<?php echo esc_url( get_permalink() ) ?>" data-text="<?php echo $post->post_title; ?>"></div>
                </li>
                <li>
                    <div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-text="<?php echo $post->post_title; ?>" data-via="todoapuestas" data-lang="es" data-size="large" data-dnt="true">Twittear</a></div>
                </li>
            </ul>
		</div>

	</section><!-- .entry-content -->

</article><!-- #post-## -->