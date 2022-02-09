<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       https://abdulwahab.live/
* @since      1.0.0
*
* @package    Meal_Prep
* @subpackage Meal_Prep/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Meal_Prep
* @subpackage Meal_Prep/admin
* @author     Abdul Wahab <rockingwahab9@gmail.com>
*/
class Meal_Prep_Admin {

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
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	* Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/meal-prep-admin.css', array(), $this->version, 'all' );

	}

	/**
	* Register the JavaScript for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {

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

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/meal-prep-admin.js', array( 'jquery','acf-input' ), $this->version, false );

	}

	public function admin_menu(){
		add_submenu_page(
			'edit.php?post_type=user_favourite',
			__( 'Dislikes', 'textdomain' ),
			__( 'Dislikes', 'textdomain' ),
			'manage_options',
			'aw-dislikes',
			array($this,'meal_prep_callback'),
		);
	}

	public function meal_prep_callback(){
		include AW_MEAL_PATH . "admin/partials/meal-prep-admin-display.php" ;
	}

	public function admin_footer(){
		?>
		<script>
		var $ = jQuery;
		$(document).ready(function(){

			if($('#post_type').length > 0 && $("#post_type").val() == "meals"){
				$('#titlewrap').append(`<div class="suggesstion-box-list" id="title-suggesstions"></div>`);

				$("#titlewrap input").keyup(function(){
					var data = {

						action: "aw_recipie_autocomplete",
						term:$(this).val()
					};
					$.post("<?= admin_url('admin-ajax.php')?>",data,function(res){

						console.log(res);
					});
				});
			}

		});
		</script>
		<?php
	}

	public function aw_recipie_autocomplete(){

		$s = $_POST['term'];

		wp_die();
	}

	public function init(){
		$labels = array(
			'name'                  => _x( 'Meals', 'Post type general name', 'recipe' ),
			'singular_name'         => _x( 'Meal', 'Post type singular name', 'recipe' ),
			'menu_name'             => _x( 'Meals', 'Admin Menu text', 'recipe' ),
			'name_admin_bar'        => _x( 'Meal', 'Add New on Toolbar', 'recipe' ),
			'add_new'               => __( 'Add New', 'recipe' ),
			'add_new_item'          => __( 'Add New Meal', 'recipe' ),
			'new_item'              => __( 'New Meal', 'recipe' ),
			'edit_item'             => __( 'Edit Meal', 'recipe' ),
			'view_item'             => __( 'View Meal', 'recipe' ),
			'all_items'             => __( 'All Meals', 'recipe' ),
			'search_items'          => __( 'Search Meals', 'recipe' ),
			'parent_item_colon'     => __( 'Parent Meals:', 'recipe' ),
			'not_found'             => __( 'No Meals found.', 'recipe' ),
			'not_found_in_trash'    => __( 'No Meals found in Trash.', 'recipe' ),
			'featured_image'        => _x( 'Meal Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'recipe' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'recipe' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'recipe' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'recipe' ),
			'archives'              => _x( 'Meal archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'recipe' ),
			'insert_into_item'      => _x( 'Insert into Meal', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'recipe' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this Meal', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'recipe' ),
			'filter_items_list'     => _x( 'Filter Meals list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'recipe' ),
			'items_list_navigation' => _x( 'Meals list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'recipe' ),
			'items_list'            => _x( 'Meals list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'recipe' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Meals custom post type.',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu' => true,//'meal-prep',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'meals' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'thumbnail' ),
			//  'taxonomies'         => array( 'category', 'post_tag' ),
			'show_in_rest'       => false
		);

		register_post_type( 'meals', $args );

		register_taxonomy(
			'badges', //taxonomy
			'meals', //post-type
			array(
				'hierarchical'  => false,
				'label'         => __( 'Badges','taxonomy general name'),
				'singular_name' => __( 'Badge', 'taxonomy general name' ),
				'rewrite'       => true,
				'query_var'     => true
			));

			register_taxonomy(
				'primary', //taxonomy
				'meals', //post-type
				array(
					'hierarchical'  => false,
					'label'         => __( 'Primary','taxonomy general name'),
					'singular_name' => __( 'Primary', 'taxonomy general name' ),
					'rewrite'       => true,
					'query_var'     => true
				));


				$labels = array(
					'name'                  => _x( 'Weeks', 'Post type general name', 'recipe' ),
					'singular_name'         => _x( 'Weeks', 'Post type singular name', 'recipe' ),
					'menu_name'             => _x( 'Weeks', 'Admin Menu text', 'recipe' ),
					'name_admin_bar'        => _x( 'Weeks', 'Add New on Toolbar', 'recipe' ),
					'add_new'               => __( 'Add New', 'recipe' ),
					'add_new_item'          => __( 'Add New Weeks', 'recipe' ),
					'new_item'              => __( 'New Weeks', 'recipe' ),
					'edit_item'             => __( 'Edit Weeks', 'recipe' ),
					'view_item'             => __( 'View Weeks', 'recipe' ),
					'all_items'             => __( 'All Weeks', 'recipe' ),
					'search_items'          => __( 'Search Weeks', 'recipe' ),
				);

				$args = array(
					'labels'             => $labels,
					'description'        => 'Weeks custom post type.',
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu' => true,//'meal-prep',
					'query_var'          => true,
					'rewrite'            => array( 'slug' => 'weeks-menu' ),
					'capability_type'    => 'post',
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => 21,
					'supports'           => array( 'title', 'thumbnail' ),
					//  'taxonomies'         => array( 'category', 'post_tag' ),
					'show_in_rest'       => false
				);

				register_post_type( 'weeks-menu', $args );

				if( function_exists('acf_add_options_sub_page') ) {

					// Add sub page.
					$child = acf_add_options_sub_page(array(
						'page_title'  => __('Settings'),
						'menu_title'  => __('Settings'),
						'parent_slug' => "edit.php?post_type=weeks-menu",
					));
				}

				$labels = array(
					'name'                  => _x( 'User Favourites', 'Post type general name', 'recipe' ),
					'singular_name'         => _x( 'User Favourites', 'Post type singular name', 'recipe' ),
					'menu_name'             => _x( 'User Favourites', 'Admin Menu text', 'recipe' ),
					'name_admin_bar'        => _x( 'User Favourites', 'Add New on Toolbar', 'recipe' ),
					'add_new'               => __( 'Add New', 'recipe' ),
					'add_new_item'          => __( 'Add New User Favourites', 'recipe' ),
					'new_item'              => __( 'New User Favourites', 'recipe' ),
					'edit_item'             => __( 'Edit User Favourites', 'recipe' ),
					'view_item'             => __( 'View User Favourites', 'recipe' ),
					'all_items'             => __( 'All User Favourites', 'recipe' ),
					'search_items'          => __( 'Search User Favourites', 'recipe' ),
				);

				$args = array(
					'labels'             => $labels,
					'description'        => 'User Favourites custom post type.',
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu' => true,//'meal-prep',
					'query_var'          => true,
					'rewrite'            => array( 'slug' => 'user_favourite' ),
					'capability_type'    => 'post',
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => 23,
					'supports'           => array( 'title' ),
					//  'taxonomies'         => array( 'category', 'post_tag' ),
					'show_in_rest'       => false
				);

				register_post_type( 'user_favourite', $args );

				include AW_MEAL_PATH . "admin/acf-fields.php";

			}

			public function save_post($post_id){



			}

			public function change_value_select($field){

				return $field;
			}

			public function get_alac_data($url){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://www.alacalc.com/api/v1/sessions');
				curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "email=hi@alphatrait.com&api_access_key=7f82c4a5727c67460ffee8d3abb10ad8");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_COOKIESESSION, true);
				curl_setopt($ch, CURLOPT_COOKIEJAR, 'alacalc_cookie');  //could be empty, but cause problems on some hosts
				curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts
				$answer = curl_exec($ch);
				if (curl_error($ch)) {
					echo curl_error($ch);
				}

				$headers = array(


					'Cookie: alacalc_cookie'
				);

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch );

				return $response;
			}

			public function post_alac($data,$url){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://www.alacalc.com/api/v1/sessions');
				curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "email=hi@alphatrait.com&api_access_key=7f82c4a5727c67460ffee8d3abb10ad8");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_COOKIESESSION, true);
				curl_setopt($ch, CURLOPT_COOKIEJAR, 'alacalc_cookie');  //could be empty, but cause problems on some hosts
				curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts
				$answer = curl_exec($ch);
				if (curl_error($ch)) {
					echo curl_error($ch);
				}

				$headers = array(

					'Cookie: alacalc_cookie'
				);

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch );

				return $response;
			}

			public function get_spoonacular_data($url){
				$url = $url . "&apiKey=6e88a8eb870240c4aec048f50512a163";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, false);
				//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($ch);
				curl_close($ch);
				return $response;
			}

			public function aw_auto_complete(){

				$val = $_POST['val'];

				$url = "https://api.spoonacular.com/food/ingredients/search?query={$val}&number=5";
				$result = $this->get_spoonacular_data($url);

				$result = json_decode($result,true);
				if(!empty($result)) {

					echo  json_encode($result);
					wp_die();

				}
			}

			public function aw_get_weights(){

				$id = $_POST['id'];

				$url = "https://api.spoonacular.com/food/ingredients/{$id}/information?amount=1";
				$result = $this->get_spoonacular_data($url);

				echo $result;
				wp_die();

			}

			public function add_meta_box(){
				add_meta_box( 'meta-box-id123', __( 'User Favourites', 'textdomain' ), array($this,'user_favourite_meta_box'), 'user_favourite' );
			}

			public function user_favourite_meta_box(){

				include AW_MEAL_PATH . "admin/partials/metabox.php";
			}

		}
		
