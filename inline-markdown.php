<?php
/**
 *
 * @package   Inline Markdown
 * @author    Ryan Durham
 * @license   GPL-2.0+
 * @link      http://www.ryandurham.com/projects/inline-markdown-plugin/
 * @copyright 2013 Ryan Durham
 *
 * @wordpress-plugin
 * Plugin Name:       Inline Markdown
 * Plugin URI:        http://www.ryandurham.com/projects/inline-markdown-plugin/
 * Description:       Use [md][/md] shortcodes to embed markdown in a post or page and parse it into HTML on the fly, using ParseDown.
 * Version:           1.0.5
 * Author:            Ryan Durham
 * Author URI:        http://www.ryandurham.com
 * Text Domain:       inline-markdown
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/rydurham/Inline-Markdown-Plugin.git
 *
 *
 * Thanks to Tom McFarlin - https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
        die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 *  Include the Parsedown Library
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/includes/parsedown.php' );

/*----------------------------------------------------------------------------*
 * Core Plugin Functionality
 *----------------------------------------------------------------------------*/

/**
 * Use Parsedown to parse markdown text inside [md][/md] shortcodes.
 * @param  array  $atts WP Shortcode Attributes
 * @return string       HTML formatted string
 */
function inline_markdown( $atts, $content ) {

    $result = Parsedown::instance()->parse($content);

    return $result;
}

/**
 * Process the shortcode before wpautop() & wptexturize() are applied.
 * Thanks to http://betterwp.net/17-protect-shortcodes-from-wpautop-and-the-likes/
 * @param  string $content [description]
 * @return [type]          [description]
 */
function inline_markdown_pre_process_shortcode($content) {
    global $shortcode_tags;

    // Backup current registered shortcodes and clear them all out
    $orig_shortcode_tags = $shortcode_tags;
    $shortcode_tags = array();

    add_shortcode( 'md', 'inline_markdown' );

    // Do the shortcode (only the one above is registered)
    $content = do_shortcode($content);

    // Put the original shortcodes back
    $shortcode_tags = $orig_shortcode_tags;

    return $content;
}
add_filter('the_content', 'inline_markdown_pre_process_shortcode', 7);

