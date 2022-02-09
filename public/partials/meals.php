<?php

if(isset($_GET['aw_show_single'])){

  include AW_MEAL_PATH . "public/partials/meals-single.php";

  return;
}

$current_time = current_time('timestamp');


$user_id = get_current_user_id();

if(isset($_POST['aw_feedback'])){
  $sms = $_POST['aw_sms'];
  $aw_id = $_POST['id'];

  $user = get_userdata($user_id);

  $user_fav_post = get_user_meta($user_id,"aw_fav_post",true);

  if($user_fav_post){
    add_post_meta($user_fav_post,'aw_dislike_meal',['meal' => $aw_id,'sms' => $sms,'primary' => $_POST['primary']]);
  }else{

    $post = wp_insert_post(['post_type' => "user_favourite",'post_title' => "#$user_id - $user->display_name",'post_status' => "publish"]);
    update_user_meta($user_id,"aw_fav_post",$post);
    add_post_meta($post,'aw_dislike_meal',['meal' => $aw_id,'sms' => $sms,'primary' => $_POST['primary']]);

  }
}


$current_week = get_field("aw_current_week","option");


$aw_current_week_date = get_option("aw_current_week_date");
$week_index = get_option("aw_week_index");
if(!$aw_current_week_date){
  $counter = 1;

  while (have_rows("aw_weeks","option")) {
    the_row();
    $week = get_sub_field("week");
    if($current_week == $week){
      $week_index = $counter;
    }
    $counter++;

  }
  update_option('aw_week_index',$week_index);

  $sunday = new DateTime(date("Y-m-d",$current_time));
  $sunday->modify("Next Sunday");
  $sunday->setTime(23,59,59);

  update_option("aw_current_week_date",$sunday->format("Y-m-d"));
}else{
  $aw_current_week_number = get_option("aw_current_week_date");

  if(date("Y-m-d",$current_time) > $aw_current_week_number){

    $sunday = new DateTime(date("Y-m-d",$current_time));
    $sunday->modify("Next Sunday");
    $sunday->setTime(23,59,59);

    update_option("aw_current_week_date",$sunday->format("Y-m-d"));

    $counter = 1;
    $week_index = $week_index + 1;

    while (have_rows("aw_weeks","option")) {
      the_row();
      $week = get_sub_field("week");
      if($counter == ($week_index)){
        update_field("aw_current_week",$week,"option");
      }
      $counter++;

    }

    update_option('aw_week_index',$week_index);

  }

}

$all_weeks = get_field("aw_weeks","option");

if(count($all_weeks) > 0 && count($all_weeks) < $week_index){
  $week_index = 1;
  update_option('aw_week_index',$week_index);

  $counter = 1;

  while (have_rows("aw_weeks","option")) {
    the_row();
    $week = get_sub_field("week");
    if($counter == 1){
      update_field("aw_current_week",$week,"option");
    }
    $counter++;

  }

}
$user_id = get_current_user_id();
$current_week = get_field("aw_current_week","option");

if($current_week){


  $user_fav_post = get_user_meta($user_id,"aw_fav_post",true);
  $user_fav = [];
  if($user_fav_post){
    $user_fav = get_post_meta($user_fav_post,'aw_fav_meal');
    $temp_fav = [];
    if($user_fav){
      foreach ($user_fav as $key => $value) {
        $temp_fav[] = $value;
      }

      $user_fav = $temp_fav;
    }
  }

  $user_subscriptions = wcs_get_users_subscriptions($user_id);
  $user_products = [];

  foreach ($user_subscriptions as $subscription) {
    if ($subscription->get_status() != "active") {
      continue;
    }
    $products = $subscription->get_items();
    foreach ($products as $item) {
      $product_id = $item->get_product_id();
      $user_products[] = $product_id;
    }
  }

  $all_meals = [];

  while (have_rows('weeks_planner',$current_week)) {
    the_row();
    $planner = get_sub_field('planner_product');
    //check if user have this product in subscription

    if (!in_array($planner,$user_products)) {
      continue;
    }

    foreach (get_sub_field('meals') as $key => $value) {
      $all_meals[] = $value;
    }

  }

  ?>


  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">


  <section id="mobile-meals">

    


    <div class="mb-meal-wrap">

      <?php
      foreach ($all_meals as $key => $aw_id) {


        $ingredients = [];
        $carbs = [];
        while (have_rows('ingredients_admin', $aw_id)) {
          the_row();
          $ing = explode(":",get_sub_field('name'));
          $ingredients[] = $ing[0];

          $unit_weights = get_sub_field("unit_weights");
          if(!empty($unit_weights)){
            $unit_weights = json_decode($unit_weights,true);
            foreach ($unit_weights['nutrition']['nutrients'] as $key => $value) {
              $carbs[$value['name']][] = $value;
            }
          }

        }

        $calories = ['weight' => 0,'unit' => ''];
        $Carbohydrates = ['weight' => 0,'unit' => ''];
        $Fat = ['weight' => 0,'unit' => ''];
        $Protein = ['weight' => 0,'unit' => ''];

        foreach ($carbs as $key => $value1) {
          foreach ($value1 as $key1 => $value) {

            switch($key){
              case 'Calories':
              $calories['weight'] = $calories['weight'] + $value['amount'];
              $calories['unit'] = $value['unit'];
              break;
              case 'Carbohydrates':
              $Carbohydrates['weight'] = $Carbohydrates['weight'] + $value['amount'];
              $Carbohydrates['unit'] = $value['unit'];

              break;
              case 'Fat':
              $Fat['weight'] = $Fat['weight'] + $value['amount'];
              $Fat['unit'] = $value['unit'];

              break;
              case 'Protein':
              $Protein['weight'] = $Protein['weight'] + $value['amount'];
              $Protein['unit'] = $value['unit'];

              break;
            }
          }

        }


        ?>
        <div class="mb-menu-wrap bg-dark-gray">

          <a href="?aw_show_single=true&id=<?= $aw_id;?>">
            <div class="mb-meal-content">

              <?php
              $primaries = [];
              while (have_rows('ingredients_admin', $aw_id)) {
                the_row();
                $primary = get_sub_field('primary');
                if(!$primary){
                  continue;
                }



                $term = get_term($primary);
                $primaries[] = $term;

              }


              ?>

              <img src="<?= plugin_dir_url(__FILE__)?>images/vector.svg" data-primary='<?= json_encode($primaries);?>' data-img="<?= get_the_post_thumbnail_url($aw_id)?>" data-id="<?= $aw_id?>"  class="dislike" alt="">

              <?php

              if(in_array($aw_id,$user_fav)){

                ?>
                <img style="height:20px;display:none;" class="aw-add-favourite"  data-id="<?= $aw_id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart.svg" alt="">

                <img style="height:20px;" class="aw-remove-favourite" data-id="<?= $aw_id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart-fill.svg" alt="">


                <?php

              }else{

                ?>
                <img style="height:20px;" class="aw-add-favourite" data-id="<?= $aw_id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart.svg" alt="">

                <img style="height:20px;display:none;" class="aw-remove-favourite"  data-id="<?= $aw_id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart-fill.svg" alt="">
              <?php } ?>
              <h4 class="mb-meal-title"><?= get_the_title($aw_id);?></h4>
              <p class="mb-sub-title"><?= get_field("sub_heading",$aw_id);?></p>

              <div class="mb-mineral-wrap">

                <?php if($calories['weight'] > 0){	?>
                  <div class="mb-m-wrap">
                    <p class="m-quantity clr-green"><?= $calories['weight']?></p>
                    <p class="m-name clr-green">Kcal</p>
                  </div><!--- =============  mb-m-wrap =========== -->

                <?php } ?>

                <?php if($Carbohydrates['weight'] > 0){	?>
                  <div class="mb-m-wrap">
                    <p class="m-quantity clr-green"><?= $Carbohydrates['weight']?> <?= $Carbohydrates['unit']?></p>
                    <p class="m-name clr-green">Carbs</p>
                  </div><!--- =============  mb-m-wrap =========== -->

                <?php } ?>

                <?php if($Fat['weight'] > 0){	?>
                  <div class="mb-m-wrap">
                    <p class="m-quantity clr-green"><?= $Fat['weight']?> <?= $Fat['unit']?></p>
                    <p class="m-name clr-green">Fat</p>
                  </div><!--- =============  mb-m-wrap =========== -->

                <?php } ?>

                <?php if($Protein['weight'] > 0){	?>
                  <div class="mb-m-wrap">
                    <p class="m-quantity clr-green"><?= $Protein['weight']?> <?= $Protein['unit']?></p>
                    <p class="m-name clr-green">Protein</p>
                  </div><!--- =============  mb-m-wrap =========== -->

                <?php } ?>





              </div><!--- =============  mb-mineral-wrap =========== -->

            </div><!--- =============  mb-meal-content =========== -->

            <div class="mb-meal-image">
              <img src="<?= get_the_post_thumbnail_url($aw_id)?>" alt="">
            </div><!--- =============  mb-meal-image =========== -->
          </a>
        </div><!--- =============  mb-meal-wrap =========== -->

      <?php } ?>


    </div><!--- =============  mb-meal-wrap =========== -->

  </section><!--- =============  mobile-meal =========== -->


  <?php
}

?>


<div class="aw-feedback-popup" style="z-index: 9999; overflow: scroll;">
  <i class="fa fa-times remove-pop"></i>
  <form action="" method="post" class="aw-form">
    <div class="pop-wrap bg-dark-gray">
      <div class="aw-pop-img align-center">
        <img id="meal_img" src="" alt="">
      </div>

      <label for="">Select primary you don't like</label>
      <div id="aw-primary">



      </div>

      <input type="hidden" name="id" id="meal_id">
      <label for="">What don’t you like about this meal?</label>
      <textarea name="aw_sms" id="" cols="30" rows="10"></textarea>

    </div><!-- ============  pop-wrap ========= ----->

    <input type="Submit" value="Submit Feedback" class="btn-feedback" name="aw_feedback">

  </form>
</div><!-- ============  aw-feedback-popup ========= ----->


<style>

.aw-label{    color: #e0e0e0;
  font-size: 14px;
  line-height: 30px;}

  </style>
  <script>

  var $ = jQuery;

  $(document).ready(function(){

    $(".dislike").click(function(e){
      e.preventDefault();
      $('#meal_id').val($(this).data('id'));


      var primary = $(this).data('primary');

      if(primary != ""){
        console.log(primary);
        $('#aw-primary').html(null);
        var htm = ``;
        for (var i = 0; i < primary.length; i++) {
          var pr = primary[i];


          $('#aw-primary').append(`<label class="aw-label"><input type="checkbox" name="primary[]" value="${pr.term_id}" > ${pr.name}</label><br>`);


        }

      }

      $('#meal_img').attr('src',$(this).data('img'));

      $(".aw-feedback-popup").show();


      setTimeout(function(){  $(".aw-form").css({"transform":"translateY(0)"}); }, 300);

    });

    $(".remove-pop").click(function(){

      $(".aw-form").css({"transform":"translateY(200%)"});

      setTimeout(function(){  $(".aw-feedback-popup").hide(); }, 300);

    });



    $(".aw-add-favourite").click(function(e){
      e.preventDefault();
      var button = $(this);

      var data = {
        action : "add_to_favourites",
        id : $(this).data('id')
      }

      $.post("<?= admin_url('admin-ajax.php')?>",data,function(){
        button.hide();
        button.parent().find('.aw-remove-favourite').show();
      });
    });

    $(".aw-remove-favourite").click(function(e){
      e.preventDefault();
      var button = $(this);

      var data = {
        action : "remove_from_favourites",
        id : $(this).data('id')
      }

      $.post("<?= admin_url('admin-ajax.php')?>",data,function(){
        button.hide();
        button.parent().find('.aw-add-favourite').show();
      });
    });

  });

</script>
