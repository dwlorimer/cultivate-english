<?php
/**
 * Section Copyright
 *
 * @package Thim_Starter_Theme
 */

thim_customizer()->add_section(
	array(
		'id'       => 'copyright',
		'title'    => esc_html__( 'Copyright', 'education-pack' ),
		'panel'    => 'footer',
		'priority' => 50,
	)
);

// Copyright Background Color
thim_customizer()->add_field(
	array(
		'id'        => 'copyright_background_color',
		'type'      => 'color',
		'label'     => esc_html__( 'Background Color', 'education-pack' ),
		'tooltip'   => esc_html__( 'Allows you to choose background color for your copyright area. ', 'education-pack' ),
		'section'   => 'copyright',
		'default'   => '#222',
		'priority'  => 15,
		'alpha'     => true,
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'choice'   => 'color',
				'element'  => 'footer#colophon .copyright-area',
				'property' => 'background-color',
			)
		)
	)
);

// Select All Fonts For Main Menu
thim_customizer()->add_field(
	array(
		'id'        => 'copyright_fonts',
		'type'      => 'typography',
		'label'     => esc_html__( 'Fonts', 'education-pack' ),
		'tooltip'   => esc_html__( 'Allows you to select all font font properties for copyright. ', 'education-pack' ),
		'section'   => 'copyright',
		'priority'  => 10,
		'default'   => array(
			'font-family'    => 'Open Sans',
		),
		'transport' => 'postMessage',
	)
);

// Copyright Text Color, Link Color, Link Hover Colo
thim_customizer()->add_field(
	array(
		'type'      => 'multicolor',
		'id'        => 'font_copyright_color',
		'label'     => esc_html__( 'Colors', 'education-pack' ),
		'section'   => 'copyright',
		'priority'  => 20,
		'choices'   => array(
			'text'  => esc_html__( 'Text', 'education-pack' ),
			'link'  => esc_html__( 'Link', 'education-pack' ),
			'hover' => esc_html__( 'Hover', 'education-pack' ),
		),
		'default'   => array(
			'text'  => '#fff',
			'link'  => '#fff',
			'hover' => '#3498db',
		),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'choice'   => 'text',
				'element'  => 'footer#colophon .copyright-area .copyright-content',
				'property' => 'color',
			),
			array(
				'choice'   => 'link',
				'element'  => 'footer#colophon .copyright-area .copyright-content a',
				'property' => 'color',
			),
			array(
				'choice'   => 'hover',
				'element'  => 'footer#colophon .copyright-area .copyright-content a:hover',
				'property' => 'color',
			),
		),
	)
);