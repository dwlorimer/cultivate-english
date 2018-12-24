<?php
echo '<div class="thim-brands owl-carousel owl-theme">';
foreach ( $instance['thim-brands'] as $i => $thim_brands ) {

	echo '<div class="item-brands">';
	echo '<a href="'. $thim_brands['link'] .'" title="'. $thim_brands['title'] .'">';
	echo '<img src ="' . wp_get_attachment_url($thim_brands['image']) . '" alt= "'. $thim_brands['title'] .'" title="'. $thim_brands['title'] .'" />';
	echo '</a>';
	echo '</div>';
}
echo '</div>';
?>