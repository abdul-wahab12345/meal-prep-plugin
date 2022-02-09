<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_post_types') ) :


  class acf_field_post_types extends acf_field {


    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type    function
    *  @date    5/03/2014
    *  @since   5.0.0
    *
    *  @param   n/a
    *  @return  n/a
    */

    function __construct( $settings ) {

      /*
      *  name (string) Single word, no spaces. Underscores allowed
      */

      $this->name = 'search_ingredients';


      /*
      *  label (string) Multiple words, can include spaces, visible when selecting a field type
      */

      $this->label = __('Search ingredients', 'TEXTDOMAIN');


      /*
      *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
      */

      $this->category = 'basic';


      /*
      *  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
      */

      $this->defaults = array(
        'font_size' => 14,
      );


      /*
      *  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
      *  var message = acf._e('FIELD_NAME', 'error');
      */

      $this->l10n = array(
        'error' => __('Error! Please enter a higher value', 'TEXTDOMAIN'),
      );


      /*
      *  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
      */

      $this->settings = $settings;


      // do not delete!
      parent::__construct();

    }




    /*
    *  render_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param   $field (array) the $field being rendered
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $field (array) the $field being edited
    *  @return  n/a
    */

    function render_field( $field ) {
      $val = '';


      if(!empty($field['value'])){
        $va = $field['value'];
        $va = explode(":",$va);
        $val = $va[0];
      }


      ?>

      <div class="frmSearch">
        <input type="text" class="aw-search-fiels" value="<?= $val;?>" placeholder="<?= $field['label']?>" autocomplete="off" />
        <input type="hidden" class="aw-search-fiels-sub"  name="<?php echo esc_attr($field['name']) ?>" id="<?= $field['key']?>" value="<?= !empty($field['value'])?$field['value']:'';?>" placeholder="<?= $field['label']?>" />
        <div class="suggesstion-box-list"></div>
      </div>


      <script type="text/javascript">

      var $ = jQuery;

      $(document).ready(function(){
        $(".aw-search-fiels").keyup(function(){


        });
      });

      function <?= $field['key']?>(val) {
        $("#<?= $field['key']?>").val(val);
        $(".suggesstion-box-<?= $field['key']?>").hide();
      }

      </script>





      <?php
    }


  }


  // initialize
  new acf_field_post_types( $this->settings );


  // class_exists check
endif;

add_action('admin_footer',function(){


  ?>
  <style>

  .suggesstion-box-list{

    float: left;
    list-style: none;
    background: white;
    margin-top: -3px;
    padding: 0;
    width: auto;
    height: 400px;
    position: absolute;
    overflow: scroll;
    z-index: 999;
    display: none;

  }
  .suggesstion-box-list-ul li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
  .suggesstion-box-list-ul li:hover{background:#ece3d2;cursor: pointer!important;}

  </style>
  <script>
  //aw-fill-field

  var $ = jQuery;

  $(document).ready(function(){



  });

  (function($){

    function ing_unit($field){
      console.log($field);

      $field.find('select').change(function(){
        console.log($(this).val());
        $field.parent().find("td[data-name='default_value'] input").removeAttr("disabled").val($(this).val());
        console.log($field.parent().find("td[data-name='default_value'] input"));

      });

    }

    function initialize_field( $field ) {
      //console.log($field);
      //$field.doStuff();

      var input = $field.find('.aw-search-fiels');

      input.keyup(function(){

        var data = {
          action : "aw_auto_complete",
          val:input.val(),

        }
        //  console.log(input)
        //  console.log(data)
        $.ajax({
          type: "POST",
          url: '<?= admin_url("admin-ajax.php");?>',
          data:data,
          beforeSend: function(){
            $(input).css("background","#FFF url(<?= plugin_dir_url( __FILE__ ) . ''?>LoaderIcon.gif) no-repeat 165px");
          },
          success: function(data){

            if(data){

              var ingredients = JSON.parse(data);
              console.log(ingredients);

              var html = "";

              if(ingredients.results != undefined){
                for(var i = 0; i < ingredients.results.length; i++){
                  console.log(ingredients.results[i]);
                  var ing = ingredients.results[i];

                  var ing_a = JSON.stringify(ing).replace("'","");

                  html += "<li data-ing='"+ing_a+"' data-long_desc='"+ing.name+"' class='aw-fill-field'>"+ing.name+"</li>";

                }
              }

              $(input).parent().find(".suggesstion-box-list").show();
              $(input).parent().find(".suggesstion-box-list").html(`<ul class="suggesstion-box-list-ul">${html}</ul>`);
              $(input).css("background","#FFF");

              console.log(html);

              $(".aw-fill-field").click(function(){

                $(input).val($(this).data('long_desc'));

                var ing = $(this).data('ing');
                var dat = ing.name+":"+ing.id;

                $.post("<?= admin_url('admin-ajax.php')?>",{action:"aw_get_weights",id:ing.id},function(res){

                  console.log(res);
                  var data = JSON.parse(res);
                  var html = '';

                  if(data.possibleUnits != undefined){
                    for(var i = 0; i < data.possibleUnits.length; i++){
                      console.log(data.possibleUnits[i]);
                      var weight = data.possibleUnits[i];

                      html += "<option value='"+weight+"'>"+weight+"</option>";

                    }
                  }


                  $field.parent().find('td[data-name="unit_weights"] textarea').html(res).removeAttr('disabled');
                  $field.parent().find('td[data-name="ing_unit"] select').html(html);

                });

                $field.find('.aw-search-fiels-sub').val(dat);

                console.log($(this).data('ing'));

                $(".suggesstion-box-list").hide();

              });

            }else{

            }

          }
        });

      });

    }

    if( typeof acf.add_action !== 'undefined' ) {

      acf.add_action('ready_field/type=search_ingredients', initialize_field);
      acf.add_action('append_field/type=search_ingredients', initialize_field);
      acf.add_action('ready_field/name=ing_unit', ing_unit);
      acf.add_action('append_field/name=ing_unit', ing_unit);

    } else {

      $(document).on('acf/setup_fields', function(e, postbox){

        $(postbox).find('.field[data-field_type="search_ingredients"]').each(function(){

          // initialize
          initialize_field( $(this) );

        });

      });

    }



    $('div[data-name="ingredients_admin"] table tbody tr').each(function(){

      var default_unit = $(this).find('td[data-name="default_value"] input').val();
      var unit_weights = $(this).find('td[data-name="unit_weights"] textarea').val();
      var select = $(this).find('td[data-name="ing_unit"] select');

      if(unit_weights != ""){
        var data = JSON.parse(unit_weights);
        var html = '';

        if(data.possibleUnits != undefined){
          for(var i = 0; i < data.possibleUnits.length; i++){
            console.log(data.possibleUnits[i]);
            var weight = data.possibleUnits[i];
            if(weight == default_unit){
              html += "<option selected value='"+weight+"'>"+weight+"</option>";

            }else{
              html += "<option value='"+weight+"'>"+weight+"</option>";

            }


          }

          select.html(html);

        }

      }

    });

  })(jQuery);

  </script>

  <?php

});

?>
