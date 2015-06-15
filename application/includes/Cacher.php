<?php
/*
Copyright 2013 - Jean-Sebastien Morisset - http://surniaulula.com/

This script is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later
version.

This script is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details at
http://www.gnu.org/licenses/.

The following ngfbCache() class was written for the NextGEN Facebook OG plugin
for WordPress, available at http://wordpress.org/extend/plugins/nextgen-facebook/.

Example usage:

require_once ( dirname ( __FILE__ ) . '/cache.php' );

$cache = new ngfbCache();
$cache->base_dir = '/var/www/htdocs/cache/';
$cache->base_url = '/cache/';
$cache->pem_file = dirname ( __FILE__ ) . 'curl/cacert.pem';
$cache->verify_cert = true;
$cache->expire_time = 3 * 60 * 60;	// cache the file for 3 hours

$url = $cache->get( $url );		// return a modified url (default)
$raw = $cache->get( $url, 'raw' );	// return the file's content instead

Some source files, like the Google+ plusone.js javascript file, may change
depending on the user agent. In this case, you may want to define the
$cache->user_agent variable as well. The default user agent is the one provided
by the browser (consider that crawlers may refresh the cache files, so
hard-coding a user agent may be desirable). The $cache->base_url can be
relative or include the protocol (http / https), and could be an alias to the
real folder location (which may be outside of the document root). The
$cache->base_dir and $cache->base_url variables should end with a slash.

Slightly adapted by https://github.com/HazCod
*/

if ( ! class_exists( 'Cacher' ) ) {

	class Cacher {

		var $base_dir = '';
		var $base_url = '/cache/';
		var $pem_file = '';
		var $verify_cert = false;
		var $expire_time = 0;
		var $user_agent = '';

		function __construct() {
			$this->base_dir = dirname ( __FILE__ ) . '/cache/';
			$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		}

		// $content = true : return the cached content
		// $content = false : return an URL to the content

		function get( $url, $content=true, $force=false ) {

			if ( ! function_exists('curl_init') ) return $url;

			// if we're not using https on the current page, then no need to make our requests using https
			$get_url = empty( $_SERVER['HTTPS'] ) ? preg_replace( '/^https:/', 'http:', $url ) : $url;
			$get_url = preg_replace( '/#.*$/', '', $get_url );
			$url_key = md5( $get_url );

			$url_path = parse_url( $get_url, PHP_URL_PATH );
			$url_ext = pathinfo( $url_path, PATHINFO_EXTENSION );
			$url_frag = parse_url( $url, PHP_URL_FRAGMENT );
			if ( ! empty( $url_frag ) ) $url_frag = '#' . $url_frag;

			$cache_file = $this->base_dir . $url_key . '.' . $url_ext;
			$cache_url = $this->base_url . $url_key . '.' . $url_ext . $url_frag;
			$cache_time = time() - $this->expire_time;
			$raw_data = '';

			if ( file_exists( $cache_file ) && filemtime( $cache_file ) > $cache_time && $force == false) {
				$url = $cache_url;
				if ( $content == true ) {
					$fh = fopen( $cache_file, 'rb' );
					$raw_data = fread( $fh, filesize( $cache_file ) );
					fclose( $fh );
				}
			} else {
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $get_url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
				curl_setopt( $ch, CURLOPT_USERAGENT, $this->user_agent );

				if ( empty( $this->verify_cert) ) {
					curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
				} else {
					curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, TRUE );
					curl_setopt( $ch, CURLOPT_CAINFO, $this->pem_file );
				}
				$raw_data = curl_exec( $ch );
				curl_close( $ch );
				if ( ! empty( $raw_data ) ) {
					if ( ! is_dir( $this->base_dir ) ) mkdir( $this->base_dir );
					$fh = fopen( $cache_file, 'wb' );
					if ( ! empty( $fh ) ) {
						if ( fwrite( $fh, $raw_data ) ) $url = $cache_url;
						fclose( $fh );
					}
				}
			}
			if ( $content == true ) return $raw_data;
			else return $url;
		}
	}
}
