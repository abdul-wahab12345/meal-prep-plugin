<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://abdulwahab.live/
* @since      1.0.0
*
* @package    Meal_Prep
* @subpackage Meal_Prep/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the public-facing stylesheet and JavaScript.
*
* @package    Meal_Prep
* @subpackage Meal_Prep/public
* @author     Abdul Wahab <rockingwahab9@gmail.com>
*/
class Meal_Prep_Public {

	/**
	* The ID of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $plugin_name    The ID of this plugin.
	*/
	private $plugin_name;

	/**
	* The version of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $version    The current version of this plugin.
	*/
	private $version;

	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param      string    $plugin_name       The name of the plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	* Register the stylesheets for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {

		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in Meal_Prep_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Meal_Prep_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/meal-prep-public.css', array(), $this->version, 'all' );

	}

	/**
	* Register the JavaScript for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/meal-prep-public.js', array( 'jquery' ), $this->version, false );

	}

	public function the_content($content){

		global $post;
		$img = get_field('nutrition_image',$post->ID);

		if(	$img ){
			$img  = "<img src='{$img}'/>";
		}


		return str_replace("AW_NUTRITION_IMAGE",$img,$content);

	}

	public function woo_menu_items($menu_links){
		$new = array( 'weeks-meals' => "Week's Meals",'favourite-meals' => "Favourite Meals" );

		$menu_links = array_slice( $menu_links, 0, 1, true )
		+ $new
		+ array_slice( $menu_links, 1, NULL, true );


		return $menu_links;
	}

	public function init(){
		add_rewrite_endpoint( 'favourite-meals', EP_PAGES );
		add_rewrite_endpoint( 'weeks-meals', EP_PAGES );
		flush_rewrite_rules();
	}

	public function meals_callback(){
		include AW_MEAL_PATH . "public/partials/meals.php";
	}

	public function favourites_callback(){
		include AW_MEAL_PATH . "public/partials/favourites.php";
	}

	public function add_to_favourites(){
		$id = $_POST['id'];

		$user_id = get_current_user_id();
		$user = get_userdata($user_id);

		$user_fav_post = get_user_meta($user_id,"aw_fav_post",true);

		if($user_fav_post){
			add_post_meta($user_fav_post,'aw_fav_meal',$id);
		}else{

			$post = wp_insert_post(['post_type' => "user_favourite",'post_title' => "#$user_id - $user->display_name",'post_status' => "publish"]);
			update_user_meta($user_id,"aw_fav_post",$post);
			add_post_meta($post,'aw_fav_meal',$id);

		}

		wp_die();
	}

	public function remove_from_favourites(){
		$id = $_POST['id'];

		$user_id = get_current_user_id();

		$user_fav_post = get_user_meta($user_id,"aw_fav_post",true);

		if($user_fav_post){
			delete_post_meta($user_fav_post,'aw_fav_meal',$id);
		}

		wp_die();
	}

	public function single_meal($template){

		global $post;

		if($post->post_type == "meals" && is_single()){
			$template = AW_MEAL_PATH . "public/partials/single-meals.php";
		}

		if($post->post_type == "weeks-menu" && is_single()){
			$template = AW_MEAL_PATH . "public/partials/single-weeks-menu.php";
		}
		$your_meal = get_option("aw_your_meal_page");

		if($post->ID == $your_meal){
			$template = AW_MEAL_PATH . "public/partials/your-meal.php";
		}

		return $template;
	}


}
