<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              https://abdulwahab.live/
* @since             1.0.0
* @package           Meal_Prep
*
* @wordpress-plugin
* Plugin Name:       Meal Prep
* Plugin URI:        https://abdulwahab.live/meal-prep
* Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
* Version:           1.0.0
* Author:            Abdul Wahab
* Author URI:        https://abdulwahab.live/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       meal-prep
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'MEAL_PREP_VERSION', '1.0.0' );
define( 'AW_MEAL_PATH', plugin_dir_path( __FILE__ ));
/**
* Currently plugin version.
* Start at version 1.0.0 and use SemVer - https://semver.org
* Rename this for your plugin and update it as you release new versions.
*/

if(!class_exists("acf_pro")){
	include AW_MEAL_PATH . "acf/acf.php";
}



include_once AW_MEAL_PATH . 'includes/alac-api/alac-api.php';



/**
* The code that runs during plugin activation.
* This action is documented in includes/class-meal-prep-activator.php
*/
function activate_meal_prep() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-meal-prep-activator.php';
	Meal_Prep_Activator::activate();
}

/**
* The code that runs during plugin deactivation.
* This action is documented in includes/class-meal-prep-deactivator.php
*/
function deactivate_meal_prep() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-meal-prep-deactivator.php';
	Meal_Prep_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_meal_prep' );
register_deactivation_hook( __FILE__, 'deactivate_meal_prep' );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-meal-prep.php';

/**
* Begins execution of the plugin.
*
* Since everything within the plugin is registered via hooks,
* then kicking off the plugin from this point in the file does
* not affect the page life cycle.
*
* @since    1.0.0
*/
function run_meal_prep() {

	$plugin = new Meal_Prep();
	$plugin->run();

}
run_meal_prep();
