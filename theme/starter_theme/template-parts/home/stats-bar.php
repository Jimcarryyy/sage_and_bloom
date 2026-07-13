<?php
/**
 * Homepage stats band.
 *
 * @package starter_theme
 */

get_template_part(
	'template-parts/shared/stats-band',
	null,
	array(
		'stats'    => starter_theme_home_stats(),
		'modifier' => 'home',
		'label'    => __( 'Salon highlights', 'starter_theme' ),
	)
);
