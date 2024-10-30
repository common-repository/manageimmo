<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$thermal_characteristic   = floatval( get_post_meta( get_the_ID(), 'thermal_characteristic', true ) );
$energy_certificate_class = get_post_meta( get_the_ID(), 'energy_certificate_class', true );
$position                 = $thermal_characteristic - 64;

if( $thermal_characteristic > 264 ) {
	$position = 264 - 64;
}

?>

<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 270 65" style="enable-background:new 0 0 270 65;" xml:space="preserve">
	<style type="text/css">
		.st0{fill:url(#SVGID_1_);}
		.st1{fill:url(#SVGID_2_);}
		.st2{fill:none;stroke:#FFFFFF;stroke-width:1.1339;stroke-miterlimit:10;}
		.st3{fill:#7A7A7A;}
		.st4{font-family:'ArialMT',sans-serif;}
		.st5{font-size:7px;}
		.st6{opacity:0.75;}
		.st7{fill:#FFFFFF;}
		.st8{font-size:9px;}
		.st9{display:none;}
		.st10{display:inline;fill:#FFFFFF;}
		.st11{font-family:'Arial-BoldMT',sans-serif;font-weight:bolder;}
		.st12{font-size:13px;}
	</style>

	<g>
		<linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="0" y1="60" x2="270" y2="60">
			<stop offset="0" style="stop-color:#5FB95A"></stop>
			<stop offset="0.5" style="stop-color:#EBC900"></stop>
			<stop offset="1" style="stop-color:#EC2830"></stop>
		</linearGradient>
		<path class="st0" d="M270,55H0v2c0,4.4,3.6,8,8,8h254c4.4,0,8-3.6,8-8V55z"></path>

		<linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="0" y1="32.9961" x2="270" y2="32.9961">
			<stop offset="0" style="stop-color:#5FB95A"></stop>
			<stop offset="0.5" style="stop-color:#EBC900"></stop>
			<stop offset="1" style="stop-color:#EC2830"></stop>
		</linearGradient>
		<path class="st1" d="M262,23H8c-4.4,0-8,3.6-8,8v12h270V31C270,26.6,266.4,23,262,23z"></path>
	</g>

	<g>
		<line class="st2" x1="30" y1="23" x2="30" y2="43"></line>
		<line class="st2" x1="50" y1="23" x2="50" y2="43"></line>
		<line class="st2" x1="75" y1="23" x2="75" y2="43"></line>
		<line class="st2" x1="100" y1="23" x2="100" y2="43"></line>
		<line class="st2" x1="130" y1="23" x2="130" y2="43"></line>
		<line class="st2" x1="160" y1="23" x2="160" y2="43"></line>
		<line class="st2" x1="200" y1="23" x2="200" y2="43"></line>
		<line class="st2" x1="250" y1="23" x2="250" y2="43"></line>
	</g>
	<g>
		<g>
			<text transform="matrix(1 0 0 1 0.2321 51.7067)" class="st3 st4 st5">0</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 25.2088 51.7067)" class="st3 st4 st5">25</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 50.1856 51.7067)" class="st3 st4 st5">50</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 75.1624 51.7067)" class="st3 st4 st5">75</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 100.1393 51.7067)" class="st3 st4 st5">100</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 125.1161 51.7067)" class="st3 st4 st5">125</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 150.0929 51.7067)" class="st3 st4 st5">150</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 175.0697 51.7067)" class="st3 st4 st5">175</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 200.0465 51.7067)" class="st3 st4 st5">200</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 225.0233 51.7067)" class="st3 st4 st5">225</text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 250.0002 51.7067)" class="st3 st4 st5">250+</text>
		</g>
	</g>
	<g>
		<g class="<?php echo 'H' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 256.7514 36.719)" class="st7 st4 st8">H</text>
		</g>
		<g class="<?php echo 'G' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 221.4995 36.719)" class="st7 st4 st8">G</text>
		</g>
		<g class="<?php echo 'F' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 177.2526 36.719)" class="st7 st4 st8">F</text>
		</g>
		<g class="<?php echo 'E' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 141.9987 36.719)" class="st7 st4 st8">E</text>
		</g>
		<g class="<?php echo 'D' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 111.7504 36.719)" class="st7 st4 st8">D</text>
		</g>
		<g class="<?php echo 'C' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 84.2503 36.719)" class="st7 st4 st8">C</text>
		</g>
		<g class="<?php echo 'B' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 60.0806 36.719)" class="st7 st4 st8">B</text>
		</g>
		<g class="<?php echo 'A' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 36.9986 36.719)" class="st7 st4 st8">A</text>
		</g>
		<g class="<?php echo 'A+' === $energy_certificate_class ? 'st9' : 'st6'; ?>">
			<text transform="matrix(1 0 0 1 9.3707 36.719)" class="st7 st4 st8">A+</text>
		</g>
	</g>
	<g>
		<g>
			<g class="<?php echo 'H' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 255.4306 37.719)" class="st10 st11 st12">H</text>
			</g>
			<g class="<?php echo 'G' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 219.7238 37.719)" class="st10 st11 st12">G</text>
			</g>
			<g class="<?php echo 'F' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 176.4304 37.719)" class="st10 st11 st12">F</text>
			</g>
			<g class="<?php echo 'E' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 140.6647 37.719)" class="st10 st11 st12">E</text>
			</g>
			<g class="<?php echo 'D' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 110.3061 37.719)" class="st10 st11 st12">D</text>
			</g>
			<g class="<?php echo 'C' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 83.18 37.719)" class="st10 st11 st12">C</text>
			</g>
			<g class="<?php echo 'B' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 57.8062 37.719)" class="st10 st11 st12">B</text>
			</g>
			<g class="<?php echo 'A' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 35.3062 37.719)" class="st10 st11 st12">A</text>
			</g>
			<g class="<?php echo 'A' === $energy_certificate_class ? '' : 'st9'; ?>">
				<text transform="matrix(1 0 0 1 6.5103 37.719)" class="st10 st11 st12">A+</text>
			</g>
		</g>
	</g>
	<g>
		<g>
			<text transform="matrix(1 0 0 1 <?php echo esc_attr( $thermal_characteristic > 80 ) ? 0 : $position + 75 ?> 6.0071)" class="st3 st4 st5 epass-svg-label"><?php _e( 'Energy demand', 'manageimmo' ); ?></text>
		</g>
		<g>
			<text transform="matrix(1 0 0 1 <?php echo esc_attr( $thermal_characteristic > 80 ) ? 0 : $position + 75 ?> 14.8298)"><tspan x="0" y="0" class="st3 st11 st5 epass-svg-value"><?php echo esc_html( $thermal_characteristic ); ?></tspan><tspan class="st3 st4 st5 epass-svg-unit"> kWh/(m²·a)</tspan></text>
		</g>
	</g>
	<g>
		<polygon transform="matrix(1 0 0 1 <?php echo esc_attr( $position ); ?> 0)" class="st3" points="70.3,12.3 67.2,12.3 67.2,0 62.4,0 62.4,12.3 59.3,12.3 64.8,21 	"></polygon>
	</g>
</svg>