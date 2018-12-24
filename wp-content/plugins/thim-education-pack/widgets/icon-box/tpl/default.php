<?php
if ( $instance['icon'] == '' ) {
	$instance['icon'] = 'none';
}
if ( $instance['icon'] != 'none' ) {
	$icon = siteorigin_widget_get_icon($instance['icon']);
}
$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
$description = ! empty( $instance['description'] ) ? $instance['description'] : '';

echo '<div class="thim-icon-box">';
	if($icon) {
		echo '<div class="icon">';
		echo $icon;
		echo '</div>';
	}
	if($title) {
		echo '<h3 class="title">' . $instance['title'] . ' </h3>';
	}
	if($description) {
		echo '<p class="description">' . $instance['description'] . ' </p>';
	}
echo '</div>';
?>