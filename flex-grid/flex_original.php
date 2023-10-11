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
$container_id = '';
$container_classes = 'grid-container';
$grid_classes = 'flex-grid';
$grid_item_classes = 'grid-item';
$formatted_name = '';
$sizes_attr = "";
$link_to = '';
$grid_type = get_field("grid_type");


// Create id attribute allowing for custom "anchor" value.
$id = 'flex-grid-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
if (!empty($block['className'])) {
	$grid_classes .= ' ' . $block['className'];
}


//TEAM GRID
if ($grid_type === "team") :
	$container_id .= "team";
	$container_classes .= " team-grid";
	$grid_classes .= ' team';
	$grid_item_classes .= ' team-member';
	$sizes_attr = "(min-width: 1100px) 33vw, (min-width: 600px) 49vw,  100vw";
endif;
?>

<?php if (is_admin()) : ?>
	<p class="error-msg select-msg"><em>Select, then edit on toolbar on right</em></p>
<?php endif; ?>
<section class="<?= esc_attr($container_classes) ?>" <?php if ($container_id) : ?>id="<?= esc_attr($container_id) ?>" <?php endif; ?>>




	<?php
	/** Start the grid logic */
	if (have_rows('flex_grid')) :
	?>
		<div class="<?= esc_attr($grid_classes) ?>">
			<?php
			while (have_rows('flex_grid')) : the_row();
				/** INIT ALL VALUES */
				$item_image = null;
				$srcset = null;
				$fallback_src = null;
				$alt = null;


				$item_image = get_sub_field('image');
				$item_primary_label = get_sub_field('primary_label');
				$item_secondary_label = get_sub_field('secondary_label');

				if ($grid_type === "team") $item_secondary_label = get_sub_field('secondary_label');
				/** Fields for Team Page  */
				if ($grid_type === "team") :
					$formatted_name = accent2ascii(strtolower(str_replace(' ', '-', str_replace('.', '', $item_primary_label))));
					$item_description = get_sub_field('description');
				endif;


				// Image Info
				if ($item_image) {
					$alt = $item_image['alt'];
					$image_id = $item_image['ID'];
					// Generate Srcset
					$srcset = wp_get_attachment_image_srcset($image_id);
					$fallback_src = wp_get_attachment_image_url($image_id, 'medium_large');

					// Image Focal Point
					$focal_point = get_field('set_focal_point', $image_id);
					switch ($focal_point) {
						case 'custom':
							$focal_point = get_field('custom_focal_point', $image_id);
							break;

						case NULL:
						case '50-50':
							$focal_point = NULL;
							break;

						default:
							$focal_point = str_replace('-', '% ', $focal_point) . '%';
							break;
					}
					$focus_style = $focal_point ? "style=\"object-position: $focal_point; font-family: 'object-fit: cover; object-position: $focal_point;';\"" : NULL;
				}
			?>

				<div class="<?= esc_attr($grid_item_classes) ?> <?= $link_to ?>" <?php if ($formatted_name) : ?> id="<?= esc_attr($formatted_name) ?>" <?php endif; ?>>
					<figure class="image-holder">
						<div class="ratio">
							<?php if ($srcset == null || $fallback_src == null && is_admin()) : ?>
								<p class="error-msg"><em>Image Missing</em></p>
							<?php elseif (is_admin()) : ?>
								<img alt='<?= $alt; ?>' src='<?= esc_attr($fallback_src); ?>' />
							<?php elseif ($grid_type == "navigation") : ?>
								<img data-sizes='<?= $sizes_attr ?>' alt='<?= $alt; ?>' data-srcset='<?= esc_attr($srcset); ?>' data-src='<?= esc_attr($fallback_src); ?>' />
							<?php else : ?>
								<img <?= $focus_style ?> onload="imageLoaded(this)" sizes='<?= $sizes_attr ?>' alt='<?= $alt; ?>' srcset='<?= esc_attr($srcset); ?>' src='<?= esc_attr($fallback_src); ?>' />
							<?php endif; ?>
						</div>
					</figure>

					<?php
					/** TEAM LABLE */
					if ($grid_type === "team") :
					?>
						<div class="title-holder">
							<?php if (!$item_primary_label && is_admin()) : ?>
								<p class="error-msg"><em>Primary Lable Missing</em></p>
							<?php else : ?>
								<h3><?= $item_primary_label ?></h3>
							<?php endif; ?>

							<?php if (!$item_secondary_label && is_admin()) : ?>
								<p class="error-msg"><em>Secondary Lable Missing</em></p>
							<?php else : ?>
								<h4><?= $item_secondary_label ?></h4>
							<?php endif; ?>
						</div>
						<div class="bio">
							<p><?= $item_description ?></p>
							<button class="">Read Bio</button>
						</div>
					<?php
						/** END TEAM LABLE */
					endif;
					?>
				</div>
				<?php
				?>
			<?php
				/** END ITEM LOOP */
			endwhile;
			?>
			<div class="grid-item placeholder"></div>
			<div class="grid-item placeholder"></div>
		</div>
	<?php
	else : ?>
		<p class="error-msg"><em>Add rows via toolbar on right</em></p>
	<?php
		/** END GRID LOOP*/
	endif;
	?>
</section>