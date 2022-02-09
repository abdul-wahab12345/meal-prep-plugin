<?php

/**
* The file that defines the core plugin class
*
* A class definition that includes attributes and functions used across both the
* public-facing side of the site and the admin area.
*
* @link       https://abdulwahab.live/
* @since      1.0.0
*
* @package    Meal_Prep
* @subpackage Meal_Prep/includes
*/

/**
* The core plugin class.
*
* This is used to define internationalization, admin-specific hooks, and
* public-facing site hooks.
*
* Also maintains the unique identifier of this plugin as well as the current
* version of the plugin.
*
* @since      1.0.0
* @package    Meal_Prep
* @subpackage Meal_Prep/includes
* @author     Abdul Wahab <rockingwahab9@gmail.com>
*/
class Meal_Prep {

	/**
	* The loader that's responsible for maintaining and registering all hooks that power
	* the plugin.
	*
	* @since    1.0.0
	* @access   protected
	* @var      Meal_Prep_Loader    $loader    Maintains and registers all hooks for the plugin.
	*/
	protected $loader;

	/**
	* The unique identifier of this plugin.
	*
	* @since    1.0.0
	* @access   protected
	* @var      string    $plugin_name    The string used to uniquely identify this plugin.
	*/
	protected $plugin_name;

	/**
	* The current version of the plugin.
	*
	* @since    1.0.0
	* @access   protected
	* @var      string    $version    The current version of the plugin.
	*/
	protected $version;

	/**
	* Define the core functionality of the plugin.
	*
	* Set the plugin name and the plugin version that can be used throughout the plugin.
	* Load the dependencies, define the locale, and set the hooks for the admin area and
	* the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function __construct() {
		if ( defined( 'MEAL_PREP_VERSION' ) ) {
			$this->version = MEAL_PREP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'meal-prep';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	* Load the required dependencies for this plugin.
	*
	* Include the following files that make up the plugin:
	*
	* - Meal_Prep_Loader. Orchestrates the hooks of the plugin.
	* - Meal_Prep_i18n. Defines internationalization functionality.
	* - Meal_Prep_Admin. Defines all hooks for the admin area.
	* - Meal_Prep_Public. Defines all hooks for the public side of the site.
	*
	* Create an instance of the loader which will be used to register the hooks
	* with WordPress.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function load_dependencies() {

		/**
		* The class responsible for orchestrating the actions and filters of the
		* core plugin.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-meal-prep-loader.php';

		/**
		* The class responsible for defining internationalization functionality
		* of the plugin.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-meal-prep-i18n.php';

		/**
		* The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-meal-prep-admin.php';

		/**
		* The class responsible for defining all actions that occur in the public-facing
		* side of the site.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-meal-prep-public.php';

		$this->loader = new Meal_Prep_Loader();

	}

	/**
	* Define the locale for this plugin for internationalization.
	*
	* Uses the Meal_Prep_i18n class in order to set the domain and to register the hook
	* with WordPress.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function set_locale() {

		$plugin_i18n = new Meal_Prep_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	* Register all of the hooks related to the admin area functionality
	* of the plugin.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function define_admin_hooks() {

		$plugin_admin = new Meal_Prep_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'init', $plugin_admin, 'init' );
		$this->loader->add_action( 'wp_ajax_aw_auto_complete', $plugin_admin, 'aw_auto_complete' );

		$this->loader->add_action( 'wp_ajax_aw_recipie_autocomplete', $plugin_admin, 'aw_recipie_autocomplete' );

		$this->loader->add_action( 'wp_ajax_aw_get_weights', $plugin_admin, 'aw_get_weights' );
		$this->loader->add_action( 'acf/save_post', $plugin_admin, 'save_post' );
		$this->loader->add_filter( 'acf/load_field/name=ing_unit', $plugin_admin, 'change_value_select' );

		$this->loader->add_action("add_meta_boxes",$plugin_admin,"add_meta_box");
		$this->loader->add_action("admin_footer",$plugin_admin,"admin_footer");

	}

	/**
	* Register all of the hooks related to the public-facing functionality
	* of the plugin.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function define_public_hooks() {

		$plugin_public = new Meal_Prep_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'elementor/frontend/the_content', $plugin_public, 'the_content' );
		$this->loader->add_filter("woocommerce_account_menu_items",$plugin_public,"woo_menu_items");
		$this->loader->add_action("init",$plugin_public,"init");
		$this->loader->add_action("woocommerce_account_weeks-meals_endpoint",$plugin_public,"meals_callback");
		$this->loader->add_action("woocommerce_account_favourite-meals_endpoint",$plugin_public,"favourites_callback");
		$this->loader->add_action("wp_ajax_add_to_favourites",$plugin_public,"add_to_favourites");
		$this->loader->add_action("wp_ajax_nopriv_add_to_favourites",$plugin_public,"add_to_favourites");

		$this->loader->add_action("wp_ajax_remove_from_favourites",$plugin_public,"remove_from_favourites");
		$this->loader->add_action("wp_ajax_nopriv_remove_from_favourites",$plugin_public,"remove_from_favourites");

		$this->loader->add_action("template_include",$plugin_public,"single_meal");

	}

	/**
	* Run the loader to execute all of the hooks with WordPress.
	*
	* @since    1.0.0
	*/
	public function run() {
		$this->loader->run();
	}

	/**
	* The name of the plugin used to uniquely identify it within the context of
	* WordPress and to define internationalization functionality.
	*
	* @since     1.0.0
	* @return    string    The name of the plugin.
	*/
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	* The reference to the class that orchestrates the hooks with the plugin.
	*
	* @since     1.0.0
	* @return    Meal_Prep_Loader    Orchestrates the hooks of the plugin.
	*/
	public function get_loader() {
		return $this->loader;
	}

	/**
	* Retrieve the version number of the plugin.
	*
	* @since     1.0.0
	* @return    string    The version number of the plugin.
	*/
	public function get_version() {
		return $this->version;
	}

}
