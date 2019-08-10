<?php
/**
 * Plugin Name: WPA Share
 * Plugin URI:  https://wpassist.me/plugins/wpa-share/
 * Description: Performace optimized sharing plugin for high volume WordPress websites.
 * Version:     1.0.1
 * Author:      Metin Saylan
 * Author URI:  https://metinsaylan.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: wpa-share
 * Domain Path: /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

define( 'WPA_SHARE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPA_SHARE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if( ! function_exists( 'wpa_share' ) ){

    global $wpa_share;

    /* Include plugin core */
    require_once( WPA_SHARE_PLUGIN_PATH . 'inc/class-wpa-plugin.php' );
    require_once( WPA_SHARE_PLUGIN_PATH . 'wpa-share-options.php' );
    
    $wpa_share = new WPA_Plugin(
        'WPA Share',
        'wpa-share', 
        WPA_SHARE_PLUGIN_PATH
    );

    $wpa_share->options = $wpa_share_options;
    $wpa_share->options_nav = array( 
        array(
            'label' => 'Plugin Page',
            'link' => 'https://wpassist.me/plugins/wpa-share/'
        ),
        array(
            'label' => 'Usage',
            'link' => 'https://wpassist.me/docs/wpa-share-usage/'
        ),
        array(
            'label' => 'Shortcode',
            'link' => 'https://wpassist.me/docs/wpa-share-shortcode/'
        ),
        array(
            'label' => 'Donate',
            'link' => 'https://wpassist.me/donate/'
        )
    );

    /* Thin wrap for wpa_plugin */
    function get_wpa_share_option( $key, $default ){
        global $wpa_share;
        return $wpa_share->get_setting( $key, $default );
    }

    add_action( 'wp_head', 'wpa_share_print_styles' );
    function wpa_share_print_styles(){

        $output_css = get_wpa_share_option( 'output-css', 'on' );

        if( 'on' === $output_css ){ 
            $style = get_wpa_share_option( 'inpost-style', 'flex' );

        ?>
<style>.sr-only{position:absolute;width:1px;height:1px;padding:0;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}.wpa-share{display:block;padding:8px;margin:0 auto 15px;text-align:center}.wpa-share-label{display:inline-block;margin-right:20px}.wpa-share-btn svg{margin:4px;padding:4px;vertical-align:middle}<?php 

            if( 'basic' === $style ){
                ?>.wpa-share-btn{display:inline-block;background:rgba(0,0,0,.05);color:#aaa;text-decoration:none;border-radius:8px;margin:0 5px;box-shadow:none;width:100px;height:50px}<?php

            } elseif( 'round' === $style ){
                ?>.wpa-share-btn{display:inline-block;background:rgba(255,255,255,.8);color:#aaa;text-decoration:none;border-radius:50%;margin:0 10px;box-shadow:rgba(0,0,0,.2) 0 2px 5px;width:50px;height:50px}<?php
            } else {
                ?>.wpa-share{display:flex}.wpa-share-btn{display:inline-block;background:rgba(0,0,0,.05);color:#aaa;text-decoration:none;border-radius:8px;margin:0 5px;box-shadow:none;width:auto;height:50px;flex-grow:1;text-align:center}<?php
            } 

            echo '</style>';
        }
    }

    function wpa_share(){
        echo wpa_share_get_buttons();
    }

    add_shortcode('share', 'wpa_share_shortcode');
    function wpa_share_shortcode( $atts, $content = null ){

        extract( shortcode_atts( array(
            'label' => ''
        ), $atts) );

        return wpa_share_get_buttons( $label );
    }

    function wpa_share_get_buttons( $label = '' ){
        
        $share_buttons = '';

        if( is_single() || is_page() ){
            $link = urlencode( get_the_permalink() );
            $desc = urlencode( get_the_title() ); 
            $img_obj = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full', false, '' ); 
            $img_src = urlencode( $img_obj[0] );

            $share_buttons .= '<div class="wpa-share">';

            if( strlen( $label ) > 0 ){
                $share_buttons .= '<span class="wpa-share-label">' . $label . ':</span>';
            }

            $share_buttons .= '<a class="wpa-share-btn" target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u='. $link . '&t=' . $desc . '"><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg> <span class="sr-only">'. __( 'Share', 'wpa-share' ) . '</span></a>';
            
            $share_buttons .= '<a class="wpa-share-btn" target="_blank" title="Tweet this post" href="http://twitter.com/share?url='. $link . '&text=' . $desc . '"><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg> <span class="sr-only">'. __( 'Tweet', 'wpa-share' ) . '</span></a>';

            $share_buttons .= '<a  class="wpa-share-btn" target="_blank" title="Pin on Pinterest" href="http://pinterest.com/pin/create/button/?url='. $link . '&media='. $img_src . '&description='. $desc . '" ><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg> <span class="sr-only">'. __( 'Pin', 'wpa-share' ) . '</span></a>';

            $share_buttons .= '</div>';

        } 
        
        return $share_buttons;
    }

    /* Add settings link to plugin links */
    $wpa_share_hook = plugin_basename(__FILE__);
    add_filter( "plugin_action_links_$wpa_share_hook", 'wpa_share_add_settings_link' );
    function wpa_share_add_settings_link( $links ) {
        global $wpa_share;
        
        $settings_link = '<a href="options-general.php?page=' . $wpa_share->options_page . '">Settings</a>';
        array_push( $links, $settings_link );
        return $links;
    }
    
    add_filter( 'the_content', 'wpa_share_auto_insert_filter' );
    function wpa_share_auto_insert_filter( $content ) {
        global $post;

        /* Check auto-insert option */
        $wpa_auto_insert = get_wpa_share_option( 'auto-insert', 'off' );
        if( 'off' === $wpa_auto_insert ) {
            return $content;
        }

        $auto_insert_location = get_wpa_share_option( 'auto-insert-location', '' );

        $auto_insert_label = '';
        $share_buttons_html = wpa_share_get_buttons( $auto_insert_label );

        if( 'before-post' === $auto_insert_location ){
            return $share_buttons_html . $content;
        } elseif ( 'after-post' === $auto_insert_location ){
            return $content . $share_buttons_html;
        } else {
            return $share_buttons_html . $content . $share_buttons_html;
        }

    }
    
}
