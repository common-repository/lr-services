<?php
add_shortcode( 'LRSERVICES', 'lr_service_shortcode_style' );

function lr_service_shortcode_style($atts) {
	ob_start();
	extract( shortcode_atts( array (
        'limit' => '-1',
    ), $atts ) );
	
	if(isset($atts['limit'])){
		$limit = $atts['limit'];
	}else{
		$limit = -1;
    }
	
$lr_service_custom_css = get_option('lr_service_custom_css');	
?>
<div class="container">
<style>.lrservice{padding-bottom:20px;}
<?php echo $lr_service_custom_css; ?>
</style>
<div class="row">
<?php
$args = array('post_type' => 'lrservice', 'posts_per_page' => $limit, 'order' => 'DESC' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
$service_icon = get_post_meta( get_the_id(), 'service_icon', true );

$column = get_option('lr_service_layout');
$readmore = get_option('lr_rmore_layout');
?>
<div class="col-md-<?php echo $column['lr_service_layout']; ?> col-sm-6 <?php echo get_post_type(); ?>">
<div class="serviceBox">
<div class="service-icon"><i class="fa <?php echo $service_icon; ?>"></i></div>
<h3 class="title"><?php the_title(); ?></h3>
<p class="description"><?php echo substr(get_the_content(), 0, 100); ?>...</p>
<?php if($readmore['lr_rmore_layout'] == 1):?>
<a href="<?php the_permalink(); ?>" class="read-more">read more</a>
<?php endif; ?>
</div>
</div>
<?php endwhile; wp_reset_query();?>      
</div>
</div>
<?php 
return ob_get_clean();
}