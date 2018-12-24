<?php
echo '<div class="thim-slider"><ul class="slides">';
foreach ( $instance['thim-slider'] as $i => $thim_slider ) {
	echo '<li class="item-link">';
	echo '<img src ="' . wp_get_attachment_url($thim_slider['image']) . '" alt= "" />';
	echo '<div class="slider-content">';
	echo '<div class="container">';
	echo '<div class="slider-content-inner">';
	echo '<h3 class="title">' . $thim_slider['title'] . '</h3>';
	echo '<div class="description">' . $thim_slider['description'] . '</div>';
	echo '<a class="slider-button" href="' . $thim_slider['link'] . '" title="">' . $thim_slider['button'] . ' </a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</li>';
}
echo '</ul></div>';
?>