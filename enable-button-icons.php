<?php
/**
 * Plugin Name:         Enable Button Icons
 * Plugin URI:          https://www.nickdiego.com/
 * Description:         Easily add icons to Button blocks.
 * Version:             0.2.0
 * Requires at least:   6.3
 * Requires PHP:        7.4
 * Author:              Nick Diego
 * Author URI:          https://www.nickdiego.com
 * License:             GPLv2
 * License URI:         https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:         enable-button-icons
 * Domain Path:         /languages
 *
 * @package enable-button-icons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue Editor scripts.
 */
function enable_button_icons_enqueue_block_editor_assets() {
	$asset_file  = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

	wp_enqueue_script(
		'enable-button-icons-editor-scripts',
		plugin_dir_url( __FILE__ ) . 'build/index.js',
		$asset_file['dependencies'],
		$asset_file['version']
	);

	wp_set_script_translations(
		'enable-button-icons-editor-scripts',
		'enable-button-icons',
		plugin_dir_path( __FILE__ ) . 'languages'
	);

}
add_action( 'enqueue_block_editor_assets', 'enable_button_icons_enqueue_block_editor_assets' );

/**
 * Enqueue Editor styles.
 */
function enable_button_icons_enqueue_block_assets() {
	if( is_admin() ){
		$asset_file  = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

		wp_enqueue_style(
			'enable-button-icons-editor-styles',
			plugin_dir_url( __FILE__ ) . 'build/editor.css'
		);
	}
}
add_action( 'enqueue_block_assets', 'enable_button_icons_enqueue_block_assets' );

/**
 * Enqueue block styles 
 * (Applies to both frontend and Editor)
 */
function enable_button_icons_block_styles() {
	wp_enqueue_block_style(
		'core/button',
		array(
			'handle' => 'enable-button-icons-block-styles',
			'src'    => plugin_dir_url( __FILE__ ) . 'build/style.css',
			'ver'    => wp_get_theme()->get( 'Version' ),
			'path'   => plugin_dir_path( __FILE__ ) . 'build/style.css',
		)
	);
}
add_action( 'init', 'enable_button_icons_block_styles' );

/**
 * Render icons on the frontend.
 *
 * @since 0.1.0
 * @param string $block_content The block content.
 * @param array  $block         The block data.
 * @return string Modified block content with icon.
 */
function enable_button_icons_render_block_button( $block_content, $block ) {
	if ( ! isset( $block['attrs']['icon'] ) && ! isset( $block['attrs']['iconName'] ) ) {
		return $block_content;
	}

	$icon                 = isset( $block['attrs']['icon'] ) ? $block['attrs']['icon'] : '';
	$icon_name            = isset( $block['attrs']['iconName'] ) ? $block['attrs']['iconName'] : 'custom';
	$position_left        = isset( $block['attrs']['iconPositionLeft'] ) ? $block['attrs']['iconPositionLeft'] : false;
	$justify_space_between = isset( $block['attrs']['justifySpaceBetween'] ) ? $block['attrs']['justifySpaceBetween'] : false;
	$has_no_icon_fill     = isset( $block['attrs']['hasNoIconFill'] ) ? $block['attrs']['hasNoIconFill'] : false;
	$icon_size            = isset( $block['attrs']['iconSize'] ) ? $block['attrs']['iconSize'] : '';
	$icon_spacing         = isset( $block['attrs']['iconSpacing'] ) ? $block['attrs']['iconSpacing'] : '';

	$icon_color_class = '';
	$icon_color       = '';
	if ( isset( $block['attrs']['iconColor'] ) ) {
		$icon_color_class = ' has-' . sanitize_html_class( $block['attrs']['iconColor'] ) . '-color';
	} elseif ( isset( $block['attrs']['customIconColor'] ) ) {
		$icon_color = 'style="color:' . esc_attr( $block['attrs']['customIconColor'] ) . ';"';
	}

	// Build inline styles for icon size and color.
	$icon_styles = array();
	$link_styles = array();

	if ( $icon_size ) {
		// Set CSS custom properties for icon sizing.
		$link_styles[] = '--icon-size:' . esc_attr( $icon_size );
	}
	if ( $icon_spacing ) {
		$link_styles[] = '--icon-spacing:' . esc_attr( $icon_spacing );
	}
	if ( isset( $block['attrs']['customIconColor'] ) ) {
		$icon_styles[] = 'color:' . esc_attr( $block['attrs']['customIconColor'] );
	}

	$icon_style_attr = ! empty( $icon_styles ) ? ' style="' . esc_attr( implode( ';', $icon_styles ) ) . '"' : '';

	// Append the icon class to the block.
	$p = new WP_HTML_Tag_Processor( $block_content );
	if ( $p->next_tag() ) {
		$p->add_class( 'has-icon__' . sanitize_html_class( $icon_name ) );
		if ( $justify_space_between ) {
			$p->add_class( 'has-justified-space-between' );
		}
		if ( $has_no_icon_fill ) {
			$p->add_class( 'has-no-icon-fill' );
		}
		// Apply custom properties to the link.
		if ( ! empty( $link_styles ) && $p->next_tag( 'a' ) ) {
			$existing_style = $p->get_attribute( 'style' );
			$new_styles     = esc_attr( implode( ';', $link_styles ) );
			$final_style    = $existing_style ? $existing_style . ';' . $new_styles : $new_styles;
			$p->set_attribute( 'style', $final_style );
		}
	}
	$block_content = $p->get_updated_html();

	// Add the SVG icon either to the left or right of the button text.
	$icon_markup = '<span class="wp-block-button__link-icon' . $icon_color_class . '" aria-hidden="true"' . $icon_style_attr . '>' . $icon . '</span>';
	
	$block_content = $position_left
		? preg_replace( '/(<a[^>]*>)(.*?)(<\/a>)/i', '$1' . $icon_markup . '$2$3', $block_content )
		: preg_replace( '/(<a[^>]*>)(.*?)(<\/a>)/i', '$1$2' . $icon_markup . '$3', $block_content );

	return $block_content;
}
add_filter( 'render_block_core/button', 'enable_button_icons_render_block_button', 10, 2 );
