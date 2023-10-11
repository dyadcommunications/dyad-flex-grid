<?php

/**
 * 
 * Flex Grid Block
 * Displays a grid that uses Flexbox.
 *
 */

/** Init all vars */
$post_id = get_the_ID();
$parent_id = wp_get_post_parent_id($post_id);
$post_type = get_post_type();
$post_title = accent2ascii(get_the_title($post_id));
$parent_title = accent2ascii(get_the_title($parent_id));
$container_id = $post_title . "-grid-container";
$container_classes = 'dyad-flex-grid-container';


// Create id attribute allowing for custom "anchor" value.
$id = 'flex-grid-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
if (!empty($block['className'])) {
	$grid_classes .= ' ' . $block['className'];
}

$allowed_blocks = array('acf/dyad-grid-item');

$template = array(
	array(
		'acf/dyad-grid-item',
		array(
			'placeholder' => __('locale'),
			'align'       => 'center',
			'level'       => '2',
		),

	)
);

?>

<section class="<?= esc_attr($container_classes) ?>" id="<?= esc_attr($container_id) ?>">
	<InnerBlocks className="dyad-flex-grid-innerblock flex-grid acf-innerblocks-container" orientation="horizontal" allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>" template="<?php echo esc_attr(wp_json_encode($template)); ?>" />
</section>