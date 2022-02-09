<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_post_types') ) :


class acf_field_post_types extends acf_field {

	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options


	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/

	function __construct( $settings )
	{
		// vars
		$this->name = 'Post Types';
		$this->label = __('Post Types');
		$this->category = __("Basic",'TEXTDOMAIN'); // Basic, Content, Choice, etc
		$this->defaults = array();


		// do not delete!
    	parent::__construct();


    	// settings
		$this->settings = $settings;

	}



	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function create_field( $field )
	{

		?>
		<div>

		</div>
		<?php
	}


}


// initialize
new acf_field_post_types( $this->settings );


// class_exists check
endif;

?>
