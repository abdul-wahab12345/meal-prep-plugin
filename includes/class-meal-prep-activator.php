<?php

/**
* Fired during plugin activation
*
* @link       https://abdulwahab.live/
* @since      1.0.0
*
* @package    Meal_Prep
* @subpackage Meal_Prep/includes
*/

/**
* Fired during plugin activation.
*
* This class defines all code necessary to run during the plugin's activation.
*
* @since      1.0.0
* @package    Meal_Prep
* @subpackage Meal_Prep/includes
* @author     Abdul Wahab <rockingwahab9@gmail.com>
*/
class Meal_Prep_Activator {

	/**
	* Short Description. (use period)
	*
	* Long Description.
	*
	* @since    1.0.0
	*/
	public static function activate() {
		$your_meal = get_option("aw_your_meal_page");
		if(!$your_meal){

			$id = wp_insert_post(["post_type" => "page",'post_title' => "Your Meal","post_status" => "publish"]);
			update_option("aw_your_meal_page",$id);

		}
	}

}
