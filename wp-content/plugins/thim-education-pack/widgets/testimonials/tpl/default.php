<?php

echo '<div class="thim-testimonials owl-carousel owl-theme">';
foreach ( $instance['thim-testimonials'] as $i => $thim_testimonials ) {
	echo '<div class="item-link">';
	echo '<div class="item-img">';
	echo '<img src ="' . wp_get_attachment_url($thim_testimonials['image']) . '" alt= "" />';
	echo '</div>';
	echo '<div class="item-content">';
	echo '<div class="description">' . $thim_testimonials['description'] . '</div>';
	echo '<div class="item-info">';
	echo '<h3 class="name">' . $thim_testimonials['name'] . '</h3>';
	echo '<span class="jobs">' . $thim_testimonials['jobs'] . ' </span>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
echo '</div>';