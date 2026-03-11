<?php
/*
Plugin Name: WP Universal Webhooks
Description: Send WordPress events to external services using configurable webhooks.
Version: 1.0
Author: Example Developer
*/

if(!defined('ABSPATH')) exit;

define('WPUNW_PATH', plugin_dir_path(__FILE__));
define('WPUNW_URL', plugin_dir_url(__FILE__));

require_once WPUNW_PATH.'includes/Autoloader.php';

WPUniversalWebhooks\Autoloader::register();

function wpunw_boot(){

    $plugin = new WPUniversalWebhooks\Plugin();
    $plugin->init();

}

wpunw_boot();