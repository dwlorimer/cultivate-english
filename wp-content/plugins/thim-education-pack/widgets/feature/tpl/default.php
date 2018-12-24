<?php
$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
$content = ! empty( $instance['content'] ) ? $instance['content'] : '';

echo '<div class="thim-feature">';
	if($title) {
		echo '<h3 class="title">' . $title . ' </h3>';
	}
	if($content) {
		echo '<p class="description">' . $content . ' </p>';
	}
echo '</div>';
?>