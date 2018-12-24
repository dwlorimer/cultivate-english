<?php
/**
 * Section Advance features
 *
 */

// Feature: Open Graph Meta
thim_customizer()->add_field(
	array(
		'type'     => 'switch',
		'id'       => 'feature_open_graph_meta',
		'label'    => esc_html__( 'Open Graph Meta Tags', 'education-pack' ),
		'tooltip'  => esc_html__( 'Turn on to enable open graph meta tags which is mainly used when sharing pages on social networking sites like Facebook.', 'education-pack' ),
		'section'  => 'advanced',
		'default'  => true,
		'priority' => 30,
		'choices'  => array(
			true  => esc_html__( 'On', 'education-pack' ),
			false => esc_html__( 'Off', 'education-pack' ),
		),
	)
);

// Feature: Toolbar Color For Android
thim_customizer()->add_field(
	array(
		'type'     => 'switch',
		'id'       => 'feature_google_theme',
		'label'    => esc_html__( 'Google Theme', 'education-pack' ),
		'tooltip'  => esc_html__( 'Turn on to set the toolbar color in Chrome for Android.', 'education-pack' ),
		'section'  => 'advanced',
		'default'  => false,
		'priority' => 50,
		'choices'  => array(
			true  => esc_html__( 'On', 'education-pack' ),
			false => esc_html__( 'Off', 'education-pack' ),
		),
	)
);

// Feature: Google Theme Color
thim_customizer()->add_field(
	array(
		'type'            => 'color',
		'id'              => 'feature_google_theme_color',
		'label'           => esc_html__( 'Google Theme Color', 'education-pack' ),
		'section'         => 'advanced',
		'default'         => '#333333',
		'priority'        => 60,
		'alpha'           => true,
		'active_callback' => array(
			array(
				'setting'  => 'feature_google_theme',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);