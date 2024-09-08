<?php 
/**
 * Plugin name: Custom Bundle Product Manager
 * Description: Custom WooCommerce addon plugin that allows users to create and manage bundle products.
 * Plugin URI: https://www.example.com/
 * Author: Nikita Modhavadiya
 * Author URI: https://example.com
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Requires at least: 6.3.2
 */

define("CBPM_PLUGIN_PATH", plugin_dir_path(__FILE__));
define("CBPM_PLUGIN_URL", plugin_dir_url(__FILE__));
define("CBPM_PLUGIN_BASENAME", plugin_basename(__FILE__));


include_once CBPM_PLUGIN_PATH.'class/BundleProduct.php';

$bundleproductObj = new BundleProduct();

register_activation_hook(__FILE__, array($bundleproductObj, "createBundleTable"));

register_deactivation_hook(__FILE__, array($bundleproductObj, "deleteBundleTable"));