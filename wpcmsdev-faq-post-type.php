<?php
/*
Plugin Name: wpCMSdev FAQ Post Type
Plugin URI:  http://wpcmsdev.com/plugins/faq-post-type/
Description: Registers an "FAQ" custom post type.
Author:      wpCMSdev
Author URI:  http://wpcmsdev.com
Version:     1.0
Text Domain: wpcmsdev-faq-post-type
Domain Path: /languages
License:     GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Copyright (C) 2014  wpCMSdev

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


/**
 * Registers the "faq_item" post type.
 */
function wpcmsdev_faqs_post_type_register() {

	$labels = array(
		'name'               => __( 'FAQs',                    'wpcmsdev-faq-post-type' ),
		'singular_name'      => __( 'FAQ',                     'wpcmsdev-faq-post-type' ),
		'all_items'          => __( 'All FAQs',                'wpcmsdev-faq-post-type' ),
		'add_new'            => _x( 'Add New', 'faq',          'wpcmsdev-faq-post-type' ),
		'add_new_item'       => __( 'Add New FAQ',             'wpcmsdev-faq-post-type' ),
		'edit_item'          => __( 'Edit FAQ',                'wpcmsdev-faq-post-type' ),
		'new_item'           => __( 'New FAQ',                 'wpcmsdev-faq-post-type' ),
		'view_item'          => __( 'View FAQ',                'wpcmsdev-faq-post-type' ),
		'search_items'       => __( 'Search FAQs',             'wpcmsdev-faq-post-type' ),
		'not_found'          => __( 'No FAQs found.',          'wpcmsdev-faq-post-type' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash.', 'wpcmsdev-faq-post-type' ),
	);

	$args = array(
		'labels'        => $labels,
		'menu_icon'     => 'dashicons-editor-help',
		'menu_position' => 5,
		'public'        => false,
		'show_ui'       => true,
		'supports'      => array(
			'author',
			'custom-fields',
			'editor',
			'page-attributes',
			'revisions',
			'title',
		),
	);

	$args = apply_filters( 'wpcmsdev_faqs_post_type_args', $args );

	register_post_type( 'faq_item', $args );

}
add_action( 'init', 'wpcmsdev_faqs_post_type_register' );


/**
 * Loads the translation files.
 */
function wpcmsdev_faqs_load_translations() {

	load_plugin_textdomain( 'wpcmsdev-faq-post-type', false, dirname( plugin_basename( __FILE__ ) ) ) . '/languages/';
}
add_action( 'plugins_loaded', 'wpcmsdev_faqs_load_translations' );


/**
 * Initializes additional functionality when used with a theme that declares support for the plugin.
 */
function wpmcsdev_faqs_additional_functionality_init() {

	if ( current_theme_supports( 'wpcmsdev-faq-post-type' ) ) {
		add_action( 'admin_enqueue_scripts',               'wpcmsdev_faqs_manage_posts_css' );
		add_action( 'manage_faq_item_posts_custom_column', 'wpcmsdev_faqs_manage_posts_columm_content' );
		add_filter( 'manage_edit-faq_item_columns',        'wpcmsdev_faqs_manage_posts_columns' );
	}
}
add_action( 'after_setup_theme', 'wpmcsdev_faqs_additional_functionality_init', 11 );


/**
 * Registers custom columns for the Manage FAQs admin page.
 */
function wpcmsdev_faqs_manage_posts_columns( $columns ) {

	$columns['title'] = __( 'Question', 'wpcmsdev-testimonial-post-type' );

	$column_order = array( 'order' => __( 'Order', 'wpcmsdev-testimonial-post-type' ) );

	$columns = array_slice( $columns, 0, 2, true ) + $column_order + array_slice( $columns, 2, null, true );

	return $columns;
}


/**
 * Outputs the custom column content for the Manage FAQs admin page.
 */
function wpcmsdev_faqs_manage_posts_columm_content( $column ) {

	global $post;

	switch( $column ) {

		case 'order':
			$order = $post->menu_order;
			if ( 0 === $order ) {
				echo '<span class="default-value">' . $order . '</span>';
			} else {
				echo $order;
			}
			break;
	}
}


/**
 * Outputs the custom columns CSS used on the Manage FAQs admin page.
 */
function wpcmsdev_faqs_manage_posts_css() {

	global $pagenow, $typenow;
	if ( ! ( 'edit.php' == $pagenow && 'faq_item' == $typenow ) ) {
		return;
	}

?>
<style>
	.edit-php .posts .column-order {
		width: 10%;
	}
	.edit-php .posts .column-order .default-value {
		color: #bbb;
	}
</style>
<?php
}


/**
 * Changes the "Enter title here" text on the FAQ edit screen.
 */
function wpcmsdev_faqs_enter_title_here( $title ) {

	global $post_type;
	if ( 'faq_item' == $post_type ) {
		$title = __( 'Enter question here', 'wpcmsdev-faq-post-type' );
	}

	return $title;
}
add_filter( 'enter_title_here', 'wpcmsdev_faqs_enter_title_here' );
