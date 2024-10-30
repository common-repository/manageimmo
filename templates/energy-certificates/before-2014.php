<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$min_pos = -62;
$max_pos = 188;

$range_total = absint( $min_pos ) + $max_pos;
$range_piece = $range_total / 400;

$thermal_characteristic = absint( get_post_meta( get_the_ID(), 'thermal_characteristic', true ) );

$position = $min_pos + $range_piece * $thermal_characteristic;

if( $position > $max_pos ) {
	$position = $max_pos;
} elseif( $position < $min_pos ) {
	$position = $min_pos;
}

?>

<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 257.5 55" style="enable-background:new 0 0 257.5 55;" xml:space="preserve">
	<style type="text/css">
		.st0{fill:url(#SVGID_1_);}
		.st1{fill:url(#SVGID_2_);}
		.st4{font-size:7px;}
		.st2{fill:#7A7A7A;}
		.st3{font-family:'ArialMT',sans-serif;}
		.st5{font-family:'Arial-BoldMT',sans-serif;font-weight:bolder;}
	</style>
	<g>
		<linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="0" y1="49.9997" x2="257.5121" y2="49.9997">
			<stop offset="0" style="stop-color:#5FB95A"></stop>
			<stop offset="0.5" style="stop-color:#EBC900"></stop>
			<stop offset="1" style="stop-color:#EC2830"></stop>
		</linearGradient>
		<path class="st0" d="M257.5,45H0v2c0,4.4,3.6,8,8,8h241.5c4.4,0,8-3.6,8-8V45z"></path>

		<linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="0" y1="38.0041" x2="257.5121" y2="38.0041" gradientTransform="matrix(1 0 0 -1 0 66.0001)">
			<stop offset="0" style="stop-color:#5FB95A"></stop>
			<stop offset="0.5" style="stop-color:#EBC900"></stop>
			<stop offset="1" style="stop-color:#EC2830"></stop>
		</linearGradient>
		<path class="st1" d="M257.5,33H0v-2c0-4.4,3.6-8,8-8h241.5c4.4,0,8,3.6,8,8V33z"></path>
	</g>
	<g>
		<g>
			<text transform="matrix(1 0 0 1 -1.373291e-04 41.7064)" class="st2 st3 st4">0</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 29.0139 41.7064)" class="st2 st3 st4">50</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 58.0274 41.7064)" class="st2 st3 st4">100</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 88.988 41.7064)" class="st2 st3 st4">150</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 119.9482 41.7064)" class="st2 st3 st4">200</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 150.9085 41.7064)" class="st2 st3 st4">250</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 181.8687 41.7064)" class="st2 st3 st4">300</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 212.829 41.7064)" class="st2 st3 st4">350</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 241.7453 41.7064)" class="st2 st3 st4">400+</text>
		</g>
	</g>
	<g>
		<g>
			<text transform="matrix(1 0 0 1 <?php echo esc_attr( $thermal_characteristic > 110 ) ? 0 : $position + 75 ?> 6.0071)" class="st2 st3 st4 epass-svg-label">
				<?php _ex( 'Energy consumption', 'certificate energy consumption', 'manageimmo' ); ?>
			</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 <?php echo esc_attr( $thermal_characteristic > 110 ) ? 0 : $position + 75 ?> 14.8298)"><tspan x="0" y="0" class="st2 st5 st4 epass-svg-value"><?php echo esc_html( $thermal_characteristic ); ?></tspan><tspan class="st2 st3 st4 epass-svg-unit"> kWh/(m²·a)</tspan></text>
		</g>
	</g>
	<g>
		<polygon transform="matrix(1 0 0 1 <?php echo esc_attr( $position ); ?> 0)" class="st2" points="70.3,12.3 67.2,12.3 67.2,0 62.4,0 62.4,12.3 59.3,12.3 64.8,21 	"></polygon>
	</g>
</svg>