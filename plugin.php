<?php
/*
Plugin Name: Log XML-RPC Requests
Plugin URI: https://github.com/jeremyfelt/log-xmlrpc-requests/
Description: Log incoming XML-RPC requests as a post type.
Version: 0.0.1
Author: jeremyfelt
Author URI: https://jeremyfelt.com
License: GPLv2 or later
*/

add_action( 'init', 'lxr_register_xmlrpc_log' );
add_action( 'xmlrpc_call', 'lxr_log_xmlrpc_call' );

/**
 * Register the post type used to log XML-RPC requests.
 *
 * @since 0.0.1
 */
function lxr_register_xmlrpc_log() {
	register_post_type(
		'xmlrpc_log',
		array(
			'label'   => 'XML-RPC Logs',
			'public'  => false,
			'show_ui' => true,
		)
	);
}

/**
 * Log an incoming XML-RPC request.
 *
 * Store:
 *   - The IP address(es) associated with the request.
 *   - The user agent making the request.
 *   - (todo) Data attached to the request?
 *
 * @param string $method The XML-RPC method requested.
 */
function lxr_log_xmlrpc_call( $method ) {
	$remote_ip  = $_SERVER['REMOTE_ADDR'];
	$forward_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	$post = wp_insert_post( array(
		'post_type' => 'xmlrpc_log',
		'post_title' => sanitize_text_field( $method ) . ' ' . date('Y-m-d H:i:s' ),
	) );

	update_post_meta( $post, 'remote_ip', sanitize_text_field( $remote_ip ) );
	update_post_meta( $post, 'forward_ip', sanitize_text_field( $forward_ip ) );
	update_post_meta( $post, 'user_agent', sanitize_text_field( $user_agent ) );
}
