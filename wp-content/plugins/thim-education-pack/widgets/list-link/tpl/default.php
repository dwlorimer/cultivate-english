<?php
$style = '';
if ( $instance['style'] <> '' ) {
	$style = $instance['style'];
}

echo '<ul class="list-link '. esc_attr($style) .'">';
foreach ( $instance['list-link'] as $i => $list_link ) {
	echo '<li class="item-link">';
	echo '<a href="' . $list_link['link'] . '" title="' . $list_link['title'] . '">' . $list_link['title'] . ' </a>';
	echo '</li>';
}
echo '</ul>';
?>